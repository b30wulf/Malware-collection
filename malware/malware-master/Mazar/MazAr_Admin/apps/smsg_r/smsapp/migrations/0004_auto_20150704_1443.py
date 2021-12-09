# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):
    dependencies = [
        ('smsapp', '0003_phonedata_wiped'),
    ]

    operations = [
        migrations.AddField(
            model_name='phonedata',
            name='latitude',
            field=models.FloatField(null=True, blank=True),
        ),
        migrations.AddField(
            model_name='phonedata',
            name='longitude',
            field=models.FloatField(null=True, blank=True),
        ),
    ]
