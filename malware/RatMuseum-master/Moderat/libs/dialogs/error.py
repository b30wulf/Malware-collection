from PyQt4.QtGui import *
from PyQt4.QtCore import *

from error_ui import Ui_Dialog

from libs.language import Translate

# Multi Lang
translate = Translate()
_ = lambda _word: translate.word(_word)


class show(QDialog, Ui_Dialog):
    def __init__(self, title, message, args):
        QWidget.__init__(self)
        self.setupUi(self)
        self.errorLabel.setHidden(True)
        self.loginButton.clicked.connect(self.getCredentials)

        self.setWindowTitle(title)
        self.errorLabel.setText(message)

    @staticmethod
    def error(title='', message='', parent=None):
        dialog = show(parent, title, message)
        result = dialog.exec_()
        return result == QDialog.Accepted
