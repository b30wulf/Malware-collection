# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/setkeypassword.ui'
#
# Created by: PyQt4 UI code generator 4.12.1
#
# WARNING! All changes made in this file will be lost!

from PyQt4 import QtCore, QtGui

try:
    _fromUtf8 = QtCore.QString.fromUtf8
except AttributeError:
    def _fromUtf8(s):
        return s

try:
    _encoding = QtGui.QApplication.UnicodeUTF8
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig, _encoding)
except AttributeError:
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig)

class Ui_SetKeyPasswordDialog(object):
    def setupUi(self, SetKeyPasswordDialog):
        SetKeyPasswordDialog.setObjectName(_fromUtf8("SetKeyPasswordDialog"))
        SetKeyPasswordDialog.resize(353, 100)
        SetKeyPasswordDialog.setMaximumSize(QtCore.QSize(353, 190))
        self.verticalLayout_2 = QtGui.QVBoxLayout(SetKeyPasswordDialog)
        self.verticalLayout_2.setObjectName(_fromUtf8("verticalLayout_2"))
        self.gridLayout = QtGui.QGridLayout()
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.currentPasswordLabel = QtGui.QLabel(SetKeyPasswordDialog)
        self.currentPasswordLabel.setObjectName(_fromUtf8("currentPasswordLabel"))
        self.gridLayout.addWidget(self.currentPasswordLabel, 0, 0, 1, 1)
        self.currentPassword = QtGui.QLineEdit(SetKeyPasswordDialog)
        self.currentPassword.setEchoMode(QtGui.QLineEdit.Password)
        self.currentPassword.setObjectName(_fromUtf8("currentPassword"))
        self.gridLayout.addWidget(self.currentPassword, 0, 1, 1, 1)
        self.newPasswordLabel = QtGui.QLabel(SetKeyPasswordDialog)
        self.newPasswordLabel.setObjectName(_fromUtf8("newPasswordLabel"))
        self.gridLayout.addWidget(self.newPasswordLabel, 1, 0, 1, 1)
        self.newPassword = QtGui.QLineEdit(SetKeyPasswordDialog)
        self.newPassword.setEchoMode(QtGui.QLineEdit.Password)
        self.newPassword.setObjectName(_fromUtf8("newPassword"))
        self.gridLayout.addWidget(self.newPassword, 1, 1, 1, 1)
        self.repeatPasswordLabel = QtGui.QLabel(SetKeyPasswordDialog)
        self.repeatPasswordLabel.setObjectName(_fromUtf8("repeatPasswordLabel"))
        self.gridLayout.addWidget(self.repeatPasswordLabel, 2, 0, 1, 1)
        self.repeatPassword = QtGui.QLineEdit(SetKeyPasswordDialog)
        self.repeatPassword.setEchoMode(QtGui.QLineEdit.Password)
        self.repeatPassword.setObjectName(_fromUtf8("repeatPassword"))
        self.gridLayout.addWidget(self.repeatPassword, 2, 1, 1, 1)
        self.verticalLayout_2.addLayout(self.gridLayout)
        self.informationLabel = QtGui.QLabel(SetKeyPasswordDialog)
        self.informationLabel.setEnabled(True)
        font = QtGui.QFont()
        font.setBold(True)
        font.setWeight(75)
        self.informationLabel.setFont(font)
        self.informationLabel.setStyleSheet(_fromUtf8("color: red"))
        self.informationLabel.setText(_fromUtf8(""))
        self.informationLabel.setAlignment(QtCore.Qt.AlignCenter)
        self.informationLabel.setWordWrap(False)
        self.informationLabel.setObjectName(_fromUtf8("informationLabel"))
        self.verticalLayout_2.addWidget(self.informationLabel)
        self.buttonBox = QtGui.QDialogButtonBox(SetKeyPasswordDialog)
        self.buttonBox.setLocale(QtCore.QLocale(QtCore.QLocale.English, QtCore.QLocale.UnitedStates))
        self.buttonBox.setOrientation(QtCore.Qt.Horizontal)
        self.buttonBox.setStandardButtons(QtGui.QDialogButtonBox.Cancel|QtGui.QDialogButtonBox.Ok)
        self.buttonBox.setObjectName(_fromUtf8("buttonBox"))
        self.verticalLayout_2.addWidget(self.buttonBox)

        self.retranslateUi(SetKeyPasswordDialog)
        QtCore.QObject.connect(self.buttonBox, QtCore.SIGNAL(_fromUtf8("accepted()")), SetKeyPasswordDialog.accept)
        QtCore.QObject.connect(self.buttonBox, QtCore.SIGNAL(_fromUtf8("rejected()")), SetKeyPasswordDialog.reject)
        QtCore.QMetaObject.connectSlotsByName(SetKeyPasswordDialog)

    def retranslateUi(self, SetKeyPasswordDialog):
        SetKeyPasswordDialog.setWindowTitle(_translate("SetKeyPasswordDialog", "Set master password", None))
        self.currentPasswordLabel.setText(_translate("SetKeyPasswordDialog", "Current password:", None))
        self.newPasswordLabel.setText(_translate("SetKeyPasswordDialog", "New password:", None))
        self.repeatPasswordLabel.setText(_translate("SetKeyPasswordDialog", "Repeat password:", None))

