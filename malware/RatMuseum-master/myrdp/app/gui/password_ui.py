# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/password.ui'
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

class Ui_Password(object):
    def setupUi(self, Password):
        Password.setObjectName(_fromUtf8("Password"))
        Password.resize(403, 111)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Fixed, QtGui.QSizePolicy.Fixed)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(Password.sizePolicy().hasHeightForWidth())
        Password.setSizePolicy(sizePolicy)
        self.verticalLayout = QtGui.QVBoxLayout(Password)
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        self.label = QtGui.QLabel(Password)
        self.label.setObjectName(_fromUtf8("label"))
        self.verticalLayout.addWidget(self.label)
        self.password = QtGui.QLineEdit(Password)
        self.password.setEchoMode(QtGui.QLineEdit.Password)
        self.password.setObjectName(_fromUtf8("password"))
        self.verticalLayout.addWidget(self.password)
        spacerItem = QtGui.QSpacerItem(20, 40, QtGui.QSizePolicy.Minimum, QtGui.QSizePolicy.Expanding)
        self.verticalLayout.addItem(spacerItem)
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        spacerItem1 = QtGui.QSpacerItem(208, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.horizontalLayout.addItem(spacerItem1)
        self.exitButton = QtGui.QPushButton(Password)
        self.exitButton.setObjectName(_fromUtf8("exitButton"))
        self.horizontalLayout.addWidget(self.exitButton)
        self.okButton = QtGui.QPushButton(Password)
        self.okButton.setFlat(False)
        self.okButton.setObjectName(_fromUtf8("okButton"))
        self.horizontalLayout.addWidget(self.okButton)
        self.verticalLayout.addLayout(self.horizontalLayout)

        self.retranslateUi(Password)
        QtCore.QObject.connect(self.exitButton, QtCore.SIGNAL(_fromUtf8("clicked()")), Password.reject)
        QtCore.QObject.connect(self.okButton, QtCore.SIGNAL(_fromUtf8("clicked()")), Password.accept)
        QtCore.QObject.connect(self.password, QtCore.SIGNAL(_fromUtf8("returnPressed()")), Password.accept)
        QtCore.QMetaObject.connectSlotsByName(Password)

    def retranslateUi(self, Password):
        Password.setWindowTitle(_translate("Password", "Password required", None))
        self.label.setText(_translate("Password", "Enter master password to continue", None))
        self.exitButton.setText(_translate("Password", "Exit", None))
        self.okButton.setText(_translate("Password", "OK", None))

