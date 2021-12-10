# -*- coding: utf-8 -*-
from collections import OrderedDict
from app.log import logger
from sqlalchemy import or_
from sqlalchemy.sql.expression import case, collate

from app.database.schema import HostTable, GroupsTable


class Groups(object):
    def __init__(self, database, crypto):
        self._db = database
        self._crypto = crypto

    def get(self, name):
        group = self._db.getObjectByName(GroupsTable, name)
        if group is None:
            raise LookupError(u"Host not found")

        return group

    def create(self, name, default_password=None, default_user_name=None):
        if default_password:
            default_password = self._crypto.encrypt(default_password)

        group = GroupsTable(name=name, default_password=default_password, default_user_name=default_user_name)
        self._db.createObject(group)
        return group

    def getOrCreate(self, groupName):
        try:
            group = self.get(groupName)
        except LookupError:
            group = self.create(groupName)

        return group

    def updateValues(self, groupName, values):
        passwd = values.get('default_password')
        if passwd:
            values["default_password"] = self._crypto.encrypt(passwd)
        group = self.get(groupName)
        self._db.updateObject(group, values)

    def getFormattedValues(self, groupName, attributes):
        group = self.get(groupName)
        values = dict()
        for attribute in attributes:
            value = getattr(group, attribute)
            if attribute == "default_password":
                value = self._crypto.decrypt(value)
            values[attribute] = value
        return values


class Hosts(object):
    def __init__(self, database, crypto):
        """
        :param database: Database instance app.database.Database
        :type database: app.database.Database
        :param crypto: crypto object used to password encryption
        :type crypto: app.crypto.CryptoKey
        """
        self._db = database
        self._crypto = crypto
        self.groups = Groups(self._db, self._crypto)

    def get(self, hostName):
        hostProxy = self._db.session.query(HostTable, GroupsTable).filter_by(
            name=unicode(hostName)).outerjoin(
            GroupsTable, HostTable.group == GroupsTable.id
        ).first()

        if hostProxy is None:
            raise LookupError(u"Host not found")
        return Host(hostProxy, self._crypto)

    def assignGroup(self, hostName, groupName):
        host = self.get(hostName)
        if groupName:
            group = self.groups.getOrCreate(groupName).id
        else:  # unassign
            group = None
        host.assignGroup(group)

    def getAllHostsNames(self):
        """
        :return: list with host names
        """
        hostsList = sum(self._db.session.query(HostTable.name), ())
        return sorted(hostsList)

    def getHostsListByHostNameAndGroup(self, hostFilter=None, groupFilter=None):
        result = self._db.session.query(
            HostTable.name).outerjoin(GroupsTable, HostTable.group == GroupsTable.id).order_by(
            collate(HostTable.name, 'NOCASE')
        ).filter(
            or_(GroupsTable.name.in_(groupFilter), HostTable.group.is_(None))  # always include hosts without groups
        )

        if hostFilter:
            result = result.filter(HostTable.name.like(u"%%{}%%".format(hostFilter)))

        return sum(result, ())

    def getGroupsList(self):
        """
        :return: list with group names
        """
        return sum(self._db.session.query(GroupsTable.name), ())

    def getGroupedHostNames(self, queryFilter=None):
        hostsList = self._db.session.query(HostTable.name, GroupsTable.name).outerjoin(
            GroupsTable, HostTable.group == GroupsTable.id).order_by(
            case([(HostTable.group == None, 1)], else_=0),  # nulls last
            collate(GroupsTable.name, 'NOCASE'),
            collate(HostTable.name, 'NOCASE')
        )

        if queryFilter:
            hostsList = hostsList.filter(HostTable.name.like("%%%s%%" % queryFilter))

        groupedHosts = OrderedDict()
        for host, group in hostsList:
            if group in groupedHosts.keys():
                groupedHosts[group].append(host)
            else:
                groupedHosts[group] = [host]
        return groupedHosts

    def updateValues(self, hostName, values):
        """
        :param hostName: host name
        :param values: Dictionary {attribute: value}
        """
        host = self.get(hostName)
        passwd = values.get('password')
        if passwd:
            values["password"] = self._crypto.encrypt(passwd)
        group = values.get('group')
        if group:
            values['group'] = self.groups.getOrCreate(group).id
        self._db.updateObject(host.hostData, values)

    def create(self, name, address, user=None, password=None, group=None):
        # todo: 1# group should be refactored to group name but this has implications :P
        # todo: 2# host is not returned because, when is returned something behaves
        #  in different way than when is taken from hosts
        if password:
            password = self._crypto.encrypt(password)

        if group:
            groupId = self.groups.getOrCreate(group).id
        else:
            groupId = None

        host = HostTable(name=name, address=address, user=user, password=password, group=groupId)
        self._db.createObject(host)

    def delete(self, hostName):
        host = self.get(hostName=hostName)
        self._db.deleteObject(host)

    def deleteGroup(self, groupName):
        group = self.groups.get(groupName)
        self._db.session.query(HostTable).filter(HostTable.group == group.id).update({HostTable.group: None})
        self._db.deleteObject(group)

    def getFormattedValues(self, hostName, attributes):
        host = self.get(hostName)
        values = dict()
        for attribute in attributes:
            value = getattr(host.hostData, attribute)
            if attribute == "password" and value:
                value = self._crypto.decrypt(value)
            elif attribute == "group":
                value = host.group

            values[attribute] = value
        return values


class Host(object):
    def __init__(self, hostProxy, crypto):
        self.ctx = hostProxy
        self._crypto = crypto
        self.hostData = self.ctx.HostTable
        self.groupsData = self.ctx.GroupsTable

    def __getattr__(self, item):
        return self.hostData.__dict__.get(item)

    def setValue(self, key, value):
        return self.ctx.__setattr__(key, value)

    @property
    def group(self):
        return self.groupsData.name

    def assignGroup(self, group):
        self.hostData.group = group

    @property
    def user(self):
        user = self.hostData.user
        if not user:
            user = self.groupsData.default_user_name
        return user

    @property
    def password(self):
        password = self.hostData.password
        if not password:
            password = self.groupsData.default_password
        if password:
            try:
                return self._crypto.decrypt(password)
            except ValueError as e:
                logger.error(u"Couldn't decrypt password. {}".format(e.message))
            except TypeError as e:
                logger.error(u"Couldn't decode base64 password. {}".format(e.message))
        return None
