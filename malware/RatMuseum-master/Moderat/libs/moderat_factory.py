import ast

from twisted.internet.protocol import ClientFactory
from twisted.protocols.basic import LineReceiver


class SocketModeratorProtocol(LineReceiver):

    delimiter = '[ENDOFMESSAGE]'
    MAX_LENGTH = 1024*1024*10 # 10MB

    def __init__(self):

        pass

    # New Connection Made
    def connectionMade(self):
        self.factory.clientReady(self)

    def connectionLost(self, reason):
        self.factory.clientConnectionFailed(self, reason)

    def lineReceived(self, line):
        self.factory.got_msg(line)

    # Send Message To Server
    def send_message_to_server(self, payload):
        # Send Data Function
        self.transport.write(payload)
        #self.transport.doWrite()


class SocketModeratorFactory(ClientFactory):
    """ Created with callbacks for connection and receiving.
        send_msg can be used to send messages when connected.
    """
    protocol = SocketModeratorProtocol
    __buffer__ = ''

    def __init__(
            self,
            connect_success_callback,
            connect_fail_callback,
            recv_callback):
        self.connect_success_callback = connect_success_callback
        self.connect_fail_callback = connect_fail_callback
        self.recv_callback = recv_callback
        self.moderator = None

        self.__buffer__ = ''

    def clientConnectionFailed(self, connector, reason):
        self.connect_fail_callback(reason)

    def clientReady(self, moderator):
        self.moderator = moderator
        self.connect_success_callback()

    def got_msg(self, data):
        self.recv_callback(ast.literal_eval(data))

    def send_msg(self, message, mode, _to='', session_id='', module_id='', end='[ENDOFMESSAGE]'):
        if self.moderator:
            payload = str({
                'payload': message,
                'mode': mode,
                'from': 'moderator',
                'session_id': session_id,
                'to': _to,
                'module_id': module_id,
            })+end
            self.moderator.send_message_to_server(payload)