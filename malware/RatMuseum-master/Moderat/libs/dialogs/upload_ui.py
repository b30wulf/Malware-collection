# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'upload_ui.ui'
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
        Dialog.resize(529, 63)
        Dialog.setMinimumSize(QtCore.QSize(0, 0))
        Dialog.setMaximumSize(QtCore.QSize(16777215, 16777215))
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Dialog.setWindowIcon(icon)
        Dialog.setStyleSheet(_fromUtf8("background-color: #2c3e50;\n"
"color: #c9f5f7;"))
        self.gridLayout = QtGui.QGridLayout(Dialog)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.group = QtGui.QGroupBox(Dialog)
        self.group.setStyleSheet(_fromUtf8("background-color: #34495e;\n"
"border: 1px solid #2c3e50;"))
        self.group.setTitle(_fromUtf8(""))
        self.group.setObjectName(_fromUtf8("group"))
        self.gridLayout_2 = QtGui.QGridLayout(self.group)
        self.gridLayout_2.setObjectName(_fromUtf8("gridLayout_2"))
        self.progressBar = QtGui.QProgressBar(self.group)
        self.progressBar.setStyleSheet(_fromUtf8("QProgressBar:horizontal {\n"
"border: 1px ridge;\n"
"border-color: #2c3e50;\n"
"background-color: #34495e;\n"
"padding: 1px;\n"
"text-align: bottom;\n"
"color: #c9f5f7;\n"
"}\n"
"QProgressBar::chunk:horizontal {\n"
"background: #27ae60;\n"
"margin-right: 1px;\n"
"width: 5px;\n"
"color: #27ae60;\n"
"}"))
        self.progressBar.setProperty("value", 24)
        self.progressBar.setInvertedAppearance(False)
        self.progressBar.setTextDirection(QtGui.QProgressBar.TopToBottom)
        self.progressBar.setObjectName(_fromUtf8("progressBar"))
        self.gridLayout_2.addWidget(self.progressBar, 0, 0, 1, 1)
        self.gridLayout.addWidget(self.group, 0, 0, 1, 1)
        self.closeButton = QtGui.QPushButton(Dialog)
        self.closeButton.setMaximumSize(QtCore.QSize(120, 16777215))
        self.closeButton.setStyleSheet(_fromUtf8("QPushButton#closeButton {\n"
"            background-color: #27ae60;\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 10px;\n"
"            margin: 0px;\n"
"            }\n"
"\n"
"QPushButton#closeButton:pressed {\n"
"            background-color: #2ecc71;\n"
"            }"))
        self.closeButton.setObjectName(_fromUtf8("closeButton"))
        self.gridLayout.addWidget(self.closeButton, 0, 1, 1, 1)
        self.cancelButton = QtGui.QPushButton(Dialog)
        self.cancelButton.setMaximumSize(QtCore.QSize(120, 16777215))
        self.cancelButton.setStyleSheet(_fromUtf8("QPushButton#cancelButton {\n"
"            background-color: #e74c3c;\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 10px;\n"
"            margin: 0px;\n"
"            }\n"
"\n"
"QPushButton#cancelButton:pressed {\n"
"            background-color: #c0392b;\n"
"            }"))
        self.cancelButton.setObjectName(_fromUtf8("cancelButton"))
        self.gridLayout.addWidget(self.cancelButton, 0, 2, 1, 1)

        self.retranslateUi(Dialog)
        QtCore.QMetaObject.connectSlotsByName(Dialog)

    def retranslateUi(self, Dialog):
        Dialog.setWindowTitle(_translate("Dialog", "LogIn", None))
        self.closeButton.setText(_translate("Dialog", "Close", None))
        self.cancelButton.setText(_translate("Dialog", "Cancel", None))

