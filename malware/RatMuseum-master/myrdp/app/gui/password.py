# -*- coding: utf-8 -*-
from PyQt4.QtGui import QDialog
from app.gui.password_ui import Ui_Password


class PasswordDialog(QDialog):
    def __init__(self):
        super(PasswordDialog, self).__init__()
        self.ui = Ui_Password()
        self.ui.setupUi(self)

    def getPassword(self):
        return unicode(self.ui.password.text())