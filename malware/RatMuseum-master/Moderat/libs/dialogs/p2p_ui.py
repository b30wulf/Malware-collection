# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'p2p_ui.ui'
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
        Dialog.resize(412, 300)
        Dialog.setMinimumSize(QtCore.QSize(412, 300))
        Dialog.setMaximumSize(QtCore.QSize(412, 300))
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Dialog.setWindowIcon(icon)
        self.gridLayout_3 = QtGui.QGridLayout(Dialog)
        self.gridLayout_3.setObjectName(_fromUtf8("gridLayout_3"))
        self.connectP2pButton = QtGui.QPushButton(Dialog)
        self.connectP2pButton.setIconSize(QtCore.QSize(20, 20))
        self.connectP2pButton.setObjectName(_fromUtf8("connectP2pButton"))
        self.gridLayout_3.addWidget(self.connectP2pButton, 3, 1, 1, 1)
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.gridLayout_3.addItem(spacerItem, 3, 0, 1, 1)
        self.messageText = QtGui.QTextEdit(Dialog)
        self.messageText.setObjectName(_fromUtf8("messageText"))
        self.gridLayout_3.addWidget(self.messageText, 2, 0, 1, 2)
        self.portLine = QtGui.QLineEdit(Dialog)
        self.portLine.setMinimumSize(QtCore.QSize(0, 34))
        self.portLine.setEchoMode(QtGui.QLineEdit.Normal)
        self.portLine.setObjectName(_fromUtf8("portLine"))
        self.gridLayout_3.addWidget(self.portLine, 1, 0, 1, 2)
        self.ipaddressLine = QtGui.QLineEdit(Dialog)
        self.ipaddressLine.setMinimumSize(QtCore.QSize(0, 34))
        self.ipaddressLine.setObjectName(_fromUtf8("ipaddressLine"))
        self.gridLayout_3.addWidget(self.ipaddressLine, 0, 0, 1, 2)

        self.retranslateUi(Dialog)
        QtCore.QMetaObject.connectSlotsByName(Dialog)

    def retranslateUi(self, Dialog):
        Dialog.setWindowTitle(_translate("Dialog", "Direct Connetion", None))
        self.connectP2pButton.setText(_translate("Dialog", "Connect", None))
        self.messageText.setHtml(_translate("Dialog", "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\" \"http://www.w3.org/TR/REC-html40/strict.dtd\">\n"
"<html><head><meta name=\"qrichtext\" content=\"1\" /><style type=\"text/css\">\n"
"p, li { white-space: pre-wrap; }\n"
"</style></head><body style=\" font-family:\'Cantarell\'; font-size:11pt; font-weight:400; font-style:normal;\">\n"
"<p style=\" margin-top:0px; margin-bottom:0px; margin-left:0px; margin-right:0px; -qt-block-indent:0; text-indent:0px;\">Comment</p></body></html>", None))
        self.portLine.setText(_translate("Dialog", "5545", None))
        self.portLine.setPlaceholderText(_translate("Dialog", "Port", None))
        self.ipaddressLine.setText(_translate("Dialog", "127.0.0.1", None))
        self.ipaddressLine.setPlaceholderText(_translate("Dialog", "IP Address", None))

