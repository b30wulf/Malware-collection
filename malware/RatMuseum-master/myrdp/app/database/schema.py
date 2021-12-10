# -*- coding: utf-8 -*-
from sqlalchemy import Column, Integer, String
from sqlalchemy.schema import ForeignKey
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()


class HostTable(Base):
    __tablename__ = 'hosts'

    id = Column(Integer, primary_key=True)
    name = Column(String, unique=True, nullable=False)
    address = Column(String, nullable=False)
    user = Column(String)
    password = Column(String)
    group = Column(Integer, ForeignKey('groups.id'), nullable=True)

    def __repr__(self):
        return u"<name='%s', address='%s', user='%s', password='%s'>" \
               % (self.name, self.address, self.user, self.password)


class GroupsTable(Base):
    __tablename__ = 'groups'

    id = Column(Integer, primary_key=True)
    name = Column(String, nullable=False, unique=True)
    default_user_name = Column(String)
    default_password = Column(String)

    def __repr__(self):
        return u"<id='%s', name='%s', default_user_name='%s', default_password='%s'>" \
               % (self.id, self.name, self.default_user_name, self.default_password)