# -*- coding: utf-8 -*-
import os.path as path

from alembic import command, config

from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker

from app.config import Config
from app.database.schema import Base


class AlembicConfig(object):
    def __init__(self, databaseUrl):
        self.ctx = config.Config()
        self.ctx.set_main_option("script_location",
                                 path.join(path.dirname(path.realpath(__file__)), 'updates'))
        self.ctx.set_main_option("sqlalchemy.url", databaseUrl)


class Database(object):

    def __init__(self, engineString=None, echo=False):
        """
        :param engineString: at this time only sqlite, e.g: sqlite:////some/location.sqlite,
        if None, connection string from config will be used
        :param echo: echo SQL
        """
        if not engineString:
            engineString = Config().getConnectionString()
        self.engine = create_engine(engineString, echo=echo)
        Session = sessionmaker(bind=self.engine)
        self.alembicConfig = AlembicConfig(engineString).ctx
        self.session = Session()
        self.metadata = Base.metadata
        self.metadata.bind = self.engine  # bind or not to bind ?

    def create(self):
        # stamp only when creating database
        if len(self.engine.table_names()) == 0:
            self.metadata.create_all()
            command.stamp(self.alembicConfig, "head")

    def drop(self):
        self.metadata.drop_all()

    def update(self):
        command.upgrade(self.alembicConfig, "head")

    def recreate(self):
        self.drop()
        self.create()

    def getObjectByName(self, schemaType, objectName):
        """
        :param schemaType:
        :param objectName:
        :return:
        """
        obj = self.session.query(schemaType).filter_by(name=unicode(objectName)).first()
        return obj

    def tryCommit(self):
        try:
            self.session.commit()
        except Exception as e:
            self.session.rollback()
            raise e

    def createObject(self, schemaObject):
        self.session.add(schemaObject)
        self.tryCommit()

    def deleteObject(self, schemaObject):
        self.session.delete(schemaObject)
        self.tryCommit()

    def updateObject(self, schemaObject, values):
        for attr, value in values.items():
            setattr(schemaObject, attr, value)
        self.tryCommit()