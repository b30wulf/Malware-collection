# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'message_ui.ui'
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
        self.gridLayout_2 = QtGui.QGridLayout(Dialog)
        self.gridLayout_2.setObjectName(_fromUtf8("gridLayout_2"))
        self.okButton = QtGui.QPushButton(Dialog)
        self.okButton.setMinimumSize(QtCore.QSize(80, 0))
        self.okButton.setIconSize(QtCore.QSize(20, 20))
        self.okButton.setObjectName(_fromUtf8("okButton"))
        self.gridLayout_2.addWidget(self.okButton, 1, 1, 1, 1)
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.gridLayout_2.addItem(spacerItem, 1, 0, 1, 1)
        self.usernameGroup = QtGui.QGroupBox(Dialog)
        self.usernameGroup.setTitle(_fromUtf8(""))
        self.usernameGroup.setObjectName(_fromUtf8("usernameGroup"))
        self.gridLayout = QtGui.QGridLayout(self.usernameGroup)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.errorLabel = QtGui.QLabel(self.usernameGroup)
        self.errorLabel.setAlignment(QtCore.Qt.AlignCenter)
        self.errorLabel.setObjectName(_fromUtf8("errorLabel"))
        self.gridLayout.addWidget(self.errorLabel, 0, 0, 1, 1)
        self.gridLayout_2.addWidget(self.usernameGroup, 0, 0, 1, 2)

        self.retranslateUi(Dialog)
        QtCore.QMetaObject.connectSlotsByName(Dialog)

    def retranslateUi(self, Dialog):
        Dialog.setWindowTitle(_translate("Dialog", "Moderat - Error", None))
        self.okButton.setText(_translate("Dialog", "OK", None))
        self.errorLabel.setText(_translate("Dialog", "Message", None))

