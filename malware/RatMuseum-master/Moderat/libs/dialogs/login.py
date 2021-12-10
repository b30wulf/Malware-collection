from PyQt4.QtGui import *
from PyQt4.QtCore import *
from login_ui import Ui_Dialog


class Login(QDialog, Ui_Dialog):
    def __init__(self, moderat):
        QWidget.__init__(self)
        self.moderat = moderat

        self.setupUi(self)
        self.anim = QPropertyAnimation(self, 'windowOpacity')
        self.anim.setDuration(500)
        self.anim.setStartValue(0)
        self.anim.setEndValue(1)
        self.anim.start()

        self.setStyleSheet(self.moderat.theme.stylesheet)
        self.usernameLine.setText(self.moderat.settings.serverUsername)

        self.setWindowFlags(Qt.WindowSystemMenuHint | Qt.WindowTitleHint)

        self.loginButton.clicked.connect(self.getCredentials)

        self.setWindowTitle(self.moderat.MString('LOG_IN_TITLE'))
        self.usernameLine.setPlaceholderText(self.moderat.MString('LOG_IN_USERNAME'))
        self.passwordLine.setPlaceholderText(self.moderat.MString('LOG_IN_PASSWORD'))
        self.loginButton.setText(self.moderat.MString('LOG_IN'))

        if len(self.moderat.settings.serverUsername) > 0:
            self.passwordLine.setFocus()

    def getCredentials(self):
        username = str(self.usernameLine.text())
        password = str(self.passwordLine.text())
        self.accept()
        return {
            'username': username,
            'password': password
        }

    def closeEvent(self, QCloseEvent):
        pass


def get(parent=None):
    dialog = Login(parent)
    result = dialog.exec_()
    return result == QDialog.Accepted, dialog.getCredentials()
