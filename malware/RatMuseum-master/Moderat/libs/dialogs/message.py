from PyQt4.QtGui import *
from PyQt4.QtCore import *

from message_ui import Ui_Dialog


class show(QDialog, Ui_Dialog):
    def __init__(self, moderat, title, message, color):
        QWidget.__init__(self)
        self.moderat = moderat
        self.setupUi(self)
        self.anim = QPropertyAnimation(self, 'windowOpacity')
        self.anim.setDuration(500)
        self.anim.setStartValue(0)
        self.anim.setEndValue(1)
        self.anim.start()

        self.setStyleSheet(self.moderat.theme.stylesheet)
        self.errorLabel.setStyleSheet('color: {};border: none;'.format(color))
        self.okButton.clicked.connect(self.accept)

        self.setWindowTitle(title)
        self.errorLabel.setText(message)

        self.okButton.setText(self.moderat.MString('DIALOG_OK'))


def error(parent, title='', message='', color='#e74c3c'):
    dialog = show(parent, title, message, color)
    result = dialog.exec_()
    return result == QDialog.Accepted


def info(parent, title='', message='', color='#c9f5f7'):
    dialog = show(parent, title, message, color)
    result = dialog.exec_()
    return result == QDialog.Accepted
