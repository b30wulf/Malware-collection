# -*- coding: utf-8 -*-
from tests import BaseTestCase


class GroupTestCase(BaseTestCase):
    def setUp(self):
        super(GroupTestCase, self).setUp()
        self.groups = self.hosts.groups

    def test_addGroupWithHost(self):
        self.hosts.create("a", "b", "c", "d", group="e")
        host = self.hosts.get("a")
        self.assertEqual("e", host.group)

    def test_defaultGroupPassword(self):
        group = self.groups.create("some_group", default_password="aa")
        self.hosts.create("default_password", address="a", user="u1", group=group.name)
        host = self.hosts.get("default_password")
        self.assertEqual(host.password, "aa")
        self.assertEqual(host.user, "u1")

    def test_defaultGroupUser(self):
        group = self.groups.create("other_group", default_user_name="usi")
        self.hosts.create("default_user", address="b", password="pass", group=group.name)
        host = self.hosts.get("default_user")
        self.assertEqual(host.password, "pass")
        self.assertEqual(host.user, "usi")

    def test_overrideUserAndPassword(self):
        group = self.groups.create("ignored", default_user_name="ignored_user", default_password="ignored_passwd")
        self.hosts.create("override", address="x", user="u", password="p", group=group.name)
        host = self.hosts.get("override")
        self.assertEqual(host.password, "p")
        self.assertEqual(host.user, "u")

    def test_assignGroup(self):
        self.hosts.create("assign_group", address="x", user="u", password="p")
        self.hosts.assignGroup("assign_group", "some_group")
        host = self.hosts.get("assign_group")
        self.assertEqual(host.group, 'some_group')

        # unassign group
        self.hosts.assignGroup("assign_group", None)
        host = self.hosts.get("assign_group")
        self.assertEqual(host.group, None)