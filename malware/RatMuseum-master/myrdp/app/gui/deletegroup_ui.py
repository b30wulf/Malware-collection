# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/deletegroup.ui'
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

class Ui_DeleteGroupDialog(object):
    def setupUi(self, DeleteGroupDialog):
        DeleteGroupDialog.setObjectName(_fromUtf8("DeleteGroupDialog"))
        DeleteGroupDialog.resize(339, 143)
        DeleteGroupDialog.setMinimumSize(QtCore.QSize(339, 143))
        DeleteGroupDialog.setMaximumSize(QtCore.QSize(449, 187))
        self.verticalLayout = QtGui.QVBoxLayout(DeleteGroupDialog)
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        self.label = QtGui.QLabel(DeleteGroupDialog)
        self.label.setScaledContents(False)
        self.label.setWordWrap(True)
        self.label.setObjectName(_fromUtf8("label"))
        self.verticalLayout.addWidget(self.label)
        self.groupsComboxBox = QtGui.QComboBox(DeleteGroupDialog)
        self.groupsComboxBox.setObjectName(_fromUtf8("groupsComboxBox"))
        self.verticalLayout.addWidget(self.groupsComboxBox)
        spacerItem = QtGui.QSpacerItem(20, 10, QtGui.QSizePolicy.Minimum, QtGui.QSizePolicy.Expanding)
        self.verticalLayout.addItem(spacerItem)
        self.buttonBox = QtGui.QDialogButtonBox(DeleteGroupDialog)
        self.buttonBox.setOrientation(QtCore.Qt.Horizontal)
        self.buttonBox.setStandardButtons(QtGui.QDialogButtonBox.Cancel|QtGui.QDialogButtonBox.Ok)
        self.buttonBox.setObjectName(_fromUtf8("buttonBox"))
        self.verticalLayout.addWidget(self.buttonBox)

        self.retranslateUi(DeleteGroupDialog)
        QtCore.QObject.connect(self.buttonBox, QtCore.SIGNAL(_fromUtf8("accepted()")), DeleteGroupDialog.accept)
        QtCore.QObject.connect(self.buttonBox, QtCore.SIGNAL(_fromUtf8("rejected()")), DeleteGroupDialog.reject)
        QtCore.QMetaObject.connectSlotsByName(DeleteGroupDialog)

    def retranslateUi(self, DeleteGroupDialog):
        DeleteGroupDialog.setWindowTitle(_translate("DeleteGroupDialog", "Delete group", None))
        self.label.setText(_translate("DeleteGroupDialog", "<html><head/><body><p>Select group to delete. All hosts assigned to this group will be unassigned.</p></body></html>", None))

