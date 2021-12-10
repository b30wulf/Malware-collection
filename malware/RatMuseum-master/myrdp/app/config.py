# -*- coding: utf-8 -*-
import logging
import os
import sys

from app.log import logger
from app.crypto import CryptoKey
from PyQt4.QtCore import QSettings
J = os.path.join


class Config(object):

    defaultFreerdpArgs = "+clipboard /cert-ignore +drives /drive:home,/home -themes +compression /gdi:sw +auto-reconnect"

    def __init__(self):
        if not os.path.isdir(self.configDirectory):
            os.makedirs(self.configDirectory)

    @property
    def settings(self):
        return QSettings("myrdp", "settings")

    def setValue(self, setting, value):
        return self.settings.setValue(setting, value)

    def getValue(self, setting, defaultValue=None):
        return self.settings.value(setting, defaultValue)

    def getStringValue(self, setting, defaultValue=None):
        return self.getValue(setting, defaultValue).toString()

    @property
    def mainDirectory(self):
        # pyinstaller sets sys.frozen attribute
        if getattr(sys, 'frozen', False):
            mainDirectory = os.path.dirname(sys.executable)
        else:
            mainDirectory = J(os.path.dirname(__file__), "..")
        return mainDirectory

    @property
    def configDirectory(self):
        return os.path.dirname(unicode(self.settings.fileName()))

    @property
    def databaseLocation(self):
        defaultLocation = J(self.configDirectory, "myrdp.sqlite")
        location = self.getStringValue('database_location', defaultLocation)
        if location == '':
            location = defaultLocation
        return location

    def setDatabaseLocation(self, location):
        self.setValue('database_location', location)

    def getConnectionString(self):
        connectionString = u"sqlite:///%s" % self.databaseLocation
        logging.debug(connectionString)
        return connectionString

    @property
    def freerdpArgs(self):
        args = self.getStringValue('freerdp_arguments', self.defaultFreerdpArgs)
        return args

    def setFreerdpArgs(self, freerdpArgs):
        self.setValue('freerdp_arguments', freerdpArgs)

    @property
    def freerdpExecutable(self):
        return self.getStringValue('freerdp_executable', 'xfreerdp')

    def setFreerdpExecutable(self, freerdpExecutable):
        self.setValue('freerdp_executable', freerdpExecutable)

    @property
    def logLevel(self):
        return unicode(self.getStringValue('logging_level', "error"))

    def setLogLevel(self, loggingLevel=None):
        if loggingLevel:
            self.setValue('logging_level', loggingLevel)
        logger.setLevel(getattr(logging, self.logLevel.upper()))

    @property
    def loggingLevels(self):
        return ["error", "debug"]

    def getRdpClient(self):
        data = {
            "executable": self.freerdpExecutable,
            "args": self.freerdpArgs
        }
        return "xfreerdp", data

    @property
    def privateKeyPath(self):
        return J(self.configDirectory, 'private.key')

    def getPrivateKey(self, passphrase=None):
        ck = CryptoKey()
        if os.path.exists(self.privateKeyPath):
            ck.load(self.privateKeyPath, passphrase)
        else:  # if private key doesn't exist, then save generated one
            ck.save(self.privateKeyPath, passphrase)
        return ck
