# -*- coding: utf-8 -*-
from PyQt4 import QtCore
from PyQt4.QtGui import QDialog
from app.gui.setkeypassword_ui import Ui_SetKeyPasswordDialog

from app.config import Config
from app.log import logger


class SetKeyPassword(QDialog):
    def __init__(self):
        super(SetKeyPassword, self).__init__()
        self.ui = Ui_SetKeyPasswordDialog()
        self.ui.setupUi(self)

    def accept(self):
        try:
            self.savePassword()
        except Exception as e:
            self.ui.informationLabel.setText(e.message)
            return
        else:
            super(SetKeyPassword, self).accept()

    @staticmethod
    def isFieldEmpty(field):
        if field == QtCore.QString(u''):
            return True
        return False

    def savePassword(self):
        currentPassword = self.ui.currentPassword.text()
        newPassword = self.ui.newPassword.text()
        repeatNewPassword = self.ui.repeatPassword.text()

        if self.isFieldEmpty(currentPassword) and self.isFieldEmpty(newPassword) and \
                self.isFieldEmpty(repeatNewPassword):
            raise ValueError(u"No master password changes detected")

        if newPassword != repeatNewPassword:
            raise ValueError(u"Passwords mismatch")

        config = Config()

        ck = config.getPrivateKey(unicode(currentPassword))
        ck.save(config.privateKeyPath, unicode(newPassword))
        logger.debug(u"Key exported")
