# -*- coding: utf-8 -*-
from app.log import logger


class Client(object):

    def __init__(self, executable, args):
        """
        :param executable: executable command/path
        :param args: raw options for given client
        :return:
        """
        self.executable = executable
        if args:
            self.args = args.split(" ")
        else:
            self.args = None
        self.settings = {}  # dictionary to store client settings

    def setWindowParameters(self, windowId, width, height):
        raise NotImplementedError

    def setUserAndPassword(self, user=None, password=None):
        if user:
            self.settings['u'] = user
        if password:
            self.settings['p'] = password

    def setAddress(self, address):
        raise NotImplementedError

    def getComposedCommand(self):
        raise NotImplementedError


class RdesktopClient(Client):
    host = None  # in rdesktop host is given without argument

    """ todo: at this time only freerdp implemented """
    def setWindowParameters(self, windowId, width, height):
        self.settings.update({"X": str(int(windowId)), "g": "%sx%s" % (width, height)})

    def setAddress(self, address):
        self.host = address

    def getComposedCommand(self):
        argsList = []
        for key, value in self.settings.items():
            argsList.append("-%s" % key)
            argsList.append(value)
        if self.args:
            argsList.extend(self.args)
        argsList.append(self.host)
        logger.debug("Running command:\n%s %s" % (self.executable, " ".join(argsList)))
        return self.executable, argsList


class FreerdpClient(Client):

    def setWindowParameters(self, windowId, width, height):
        self.settings.update({"parent-window": int(windowId), "w": width, "h": height})

    def setAddress(self, address):
        self.settings['v'] = address

    def getComposedCommand(self):
        """ Compose command with set earlier params
        :return: execCmd and optsList
        """
        argsList = ["/%s:%s" % (k, v) for k, v in self.settings.items()]
        argsList.extend(self.args)
        logger.debug(u"Running command:\n%s %s" % (self.executable, " ".join(
            [unicode(arg) for arg in argsList if not unicode(arg).startswith("/p:")]
        )))
        return self.executable, argsList


def ClientFactory(clientType, *args, **kwargs):
    """ Creates proper remote desktop client for given parameters """
    clientTypeToClass = {
        "xfreerdp": FreerdpClient,
        "rdesktop": RdesktopClient
    }
    return clientTypeToClass[clientType](*args, **kwargs)


