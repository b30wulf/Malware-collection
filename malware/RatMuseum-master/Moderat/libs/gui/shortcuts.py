from PyQt4.QtGui import *
from PyQt4.QtCore import *


class ModeratShortcuts:
    def __init__(self, moderat):
        self.moderat = moderat

        # Connect To Server
        self.moderat.connect(QShortcut(QKeySequence('Ctrl+C'), self.moderat), SIGNAL('activated()'),
                             self.moderat.on_connect_to_server)
        # Disconnect From Server
        self.moderat.connect(QShortcut(QKeySequence('Ctrl+D'), self.moderat), SIGNAL('activated()'),
                             lambda: self.moderat.on_moderator_connect_fail(reason='Manually Disconnected'))
        # View Logs
        self.moderat.connect(QShortcut(QKeySequence('Ctrl+L'), self.moderat), SIGNAL('activated()'),
                             lambda: self.moderat.execute_module(module='logviewer'))
        # Set Alias
        self.moderat.connect(QShortcut(QKeySequence(Qt.Key_F2), self.moderat), SIGNAL('activated()'),
                             self.moderat.set_alias)
        # Remote Scripting
        self.moderat.connect(QShortcut(QKeySequence('Ctrl+P'), self.moderat), SIGNAL('activated()'),
                             lambda: self.moderat.execute_module(module='scripting'))
