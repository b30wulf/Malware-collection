# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'text_ui.ui'
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
        Dialog.resize(412, 120)
        Dialog.setMinimumSize(QtCore.QSize(412, 120))
        Dialog.setMaximumSize(QtCore.QSize(412, 120))
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Dialog.setWindowIcon(icon)
        self.gridLayout_3 = QtGui.QGridLayout(Dialog)
        self.gridLayout_3.setObjectName(_fromUtf8("gridLayout_3"))
        self.okButton = QtGui.QPushButton(Dialog)
        self.okButton.setIconSize(QtCore.QSize(20, 20))
        self.okButton.setObjectName(_fromUtf8("okButton"))
        self.gridLayout_3.addWidget(self.okButton, 1, 1, 1, 1)
        self.cancelButton = QtGui.QPushButton(Dialog)
        self.cancelButton.setObjectName(_fromUtf8("cancelButton"))
        self.gridLayout_3.addWidget(self.cancelButton, 1, 2, 1, 1)
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.gridLayout_3.addItem(spacerItem, 1, 0, 1, 1)
        self.textLine = QtGui.QLineEdit(Dialog)
        self.textLine.setObjectName(_fromUtf8("textLine"))
        self.gridLayout_3.addWidget(self.textLine, 0, 0, 1, 3)

        self.retranslateUi(Dialog)
        QtCore.QMetaObject.connectSlotsByName(Dialog)

    def retranslateUi(self, Dialog):
        Dialog.setWindowTitle(_translate("Dialog", "Get Text", None))
        self.okButton.setText(_translate("Dialog", "Ok", None))
        self.cancelButton.setText(_translate("Dialog", "Cancel", None))
        self.textLine.setPlaceholderText(_translate("Dialog", "Placeholdertext", None))

