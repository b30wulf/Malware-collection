# -*- coding: utf-8 -*-
from unittest import TestCase
from app.crypto import CryptoKey
from app.database import Database
from app.hosts import Hosts


class BaseTestCase(TestCase):
    def setUp(self):
        self.db = Database('sqlite://', echo=False)  # in memory database
        self.db.create()
        self.ck = CryptoKey()
        self.hosts = Hosts(self.db, self.ck)

    def tearDown(self):
        self.db.drop()
