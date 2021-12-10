# -*- coding: utf-8 -*-
from PyQt4 import QtGui

from PyQt4.QtCore import Qt, QCoreApplication
from PyQt4.QtGui import QAction, QMainWindow, QWidget, QMessageBox, \
    QMenu, QIcon, QVBoxLayout, QSystemTrayIcon, QDialog

from app.client import ClientFactory
from app.config import Config
from app.database import Database
from app.hosts import Hosts
from app.gui import actions
from app.gui.assigngroup import AssignGroupDialog
from app.gui.hostconfig import HostConfigDialog
from app.gui.groupconfig import GroupConfigDialog, DeleteGroupDialog
from app.gui.mainwindow_ui import Ui_MainWindow
from app.gui.mytabwidget import MyTabWidget
from app.gui.password import PasswordDialog
from app.gui.process import ProcessManager
from app.gui.settingspage import SettingsPage
from app.log import logger

unassignedGroupName = 'unassigned'


class DockWidgetTitleBar(QWidget):
    """
    Add this time widget with just some spacing from layout
    """
    def __init__(self):
        super(DockWidgetTitleBar, self).__init__()
        lay = QVBoxLayout()
        self.setLayout(lay)


class ConnectHostMenu(QMenu):
    def __init__(self, hosts, title="Connect", parent=None):
        self.hosts = hosts
        self.groupMenus = []

        super(ConnectHostMenu, self).__init__(title, parent)
        self.aboutToShow.connect(self.setHosts)

    def setHosts(self):
        self.clear()
        self.groupMenus = []  # keep created menus, after functions ends

        hosts = self.hosts.getGroupedHostNames()
        for group, hosts in hosts.items():
            if group is None:
                groupMenu = self
            else:
                groupMenu = QMenu(group)
                self.groupMenus.append(groupMenu)
                self.addMenu(groupMenu)
            for host in hosts:
                groupMenu.addAction(host)


class MainWindow(QMainWindow):
    groups = dict()
    typeQListWidgetHeader = 1000
    showHostsInGroups = False
    currentGroupName = None  # used to simple detect currently selected group to show menu

    def __init__(self):
        super(MainWindow, self).__init__()
        self.config = Config()
        self.db = Database(self.config.getConnectionString())

        cryptoKey = self.getCryptoKey()
        self.hosts = Hosts(self.db, cryptoKey)

        # menu used for each host
        self.hostMenu = QMenu()
        self.editAction = QAction(QIcon(':/ico/edit.svg'), "Edit", self.hostMenu)
        self.editAction.triggered.connect(self.editHost)
        self.hostMenu.addAction(self.editAction)

        # menu used for headers of groups
        self.groupsHeaderMenu = QMenu()
        self.editGroupAction = QAction(QIcon(':/ico/edit.svg'), "Edit group", self.groupsHeaderMenu)
        self.editGroupAction.triggered.connect(self.editGroup)
        self.deleteGroupAction = QAction(QIcon(':/ico/remove.svg'), "Delete group", self.groupsHeaderMenu)
        self.deleteGroupAction.triggered.connect(self.deleteGroup)
        self.groupsHeaderMenu.addAction(self.editGroupAction)
        self.groupsHeaderMenu.addAction(self.deleteGroupAction)

        self.duplicateAction = QAction(QIcon(':/ico/copy.svg'), "Duplicate", self.hostMenu)
        self.duplicateAction.triggered.connect(self.duplicateHost)
        self.hostMenu.addAction(self.duplicateAction)

        # todo: confirm for delete action
        self.deleteAction = QAction(QIcon(':/ico/remove.svg'), "Delete", self.hostMenu)
        self.deleteAction.triggered.connect(self.deleteHost)
        self.hostMenu.addAction(self.deleteAction)

        self.connectFramelessMenu = actions.generateScreenChoseMenu(self.hostMenu, self.connectFrameless,
                                                                    ':/ico/frameless.svg', "Connect frameless")
        self.hostMenu.addMenu(self.connectFramelessMenu)

        self.assignGroupAction = QAction("Assign group", self.hostMenu)
        self.assignGroupAction.triggered.connect(self.assignGroup)
        self.hostMenu.addAction(self.assignGroupAction)

        # setup main window
        self.ui = Ui_MainWindow()
        self.ui.setupUi(self)

        # when top level changed, we changing dock title bar
        self.dockWidgetTileBar = DockWidgetTitleBar()
        self.ui.hostsDock.setTitleBarWidget(self.dockWidgetTileBar)
        self.ui.hostsDock.topLevelChanged.connect(self.dockLevelChanged)

        # set global menu
        self.globalMenu = QMenu()
        self.globalMenu.addAction(QIcon(':/ico/add.svg'), 'Add host', self.addHost)

        # groups menu
        self.groupsMenu = QMenu("Groups")
        self.groupsMenu.aboutToShow.connect(self.setGroupsMenu)
        self.globalMenu.addMenu(self.groupsMenu)

        # disable menu indicator
        self.ui.menu.setStyleSheet("QPushButton::menu-indicator {image: none;}")
        self.positionMenu = QMenu("Dock position")
        self.positionMenu.addAction("Left", lambda: self.setDockPosition(Qt.LeftDockWidgetArea))
        self.positionMenu.addAction("Right", lambda: self.setDockPosition(Qt.RightDockWidgetArea))
        self.positionMenu.addAction("Float", self.setDockFloat)
        self.globalMenu.addMenu(self.positionMenu)
        self.globalMenu.addAction('Change tray icon visibility', self.changeTrayIconVisibility)
        self.globalMenu.addAction('Settings', self.showSettings)
        self.globalMenu.addAction('Quit', self.close)
        self.ui.menu.setMenu(self.globalMenu)

        # set events on hosts list
        self.ui.hostsList.itemDoubleClicked.connect(self.slotConnectHost)
        self.ui.hostsList.itemClicked.connect(self.slotShowHost)
        self.ui.hostsList.customContextMenuRequested.connect(self.slotShowHostContextMenu)

        # set tab widget
        self.tabWidget = MyTabWidget()
        self.setCentralWidget(self.tabWidget)
        self.tabWidget.setContextMenuPolicy(Qt.CustomContextMenu)
        self.tabWidget.customContextMenuRequested.connect(self.showCentralWidgetContextMenu)

        # set tray icon
        self.tray = QSystemTrayIcon(QIcon(":/ico/myrdp.svg"))
        self.tray.activated.connect(self.trayActivated)

        self.trayMenu = QMenu()
        self.trayMenu.addAction("Hide tray icon", self.changeTrayIconVisibility)
        self.connectHostMenuTray = ConnectHostMenu(self.hosts)
        self.connectHostMenuTray.triggered.connect(self.connectHostFromTrayMenu)
        self.trayMenu.addMenu(self.connectHostMenuTray)
        self.trayMenu.addAction("Quit", self.close)

        self.tray.setContextMenu(self.trayMenu)
        self.restoreSettings()
        # host list
        self.ui.filter.textChanged.connect(self.setHostList)
        self.setHostList()

    def getCryptoKey(self, passphrase=None):
        try:
            return self.config.getPrivateKey(passphrase)
        except ValueError:
            passwordDialog = PasswordDialog()
            retCode = passwordDialog.exec_()
            if retCode == QtGui.QDialog.Accepted:
                return self.getCryptoKey(passwordDialog.getPassword())
            else:
                raise SystemError("Password required")

    def showSettings(self):
        settingsWidget = self.findChild(QWidget, "settings")
        if settingsWidget is None:
            self.settingsWidget = SettingsPage()
            self.settingsWidget.setObjectName("settings")
            self.tabWidget.insertTab(0, self.settingsWidget, QIcon(":/ico/settings.svg"), 'Settings')

        index = self.tabWidget.indexOf(self.settingsWidget)
        self.tabWidget.setCurrentIndex(index)

    def connectHostFromMenu(self, action):
        self.connectHost(unicode(action.text()))

    def connectHostFromTrayMenu(self, action):
        tabPage = self.connectHost(unicode(action.text()))
        if not self.isVisible():
            self.tabWidget.setDetached(True, tabPage)

    def trayActivated(self, reason):
        if reason != QSystemTrayIcon.Trigger:
            return
        if self.isVisible():
            self.hide()
        else:
            self.show()
            self.activateWindow()

    def changeTrayIconVisibility(self):
        if self.tray.isVisible():
            self.tray.hide()
            if not self.isVisible():
                self.show()
        else:
            self.tray.show()

    def refreshGroups(self):
        groupList = self.hosts.getGroupsList()
        for group in groupList:
            if group not in self.groups:
                # add new groups as visible
                self.groups[group] = True

        # remove not existing groups
        keysToDelete = set(self.groups.keys()) - set(groupList)
        for key in keysToDelete:
            self.groups.pop(key)

    def assignGroup(self):
        groups = self.hosts.getGroupsList()
        assignGroupDialog = AssignGroupDialog(groups)
        groupToAssign = assignGroupDialog.assign()
        if groupToAssign is not False:  # None could be used to unassign the group
            groupToAssign = None if groupToAssign.isEmpty() else unicode(groupToAssign)
            for hostName in self.getSelectedHosts():
                self.hosts.assignGroup(hostName, groupToAssign)
            self.db.tryCommit()
            self.setHostList()

    def setGroupsMenu(self):
        self.groupsMenu.clear()
        addGroupAction = self.groupsMenu.addAction('Add group')
        addGroupAction.triggered.connect(self.addGroup)

        deleteGroupAction = self.groupsMenu.addAction('Delete group')
        deleteGroupAction.triggered.connect(self.showDeleteGroupDialog)

        showHostsInGroupsAction = self.groupsMenu.addAction('Show host list in groups')
        showHostsInGroupsAction.triggered.connect(self.changeHostListView)
        showHostsInGroupsAction.setCheckable(True)
        showHostsInGroupsAction.setChecked(self.showHostsInGroups)

        self.groupsMenu.addSeparator()
        for group, checked in self.groups.items():
            action = QAction(group, self.groupsMenu)
            action.setCheckable(True)
            action.setChecked(checked)
            action.triggered.connect(self.groupsVisibilityChanged)
            self.groupsMenu.addAction(action)

    def addGroup(self):
        groupConfigDialog = GroupConfigDialog(self.hosts.groups)
        resp = groupConfigDialog.add()
        self._processHostSubmit(resp)

    def groupsVisibilityChanged(self, checked):
        currentGroup = unicode(self.sender().text())
        self.groups[currentGroup] = checked
        self.setHostList()

    def setDockPosition(self, dockWidgetArea):
        if self.ui.hostsDock.isFloating():
            self.ui.hostsDock.setFloating(False)
        self.addDockWidget(dockWidgetArea, self.ui.hostsDock)

    def setDockFloat(self):
        if self.ui.hostsDock.isFloating():
            return
        # default title bar must be set before is float because sometimes window make strange crash
        self.ui.hostsDock.setTitleBarWidget(None)
        self.ui.hostsDock.setFloating(True)

    def dockLevelChanged(self, isFloating):
        if isFloating:
            # changing title bar widget if is not none, probably true will be only once on start with saved float state
            if self.ui.hostsDock.titleBarWidget():
                self.ui.hostsDock.setTitleBarWidget(None)
        else:
            self.ui.hostsDock.setTitleBarWidget(self.dockWidgetTileBar)

    def showFramelessWidget(self):
        self.t.show()
        self.t.setGeometry(self.frameGeometry())

    def getCurrentHostListItemName(self):
        return self.ui.hostsList.currentItem().text()

    def getSelectedHosts(self):
        return [host.text() for host in self.ui.hostsList.selectedItems()]

    def findHostItemByName(self, name):
        result = self.ui.hostsList.findItems(name, Qt.MatchExactly)
        resultLen = len(result)
        if resultLen != 1:  # should be only one host
            logger.error("Host not found. Got %d results" % resultLen)
        return result[0]

    def showCentralWidgetContextMenu(self, pos):
        menu = QMenu()
        title = self.ui.hostsDock.windowTitle()

        hostsDockAction = menu.addAction(title)
        hostsDockAction.setCheckable(True)
        hostsDockAction.setChecked(self.ui.hostsDock.isVisible())
        hostsDockAction.triggered.connect(self.changeHostsDockWidgetVisibility)

        hostsDockAction = menu.addAction("Tray icon")
        hostsDockAction.setCheckable(True)
        hostsDockAction.setChecked(self.tray.isVisible())
        hostsDockAction.triggered.connect(self.changeTrayIconVisibility)

        connectHostMenuTray = ConnectHostMenu(self.hosts, "Connect")
        connectHostMenuTray.triggered.connect(self.connectHostFromMenu)
        menu.addMenu(connectHostMenuTray)

        menu.exec_(self.tabWidget.mapToGlobal(pos))

    def changeHostListView(self, checked):
        self.showHostsInGroups = checked
        self.setHostList()

    def changeHostsDockWidgetVisibility(self):
        isVisible = self.ui.hostsDock.isVisible()
        self.ui.hostsDock.setVisible(not isVisible)

    def isHostListHeader(self, item):
        if not item or item.type() == self.typeQListWidgetHeader:
            return True
        return False

    def slotShowHostContextMenu(self, pos):
        def changeMenusVisibility(isEnabled):
            self.connectFramelessMenu.setEnabled(isEnabled)
            self.editAction.setEnabled(isEnabled)
            self.duplicateAction.setEnabled(isEnabled)

        # ignore context menu for group headers
        item = self.ui.hostsList.itemAt(pos)

        if self.isHostListHeader(item):
            item = self.ui.hostsList.itemAt(pos)
            widgetItem = self.ui.hostsList.itemWidget(item)
            if widgetItem:
                self.currentGroupName = widgetItem.text()  # yea I'm so dirty
                if self.currentGroupName != unassignedGroupName:
                    self.groupsHeaderMenu.exec_(self.ui.hostsList.mapToGlobal(pos))
            return

        if len(self.ui.hostsList.selectedItems()) == 1:  # single menu
            changeMenusVisibility(True)
        else:
            changeMenusVisibility(False)

        self.hostMenu.exec_(self.ui.hostsList.mapToGlobal(pos))

    def _processHostSubmit(self, resp):
        if resp["code"]:
            self.setHostList()
        hostName = resp.get("name")
        if hostName:
            hostItem = self.findHostItemByName(hostName)
            self.slotConnectHost(hostItem)

    def addHost(self):
        hostDialog = HostConfigDialog(self.hosts)
        self._processHostSubmit(hostDialog.add())

    def editHost(self):
        hostDialog = HostConfigDialog(self.hosts)
        resp = hostDialog.edit(self.getCurrentHostListItemName())
        self._processHostSubmit(resp)

    def editGroup(self):
        groupConfigDialog = GroupConfigDialog(self.hosts.groups)
        resp = groupConfigDialog.edit(self.currentGroupName)
        self._processHostSubmit(resp)

    def deleteGroup(self):
        retCode = self.showOkCancelMessageBox("Do you want to remove selected group? All assigned hosts "
                                              "to this group will be unassigned.",
                                              "Confirmation")
        if retCode == QMessageBox.Cancel:
            return

        self.hosts.deleteGroup(self.currentGroupName)
        self.setHostList()

    def showDeleteGroupDialog(self):
        deleteGroupDialog = DeleteGroupDialog(self.hosts)
        deleteGroupDialog.deleteGroup()
        self.setHostList()

    def duplicateHost(self):
        hostDialog = HostConfigDialog(self.hosts)
        resp = hostDialog.duplicate(self.getCurrentHostListItemName())
        self._processHostSubmit(resp)

    def deleteHost(self):
        retCode = self.showOkCancelMessageBox("Do you want to remove selected hosts?",
                                              "Confirmation")
        if retCode == QMessageBox.Cancel:
            return

        for host in self.getSelectedHosts():
            self.hosts.delete(host)
        self.setHostList()

    def connectFrameless(self, screenIndex=None):
        self.connectHost(self.getCurrentHostListItemName(), frameless=True, screenIndex=screenIndex)

    # Fix to release keyboard from QX11EmbedContainer, when we leave widget through wm border
    def leaveEvent(self, event):
        keyG = QWidget.keyboardGrabber()
        if keyG is not None:
            keyG.releaseKeyboard()
        event.accept()  # needed?

    def setHostList(self):
        """ set hosts list in list view """
        self.ui.hostsList.clear()
        self.refreshGroups()
        hostFilter = self.ui.filter.text()
        if self.showHostsInGroups:
            self.showHostListInGroups(hostFilter)
        else:
            self.showHostList(hostFilter)

    def showHostList(self, hostFilter):
        groupFilter = [group for group, visibility in self.groups.items() if visibility]
        hosts = self.hosts.getHostsListByHostNameAndGroup(hostFilter, groupFilter)
        self.ui.hostsList.addItems(hosts)

    def showHostListInGroups(self, hostFilter):
        hosts = self.hosts.getGroupedHostNames(hostFilter)
        for group, hostsList in hosts.items():
            if self.groups.get(group, True):
                if group is None:
                    group = unassignedGroupName
                groupHeader = QtGui.QListWidgetItem(type=self.typeQListWidgetHeader)
                groupLabel = QtGui.QLabel(unicode(group))
                groupLabel.setProperty('class', 'group-title')
                self.ui.hostsList.addItem(groupHeader)
                self.ui.hostsList.setItemWidget(groupHeader, groupLabel)
                self.ui.hostsList.addItems(hostsList)

    def slotShowHost(self, item):
        # on one click we activating tab and showing options
        self.tabWidget.activateTab(item)

    def slotConnectHost(self, item):
        if self.isHostListHeader(item):
            return
        self.connectHost(unicode(item.text()))

    def connectHost(self, hostId, frameless=False, screenIndex=None):
        hostId = unicode(hostId)  # sometimes hostId comes as QString
        tabPage = self.tabWidget.createTab(hostId)
        tabPage.reconnectionNeeded.connect(self.connectHost)

        if frameless:
            self.tabWidget.detachFrameless(tabPage, screenIndex)

        try:
            execCmd, opts = self.getCmd(tabPage, hostId)
        except LookupError:
            logger.error(u"Host {} not found.".format(hostId))
            return

        ProcessManager.start(hostId, tabPage, execCmd, opts)
        return tabPage

    def getCmd(self, tabPage, hostName):
        host = self.hosts.get(hostName)

        # set tabPage widget
        width, height = tabPage.setSizeAndGetCurrent()
        # 1et widget winId to embed rdesktop
        winId = tabPage.x11.winId()

        # set remote desktop client, at this time works only with freerdp
        remoteClientType, remoteClientOptions = self.config.getRdpClient()
        remoteClient = ClientFactory(remoteClientType, **remoteClientOptions)
        remoteClient.setWindowParameters(winId, width, height)
        remoteClient.setUserAndPassword(host.user, host.password)
        remoteClient.setAddress(host.address)
        return remoteClient.getComposedCommand()

    def saveSettings(self):
        self.config.setValue("geometry", self.saveGeometry())
        self.config.setValue("windowState", self.saveState())
        self.config.setValue('trayIconVisibility', self.tray.isVisible())
        self.config.setValue('mainWindowVisibility', self.isVisible())
        self.config.setValue('groups', self.groups)
        self.config.setValue('showHostsInGroups', self.showHostsInGroups)

    def restoreSettings(self):
        try:
            self.restoreGeometry(self.config.getValue("geometry").toByteArray())
            self.restoreState(self.config.getValue("windowState").toByteArray())
        except Exception:
            logger.debug("No settings to restore")

        # restore tray icon state
        trayIconVisibility = self.config.getValue('trayIconVisibility', "true").toBool()
        self.tray.setVisible(trayIconVisibility)

        self.showHostsInGroups = self.config.getValue('showHostsInGroups', 'false').toBool()

        if self.tray.isVisible():
            mainWindowVisibility = self.config.getValue('mainWindowVisibility', "true").toBool()
            self.setVisible(mainWindowVisibility)
        else:  # it tray icon is not visible, always show main window
            self.show()

        self.groups = {unicode(k): v for k, v in self.config.getValue('groups', {}).toPyObject().items()}

    def closeEvent(self, event):
        if not ProcessManager.hasActiveProcess:
            self.saveSettings()
            QCoreApplication.exit()
            return

        ret = self.showOkCancelMessageBox("Are you sure do you want to quit?",
                                          "Exit confirmation")
        if ret == QMessageBox.Cancel:
            event.ignore()
            return

        self.saveSettings()
        ProcessManager.killemall()
        event.accept()
        QCoreApplication.exit()

    def showOkCancelMessageBox(self, messageBoxText, windowTitle):
        msgBox = QMessageBox(self, text=messageBoxText)
        msgBox.setWindowTitle(windowTitle)
        msgBox.setStandardButtons(QMessageBox.Ok | QMessageBox.Cancel)
        msgBox.setIcon(QMessageBox.Question)
        return msgBox.exec_()