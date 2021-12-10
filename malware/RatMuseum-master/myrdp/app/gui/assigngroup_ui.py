# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/assigngroup.ui'
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

class Ui_AssignGroupDialog(object):
    def setupUi(self, AssignGroupDialog):
        AssignGroupDialog.setObjectName(_fromUtf8("AssignGroupDialog"))
        AssignGroupDialog.resize(409, 86)
        AssignGroupDialog.setToolTip(_fromUtf8(""))
        AssignGroupDialog.setStatusTip(_fromUtf8(""))
        self.verticalLayout = QtGui.QVBoxLayout(AssignGroupDialog)
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        self.assignGroupComboBox = QtGui.QComboBox(AssignGroupDialog)
        self.assignGroupComboBox.setEditable(True)
        self.assignGroupComboBox.setMaxVisibleItems(100)
        self.assignGroupComboBox.setInsertPolicy(QtGui.QComboBox.NoInsert)
        self.assignGroupComboBox.setObjectName(_fromUtf8("assignGroupComboBox"))
        self.verticalLayout.addWidget(self.assignGroupComboBox)
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.horizontalLayout.addItem(spacerItem)
        self.dialogButtons = QtGui.QDialogButtonBox(AssignGroupDialog)
        self.dialogButtons.setOrientation(QtCore.Qt.Horizontal)
        self.dialogButtons.setStandardButtons(QtGui.QDialogButtonBox.Cancel|QtGui.QDialogButtonBox.Ok)
        self.dialogButtons.setObjectName(_fromUtf8("dialogButtons"))
        self.horizontalLayout.addWidget(self.dialogButtons)
        self.verticalLayout.addLayout(self.horizontalLayout)

        self.retranslateUi(AssignGroupDialog)
        QtCore.QObject.connect(self.dialogButtons, QtCore.SIGNAL(_fromUtf8("accepted()")), AssignGroupDialog.accept)
        QtCore.QObject.connect(self.dialogButtons, QtCore.SIGNAL(_fromUtf8("rejected()")), AssignGroupDialog.reject)
        QtCore.QMetaObject.connectSlotsByName(AssignGroupDialog)

    def retranslateUi(self, AssignGroupDialog):
        AssignGroupDialog.setWindowTitle(_translate("AssignGroupDialog", "Assign group", None))

