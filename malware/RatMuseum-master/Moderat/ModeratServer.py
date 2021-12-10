from twisted.internet import reactor
from twisted.internet.error import CannotListenError
from Server.ModeratServer import *
import os

CLIENTS_PORT = 443

try:
    from twisted.python import log
    import sys
    # Default Twisted Logging
    #log.startLogging(sys.stdout)
    factory = ModeratServerFactory()
    reactor.listenTCP(CLIENTS_PORT, factory)
    #reactor.listenTCP(MODERATORS_PORT, ModeratServerFactory())
    reactor.run()
except CannotListenError:
    print '[*SERVER] PORT ALREADY USED'
    os._exit(1)
