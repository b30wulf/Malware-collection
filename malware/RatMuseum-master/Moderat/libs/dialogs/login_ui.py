# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'login_ui.ui'
#
# Created by: PyQt4 UI code generator 4.11.4
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

class Ui_Dialog(object):
    def setupUi(self, Dialog):
        Dialog.setObjectName(_fromUtf8("Dialog"))
        Dialog.resize(395, 136)
        Dialog.setMinimumSize(QtCore.QSize(0, 0))
        Dialog.setMaximumSize(QtCore.QSize(16777215, 16777215))
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Dialog.setWindowIcon(icon)
        self.gridLayout = QtGui.QGridLayout(Dialog)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.gridLayout.addItem(spacerItem, 2, 0, 1, 1)
        self.loginButton = QtGui.QPushButton(Dialog)
        self.loginButton.setIconSize(QtCore.QSize(20, 20))
        self.loginButton.setObjectName(_fromUtf8("loginButton"))
        self.gridLayout.addWidget(self.loginButton, 2, 1, 1, 1)
        self.passwordLine = QtGui.QLineEdit(Dialog)
        self.passwordLine.setMinimumSize(QtCore.QSize(0, 34))
        self.passwordLine.setEchoMode(QtGui.QLineEdit.Password)
        self.passwordLine.setObjectName(_fromUtf8("passwordLine"))
        self.gridLayout.addWidget(self.passwordLine, 1, 0, 1, 2)
        self.usernameLine = QtGui.QLineEdit(Dialog)
        self.usernameLine.setMinimumSize(QtCore.QSize(0, 34))
        self.usernameLine.setProperty("username", _fromUtf8("1"))
        self.usernameLine.setObjectName(_fromUtf8("usernameLine"))
        self.gridLayout.addWidget(self.usernameLine, 0, 0, 1, 2)

        self.retranslateUi(Dialog)
        QtCore.QMetaObject.connectSlotsByName(Dialog)

    def retranslateUi(self, Dialog):
        Dialog.setWindowTitle(_translate("Dialog", "LogIn", None))
        self.loginButton.setText(_translate("Dialog", "Login", None))
        self.passwordLine.setPlaceholderText(_translate("Dialog", "Password", None))
        self.passwordLine.setProperty("password", _translate("Dialog", "1", None))
        self.usernameLine.setPlaceholderText(_translate("Dialog", "Username", None))

