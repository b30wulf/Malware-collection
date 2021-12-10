# -*- coding: utf-8 -*-
from PyQt4 import QtCore, QtGui

from app.gui import actions
from app.log import logger


class X11Embed(QtGui.QX11EmbedContainer):
    def __init__(self, parent=None):
        super(X11Embed, self).__init__(parent)
        self.setMouseTracking(True)
        self.setMinimumSize(200, 200)


class Position(object):
    LEFT_TOP_CORNER = 0
    LEFT_BOTTOM_CORNER = 1
    RIGHT_TOP_CORNER = 2
    RIGHT_BOTTOM_CORNER = 3
    LEFT = 4
    RIGHT = 5
    TOP = 6
    BOTTOM = 7

    angle = {
        LEFT_TOP_CORNER: 180,
        RIGHT_TOP_CORNER: 180,
        TOP: 180,
        LEFT_BOTTOM_CORNER: 0,
        RIGHT_BOTTOM_CORNER: 0,
        BOTTOM: 0,
        LEFT: 90,
        RIGHT: 270,
    }


class ControlButton(QtGui.QPushButton):
    offset = None
    w = 20
    h = 20
    pageTabParent = None

    def __init__(self, pageTabParent):
        super(ControlButton, self).__init__(pageTabParent)
        self.pageTabParent = pageTabParent
        # set initial position on right side 80 px from top
        self.setGeometry(self.pageTabParent.width() - self.w, 80, self.w, self.h)
        self.currentPosition = Position.RIGHT
        self.show()

        self.ico = QtGui.QIcon(":/ico/tab_menu.png")
        self.setIcon(self.ico)
        self.setIconSize(QtCore.QSize(self.w, self.h))
        self.setFocusPolicy(QtCore.Qt.NoFocus)

        self.pageTabParent.resized.connect(self.fixPosition)

    def mousePressEvent(self, event):
        self.offset = event.pos()
        super(ControlButton, self).mousePressEvent(event)

    def getMax(self):
        """ Max coordinates for x and y """
        geometry = self.geometry()
        parentGeometry = self.pageTabParent.geometry()
        maxX = parentGeometry.width() - geometry.width()
        maxY = parentGeometry.height() - geometry.height()
        return maxX, maxY

    @staticmethod
    def getNormalizedXY(x, y, maxX, maxY):
        """ Returns fixed position for given parameters. Eliminates to small and to large coordinates. """
        if x < 0:
            x = 0
        elif x > maxX:
            x = maxX

        if y < 0:
            y = 0
        elif y > maxY:
            y = maxY

        return x, y

    def mouseMoveEvent(self, event):
        """ overrides mouse move event to properly sets icon """
        position = self.mapToParent(event.pos() - self.offset)

        x = position.x()
        y = position.y()

        # set max and minimum X,Y area
        maxX, maxY = self.getMax()
        x, y = self.getNormalizedXY(x, y, maxX, maxY)

        if y != 0 and y != maxY:
            if x != 0 and x != maxX:
                return

        self.assignCurrentPosition(x, y, maxX, maxY)
        self.move(x, y)

    def assignCurrentPosition(self, x, y, maxX, maxY):
        """ assign current button position for given coordinates """
        # corners
        if x == 0 and y == 0:
            position = Position.LEFT_TOP_CORNER
        elif x == 0 and y == maxY:
            position = Position.LEFT_BOTTOM_CORNER
        elif x == maxX and y == 0:
            position = Position.RIGHT_TOP_CORNER
        elif x == maxX and y == maxY:
            position = Position.RIGHT_BOTTOM_CORNER
        # others positions
        elif x == 0 and y > 0:
            position = Position.LEFT
        elif x == maxX and y > 0:
            position = Position.RIGHT
        elif x > 0 and y == 0:
            position = Position.TOP
        elif x > 0 and y == maxY:
            position = Position.BOTTOM

        if self.currentPosition != position:
            self.currentPosition = position

    def fixPosition(self):
        """ Used to fix position when tab is resized """
        maxX, maxY = self.getMax()
        geometry = self.geometry()

        x, y = geometry.x(), geometry.y()
        if (0 > x > maxX) or (0 > y > maxY):
            return

        x, y = self.getNormalizedXY(x, y, maxX, maxY)

        if self.currentPosition in (Position.LEFT_TOP_CORNER,  Position.TOP, Position.RIGHT_TOP_CORNER):
            y = 0
        elif self.currentPosition in (Position.LEFT_BOTTOM_CORNER,  Position.BOTTOM, Position.RIGHT_BOTTOM_CORNER):
            y = maxY
        elif self.currentPosition == Position.RIGHT:
            x = maxX
        elif self.currentPosition == Position.LEFT:
            x = 0

        self.setGeometry(x, y, geometry.width(), geometry.height())

    def paintEvent(self, event):
        """ To rotate icon we must override paintEvent """
        painter = QtGui.QStylePainter(self)

        angle = Position.angle[self.currentPosition]
        if angle:
            painter.translate(self.width()/2, self.height()/2)
            painter.rotate(angle)
            painter.translate(-self.width()/2, -self.height()/2)

        options = QtGui.QStyleOptionButton()
        options.initFrom(self)
        options.icon = self.icon()
        options.iconSize = self.iconSize()
        options.features = QtGui.QStyleOptionButton.Flat
        painter.drawControl(QtGui.QStyle.CE_PushButton, options)


class PageTab(QtGui.QWidget):
    widgetClosed = QtCore.pyqtSignal()
    resized = QtCore.pyqtSignal()
    reconnectionNeeded = QtCore.pyqtSignal("QString")

    controlButton = None
    showControlButtonWhenDetached = True

    def __init__(self, parent=None):
        super(PageTab, self).__init__(parent)
        # used for check if process has been stoped
        self.lastState = None
        
        self.layout = QtGui.QVBoxLayout(self)
        self.layout.setContentsMargins(0, 0, 0, 0)

        # bellow each rdesktop instance is text area with stdout/stderr debug
        # if something goes wrong text is visible, but when rdesktop is running
        # current display area is covered by rdp.
        # If window is resized, there is a lot of text, and rdp size is smaller,
        # than display area, you can see the text ;) looks buggy but at this (any:P) time
        # i think that's not important :)

        self.textEdit = QtGui.QTextEdit(self)
        self.textEdit.setReadOnly(True)
        self.textEdit.setFrameShape(QtGui.QFrame.NoFrame)
        self.textEdit.setStyleSheet("background-color:transparent;")
        
        self.textEdit.setVerticalScrollBarPolicy(QtCore.Qt.ScrollBarAlwaysOff)
        self.textEdit.setHorizontalScrollBarPolicy(QtCore.Qt.ScrollBarAlwaysOff)
    
        self.layout.addWidget(self.textEdit)

        # to embed rdesktop, if we use QWidget, there is some problems with
        # shortcuts (for e.g. in xfwm4), with QX11EmbedContainer looks good 
        self.x11 = X11Embed(self)

    def showControlButton(self):
        if not self.controlButton and self.showControlButtonWhenDetached:
            self.controlButton = ControlButton(self)
            menu = QtGui.QMenu()
            menu.addAction(QtGui.QIcon(":/ico/cancel.svg"), "Close", self.close)
            menu.addAction(QtGui.QIcon(":/ico/refresh.svg"), "Reconnect", self.emitReconnect)
            self.controlButton.setMenu(menu)

    def closeEvent(self, event):
        self.widgetClosed.emit()
        event.accept()
        self.deleteLater()

    def resizeEvent(self, event):
        super(PageTab, self).resizeEvent(event)
        # emit signal only when tab is visible
        if self.isVisible():
            self.resized.emit()

    def setSizeAndGetCurrent(self):
        """ Sets size of QX11EmbedContainer, because QX11 is not in layout, but
            textEdit is. Returns size of textEdit area which will be used in remote client
        """
        self.x11.setFixedSize(self.textEdit.size())
        return self.textEdit.width(), self.textEdit.height()

    def slotRead(self):
        proc = self.sender() 
        txt = proc.readAllStandardOutput()
        logger.debug(txt)
        self.appendText(txt.data().rstrip('\n'))

    def appendText(self, text):
        self.textEdit.append(text)

    def slotStateChanged(self, state):
        # append text to the text area only when process has been stopped
        if state == QtCore.QProcess.NotRunning and self.lastState == QtCore.QProcess.Running:
            self.textEdit.append("<i>Process has been stopped..</i><br />")
        self.lastState = state
        
    def text(self):
        return self.windowTitle()

    def emitReconnect(self):
        self.reconnectionNeeded.emit(self.text())


class MyTabWidget(QtGui.QTabWidget):

    def __init__(self):
        super(MyTabWidget, self).__init__()
        self.setTabsClosable(True)
        self.tabCloseRequested.connect(self.slotCloseTab)
        self.setMovable(True)
        self.setTab()
        # used when choosing action from menu
        self.currentTabIdx = None

    def setTab(self):
        self.tab = self.tabBar()
        self.tab.setContextMenuPolicy(QtCore.Qt.CustomContextMenu)
        self.tab.customContextMenuRequested.connect(self.showContextMenu)

        self.popMenu = QtGui.QMenu(self)
        self.popMenu.addAction(QtGui.QIcon(":/ico/refresh.svg"), "Reconnect", self.reconnect)
        self.popMenu.addAction(QtGui.QIcon(":/ico/detach.svg"), "Detach tab", self.detach)
        screenChoseMenu = actions.generateScreenChoseMenu(self.popMenu, self.detachFrameless,
                                                          ':/ico/frameless.svg', "Connect frameless")
        self.popMenu.addMenu(screenChoseMenu)

    def showContextMenu(self, point):
        self.currentTabIdx = self.tab.tabAt(point)
        if not self.currentTab.objectName() == "settings":
            self.popMenu.exec_(self.tab.mapToGlobal(point))

    def mousePressEvent(self, event):
        if event.button() == QtCore.Qt.MiddleButton:
            tabIdx = self.tab.tabAt(event.pos())
            self.slotCloseTab(tabIdx)
        else:
            event.accept()

    def reconnect(self):
        self.currentTab.emitReconnect()

    def setDetached(self, frameless, widget=None, screenIndex=None):
        if not widget:
            widget = self.currentTab
        widget.setParent(None)

        if frameless:
            widget.setWindowFlags(QtCore.Qt.FramelessWindowHint)
            desktop = QtGui.QApplication.desktop()
            if screenIndex is None:
                screenIndex = desktop.screenNumber(self)
            widget.setGeometry(desktop.availableGeometry(screenIndex))
        widget.setWindowIcon(QtGui.QIcon(":/ico/myrdp.svg"))
        widget.show()

        if frameless:
            widget.reconnectionNeeded.emit(widget.windowTitle())

        widget.showControlButton()

    @property
    def currentTab(self):
        return self.widget(self.currentTabIdx)

    def detach(self, widget=None):
        self.setDetached(False, widget)

    def detachFrameless(self, widget=None, screenIndex=None):
        self.setDetached(True, widget, screenIndex)

    def getTabObjectName(self, tabName):
        return u"p_%s" % tabName

    def createTab(self, tabName):
        # used for create unique object name (because title is unique)
        tabObjectName = self.getTabObjectName(tabName)
        tabWidget = self.findChild(PageTab, tabObjectName)
        
        for topLevel in QtGui.QApplication.topLevelWidgets():
            if type(topLevel) == PageTab and topLevel.objectName() == tabObjectName:
                    return topLevel
        
        if tabWidget is None:
            newTab = PageTab(self)
            newTab.setObjectName(tabObjectName)
            tabIdx = self.addTab(newTab, tabName)
            newTab.setWindowTitle(tabName)
            self.setCurrentIndex(tabIdx)
            return newTab
        else:
            return tabWidget
        
    def activateTab(self, hostId):
        tabName = self.getTabObjectName(hostId)
        tabWidget = self.findChild(PageTab, tabName)
        
        if tabWidget is not None:
            tabIdx = self.indexOf(tabWidget)
            self.setCurrentIndex(tabIdx)
    
    def slotCloseTab(self, tabIdx):
        tabWidget = self.widget(tabIdx)
        self.removeTab(tabIdx)
        tabWidget.deleteLater()
