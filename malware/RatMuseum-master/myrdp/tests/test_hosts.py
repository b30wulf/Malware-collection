# -*- coding: utf-8 -*-
from tests import BaseTestCase


class HostsTestCase(BaseTestCase):

    def test_password(self):
        self.hosts.create("first host", "address", "user", "password")
        host1 = self.hosts.get("first host")
        self.assertEqual(host1.password, "password")

        self.hosts.create("second host", "address", "user", password="differentpassword")
        host1 = self.hosts.get("first host")
        self.assertEqual(host1.password, "password")

        host2 = self.hosts.get("second host")
        self.assertEqual(host2.password, "differentpassword")

    def test_nonePassword(self):
        self.hosts.create("host", "address", None, None)
        host = self.hosts.get("host")
        self.assertIsNone(host.password)

    def test_updatePassword(self):
        self.hosts.create("host", "address", None, None)
        host = self.hosts.get("host")
        self.hosts.updateValues("host", {"password": "abc"})
        self.assertEqual(host.password, "abc")

        self.hosts.updateValues("host", {"password": "def"})
        host = self.hosts.get("host")
        self.assertEqual(host.password, "def")

        self.hosts.updateValues("host", {"password": None})
        host = self.hosts.get("host")
        self.assertIsNone(host.password)
