# -*- coding: utf-8 -*-
from PyQt4 import QtCore
from app.log import logger


class ProcessManager(object):
    processes = dict()

    def add(self, process):
        processName = process.name
        if processName in self.processes.keys():
            self.processes[processName].kill()
        self.processes[processName] = process

    def delete(self, process):
        try:
            processName = process.name
            self.processes.pop(processName)
            logger.debug(u"Removed process %s" % processName)
        except KeyError:
            logger.warning(u"Trying to remove not existing process..")

    def killemall(self):
        for process in self.processes.values():
            try:
                process.tabPage.close()
            except Exception as e:
                logger.warning(u"Exception when trying to kill process..\n%s" % e)

    @property
    def hasActiveProcess(self):
        if len(self.processes) == 0:
            return False
        return True

    def start(self, name, tabPage, *args, **kwargs):
        process = TabPageProcess(name, tabPage)
        self.add(process)
        process.tabPage.appendText(u"Connecting to %s.." % name)
        process.start(*args, **kwargs)
        return process

ProcessManager = ProcessManager()


class TabPageProcess(QtCore.QProcess):
    def __init__(self, name, tabPage, *args, **kwargs):
        super(TabPageProcess, self).__init__(*args, **kwargs)
        self.name = unicode(name)
        self.tabPage = tabPage

        self.setProcessChannelMode(QtCore.QProcess.MergedChannels)

        self.stateChanged.connect(tabPage.slotStateChanged)
        self.readyRead.connect(tabPage.slotRead)
        self.finished.connect(self.processStopped)

        tabPage.destroyed.connect(self.kill)
        tabPage.widgetClosed.connect(self.kill)

    def processStopped(self):
        ProcessManager.delete(self)
