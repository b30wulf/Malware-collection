# -*- coding: utf-8 -*-
from PyQt4 import QtGui


def generateScreenChoseMenu(menu, slot, iconPath, actionName):
    """
    :param menu: QMenu parent
    :param slot: slot to connect with parameter screenIndex
    :param iconPath: path to icon
    :param actionName: action name to add
    :return: generated menu with screens to select
    """
    ico = QtGui.QIcon(iconPath)
    screenCount = QtGui.QApplication.desktop().screenCount()
    screensMenu = QtGui.QMenu(actionName, menu)
    screensMenu.setIcon(ico)
    for screen in range(screenCount):
        # when lambda was without fix parameter, each method connectFrameless was run with latest screen :/
        screensMenu.addAction("Screen %s" % screen, lambda fix=screen: slot(screenIndex=fix))

    return screensMenu
