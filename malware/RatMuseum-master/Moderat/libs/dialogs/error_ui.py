# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'error_ui.ui'
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
        Dialog.resize(431, 140)
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Dialog.setWindowIcon(icon)
        Dialog.setStyleSheet(_fromUtf8("background-color: #2c3e50;\n"
"color: #c9f5f7;"))
        self.gridLayout_2 = QtGui.QGridLayout(Dialog)
        self.gridLayout_2.setObjectName(_fromUtf8("gridLayout_2"))
        self.loginButton = QtGui.QPushButton(Dialog)
        self.loginButton.setMinimumSize(QtCore.QSize(80, 0))
        self.loginButton.setStyleSheet(_fromUtf8("QPushButton#loginButton {\n"
"            background-color: #27ae60;\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 10px;\n"
"            margin: 0px;\n"
"            }\n"
"\n"
"QPushButton#loginButton:pressed {\n"
"            background-color: #2ecc71;\n"
"            }"))
        self.loginButton.setIconSize(QtCore.QSize(20, 20))
        self.loginButton.setObjectName(_fromUtf8("loginButton"))
        self.gridLayout_2.addWidget(self.loginButton, 1, 1, 1, 1)
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.gridLayout_2.addItem(spacerItem, 1, 0, 1, 1)
        self.usernameGroup = QtGui.QGroupBox(Dialog)
        self.usernameGroup.setStyleSheet(_fromUtf8("background-color: #34495e;\n"
"border: 1px solid #2c3e50;\n"
"padding: -3px;"))
        self.usernameGroup.setTitle(_fromUtf8(""))
        self.usernameGroup.setObjectName(_fromUtf8("usernameGroup"))
        self.gridLayout = QtGui.QGridLayout(self.usernameGroup)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.errorLabel = QtGui.QLabel(self.usernameGroup)
        self.errorLabel.setStyleSheet(_fromUtf8("color: #e74c3c; border: none;"))
        self.errorLabel.setAlignment(QtCore.Qt.AlignCenter)
        self.errorLabel.setObjectName(_fromUtf8("errorLabel"))
        self.gridLayout.addWidget(self.errorLabel, 0, 0, 1, 1)
        self.gridLayout_2.addWidget(self.usernameGroup, 0, 0, 1, 2)

        self.retranslateUi(Dialog)
        QtCore.QMetaObject.connectSlotsByName(Dialog)

    def retranslateUi(self, Dialog):
        Dialog.setWindowTitle(_translate("Dialog", "Moderat - Error", None))
        self.loginButton.setText(_translate("Dialog", "OK", None))
        self.errorLabel.setText(_translate("Dialog", "Error Message", None))

