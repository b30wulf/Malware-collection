from PyQt4.QtGui import *
from PyQt4.QtCore import *


class moderatRightClickMenu:
    def __init__(self, moderat):
        self.moderat = moderat

        self.moderat.clientsTable.setContextMenuPolicy(Qt.CustomContextMenu)
        self.moderat.connect(self.moderat.clientsTable, SIGNAL('customContextMenuRequested(const QPoint&)'),
                             self.online_clients_menu)

        self.moderat.offlineClientsTable.setContextMenuPolicy(Qt.CustomContextMenu)
        self.moderat.connect(self.moderat.offlineClientsTable, SIGNAL('customContextMenuRequested(const QPoint&)'),
                             self.offline_clients_menu)

        self.moderat.moderatorsTable.setContextMenuPolicy(Qt.CustomContextMenu)
        self.moderat.connect(self.moderat.moderatorsTable, SIGNAL('customContextMenuRequested(const QPoint&)'),
                             self.moderators_menu)

        self.moderat.directClientsTable.setContextMenuPolicy(Qt.CustomContextMenu)
        self.moderat.connect(self.moderat.directClientsTable, SIGNAL('customContextMenuRequested(const QPoint&)'),
                             self.direct_clients_menu)

    def online_clients_menu(self, point):
        self.moderat.emenu = QMenu(self.moderat)

        if self.moderat.clientsTable.currentRow() >= 0:
            self.moderat.emenu.addAction(self.moderat.MString('MVIEWER_TITLE'),
                                         lambda: self.moderat.execute_module(module='MVIEWER'))
            self.moderat.emenu.addAction(self.moderat.MString('NOTE_GET'),
                                         lambda: self.moderat.execute_module(module='MNOTE'))
            self.moderat.emenu.addAction(self.moderat.MString('LOG_SETTINGS_TITLE'),
                                         self.moderat.set_logs_settings)
            self.moderat.emenu.addSeparator()
            self.moderat.emenu.addAction(self.moderat.MString('SET_ALIAS'),
                                         self.moderat.set_alias)
            self.moderat.emenu.addAction(self.moderat.MString('RELOAD_CLIENT'),
                                         self.moderat.update_source)
            self.moderat.emenu.addSeparator()
            self.moderat.emenu.addAction(self.moderat.MString('MSHELL_TITLE'),
                                         lambda: self.moderat.execute_module(module='MSHELL'))
            self.moderat.emenu.addAction(self.moderat.MString('MEXPLORER_TITLE'),
                                         lambda: self.moderat.execute_module(module='MEXPLORER'))
            self.moderat.emenu.addAction(self.moderat.MString('MSCRIPTING_TITLE'),
                                         lambda: self.moderat.execute_module(module='MSCRIPTING'))
            self.moderat.emenu.addAction(self.moderat.MString('MDESKTOP_TITLE'),
                                         lambda: self.moderat.execute_module(module='MDESKTOP'))
            self.moderat.emenu.addAction(self.moderat.MString('MWEBCAM_TITLE'),
                                         lambda: self.moderat.execute_module(module='MWEBCAM'))
            self.moderat.emenu.addSeparator()
            self.moderat.emenu.addAction(self.moderat.MString('USB_SPREADING_ON_OFF'), self.moderat.usb_spreading)
            self.moderat.emenu.addAction(self.moderat.MString('P2P_START'), self.moderat.start_p2p)
            self.moderat.emenu.addSeparator()
            self.moderat.filter_menu = QMenu(self.moderat.MString('FILTER_BY'), self.moderat)
            self.moderat.filter_menu.addAction(self.moderat.MString('FILTER_MODERATOR'), self.moderat.filter_by_moderator)
            self.moderat.filter_menu.addAction(self.moderat.MString('FILTER_IP_ADDRESS'),
                                               self.moderat.filter_by_ip_address)
            self.moderat.filter_menu.addAction(self.moderat.MString('FILTER_ALIAS'), self.moderat.filter_by_alias)
            self.moderat.emenu.addMenu(self.moderat.filter_menu)

            if self.moderat.privs == 1:
                self.moderat.emenu.addSeparator()
                self.moderat.emenu.addAction(QIcon(':/icons/assets/set_moderator.png'), self.moderat.MString('SET_MODERATOR_TITLE'),
                                             self.moderat.set_moderator)

        self.moderat.emenu.exec_(self.moderat.clientsTable.mapToGlobal(point))

    def offline_clients_menu(self, point):
        self.moderat.emenu = QMenu(self.moderat)
        if self.moderat.offlineClientsTable.currentRow() >= 0:
            self.moderat.emenu.addAction(QIcon(':/icons/assets/log_viewer.png'), self.moderat.MString('MVIEWER_TITLE'),
                                         lambda: self.moderat.execute_module(module='MVIEWER'))
            self.moderat.emenu.addAction(QIcon(':/icons/assets/set_alias.png'), self.moderat.MString('SET_ALIAS'),
                                         self.moderat.set_alias)
            self.moderat.emenu.addAction(QIcon(':/icons/assets/note.png'), self.moderat.MString('MNOTE_TITLE'),
                                         lambda: self.moderat.execute_module(module='MNOTE'))
            self.moderat.emenu.addAction(QIcon(':/icons/assets/trash.png'), self.moderat.MString('REMOVE_CLIENT'),
                                         self.moderat.remove_client)
            self.moderat.emenu.addSeparator()
            self.moderat.filter_menu = QMenu(self.moderat.MString('FILTER_BY'))
            self.moderat.filter_menu.setStyleSheet('''background-color: #2c3e50;color: #c9f5f7;''')
            self.moderat.filter_menu.setIcon(QIcon(':/icons/assets/filter.png'))
            self.moderat.filter_menu.addAction(QIcon(':/icons/assets/filter.png'),
                                               self.moderat.MString('FILTER_MODERATOR'), self.moderat.filter_by_moderator)
            self.moderat.filter_menu.addAction(QIcon(':/icons/assets/filter.png'), self.moderat.MString('FILTER_IP_ADDRESS'),
                                               self.moderat.filter_by_ip_address)
            self.moderat.filter_menu.addAction(QIcon(':/icons/assets/filter.png'),
                                               self.moderat.MString('FILTER_ALIAS'), self.moderat.filter_by_alias)
            self.moderat.emenu.addMenu(self.moderat.filter_menu)

            if self.moderat.privs == 1:
                self.moderat.emenu.addSeparator()
                self.moderat.emenu.addAction(QIcon(':/icons/assets/set_moderator.png'), self.moderat.MString('SET_MODERATOR_TITLE'),
                                             self.moderat.set_moderator)

        self.moderat.emenu.exec_(self.moderat.offlineClientsTable.mapToGlobal(point))

    def moderators_menu(self, point):
        self.moderat.emenu = QMenu(self.moderat)
        if self.moderat.moderatorsTable.currentRow() >= 0:
            self.moderat.emenu.addSeparator()
            self.moderat.emenu.addAction(QIcon(':/icons/assets/add_moderator.png'), self.moderat.MString('MODERATOR_ADD_MDOERATOR'),
                                         self.moderat.create_moderator)
            self.moderat.emenu.addAction(QIcon(':/icons/assets/password.png'), self.moderat.MString('MODERATOR_CHANGE_PASSWORD'),
                                         self.moderat.change_moderator_password)
            self.moderat.emenu.addAction(QIcon(':/icons/assets/privileges.png'), self.moderat.MString('MODERATOR_CHANGE_GROUP'),
                                         self.moderat.change_moderator_privilege)
            self.moderat.emenu.addAction(QIcon(':/icons/assets/trash.png'), self.moderat.MString('MODERATOR_REMOVE'),
                                         self.moderat.remove_moderator)
        self.moderat.emenu.exec_(self.moderat.moderatorsTable.mapToGlobal(point))

    def direct_clients_menu(self, point):
        self.moderat.emenu = QMenu(self.moderat)
        if self.moderat.directClientsTable.currentRow() >= 0:
            self.moderat.emenu.addAction(QIcon(':/icons/assets/remote_shell.png'), self.moderat.MString('MSHELL_TITLE'),
                                         lambda: self.moderat.execute_module(module='shell'))
            self.moderat.emenu.addAction(QIcon(':/icons/assets/remote_explorer.png'), self.moderat.MString('MEXPLORER_TITLE'),
                                         lambda: self.moderat.execute_module(module='explorer'))
            self.moderat.emenu.addAction(QIcon(':/icons/assets/remote_scripting.png'), self.moderat.MString('MSCRIPTING_TITLE'),
                                         lambda: self.moderat.execute_module(module='scripting'))
            self.moderat.emenu.addAction(QIcon(':/icons/assets/desktop.png'), self.moderat.MString('MDESKTOP_TITLE'),
                                         lambda: self.moderat.execute_module(module='desktop'))
            self.moderat.emenu.addAction(QIcon(':/icons/assets/webcam.png'), self.moderat.MString('MWEBCAM_TITLE'),
                                         lambda: self.moderat.execute_module(module='webcam'))
        self.moderat.emenu.exec_(self.moderat.directClientsTable.mapToGlobal(point))