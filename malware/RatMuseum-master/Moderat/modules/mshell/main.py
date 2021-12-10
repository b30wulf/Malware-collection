from PyQt4.QtGui import *
from PyQt4.QtCore import *

import main_ui
import console


class mainPopup(QMainWindow, main_ui.Ui_Form):
    def __init__(self, args):
        QMainWindow.__init__(self)
        self.setupUi(self)
        self.anim = QPropertyAnimation(self, 'windowOpacity')
        self.anim.setDuration(500)
        self.anim.setStartValue(0)
        self.anim.setEndValue(1)
        self.anim.start()

        self.moderat = args['moderat']
        self.client = args['client']
        self.module_id = args['module_id']
        self.p2p = args['p2p']
        self.alias = args['alias']
        self.ip_address = args['ip_address']

        title_prefix = self.alias if len(self.alias) > 0 else self.ip_address
        self.setWindowTitle(u'{}[{}] {}'.format('(P2P)' if self.p2p else '', title_prefix, self.moderat.MString('MSHELL_TITLE')))

        self.console = console.Console()
        self.setCentralWidget(self.console)

        self.connect(self.console, SIGNAL("returnPressed"), self.runCommand)

        self.console.connect(QShortcut(QKeySequence(Qt.Key_Escape), self), SIGNAL('activated()'), self.canceled)

    def signal(self, data):
        self.callback(data)

    # run shell command
    def runCommand(self):
        command = self.console.command[1:] if self.console.command.startswith(' ') else self.console.command
        self.moderat.send_message(command, 'shellMode',
                                  session_id=self.moderat.session_id,
                                  _to=self.client,
                                  module_id=self.module_id,
                                  p2p=self.p2p)
        self.callback = self.recv_output

    def recv_output(self, data):
        if data['payload'] == 'endCommandExecute':
            self.console.append('<br>')
            self.console.newPrompt()
        else:
            self.console.append('<font color=#c9f5f7>' + data['payload'] + '</font>')

    def canceled(self):
        print 'canceled'
        self.moderat.send_message(self.module_id, 'terminateProcess',
                                  session_id=self.moderat.session_id,
                                  _to=self.client,
                                  p2p=self.p2p)

    def closeEvent(self, QCloseEvent):
        self.moderat.moderator.send_message(self.module_id,
                                            'terminateProcess',
                                            session_id=self.moderat.session_id,
                                            _to=self.client,
                                            p2p=self.p2p)
