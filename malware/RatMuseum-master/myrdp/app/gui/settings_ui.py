# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/settings.ui'
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

class Ui_SettingsPage(object):
    def setupUi(self, SettingsPage):
        SettingsPage.setObjectName(_fromUtf8("SettingsPage"))
        SettingsPage.resize(840, 526)
        self.verticalLayout = QtGui.QVBoxLayout(SettingsPage)
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        self.label = QtGui.QLabel(SettingsPage)
        self.label.setLayoutDirection(QtCore.Qt.LeftToRight)
        self.label.setAlignment(QtCore.Qt.AlignCenter)
        self.label.setObjectName(_fromUtf8("label"))
        self.verticalLayout.addWidget(self.label)
        self.gridLayout = QtGui.QGridLayout()
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.logging_level_label = QtGui.QLabel(SettingsPage)
        self.logging_level_label.setObjectName(_fromUtf8("logging_level_label"))
        self.gridLayout.addWidget(self.logging_level_label, 3, 0, 1, 1)
        self.database_location = QtGui.QLineEdit(SettingsPage)
        self.database_location.setEnabled(True)
        self.database_location.setReadOnly(True)
        self.database_location.setObjectName(_fromUtf8("database_location"))
        self.gridLayout.addWidget(self.database_location, 0, 1, 1, 1)
        self.freerdp_exec_label = QtGui.QLabel(SettingsPage)
        self.freerdp_exec_label.setObjectName(_fromUtf8("freerdp_exec_label"))
        self.gridLayout.addWidget(self.freerdp_exec_label, 1, 0, 1, 1)
        self.database_location_label = QtGui.QLabel(SettingsPage)
        self.database_location_label.setObjectName(_fromUtf8("database_location_label"))
        self.gridLayout.addWidget(self.database_location_label, 0, 0, 1, 1)
        self.master_key_button = QtGui.QPushButton(SettingsPage)
        self.master_key_button.setMaximumSize(QtCore.QSize(32, 32))
        self.master_key_button.setFocusPolicy(QtCore.Qt.NoFocus)
        self.master_key_button.setText(_fromUtf8(""))
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/ico/key.svg")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.master_key_button.setIcon(icon)
        self.master_key_button.setCheckable(False)
        self.master_key_button.setObjectName(_fromUtf8("master_key_button"))
        self.gridLayout.addWidget(self.master_key_button, 0, 3, 1, 1)
        self.logging_level = QtGui.QComboBox(SettingsPage)
        self.logging_level.setObjectName(_fromUtf8("logging_level"))
        self.gridLayout.addWidget(self.logging_level, 3, 1, 1, 3)
        self.freerdp_executable_button = QtGui.QPushButton(SettingsPage)
        self.freerdp_executable_button.setMaximumSize(QtCore.QSize(32, 32))
        self.freerdp_executable_button.setText(_fromUtf8(""))
        icon1 = QtGui.QIcon()
        icon1.addPixmap(QtGui.QPixmap(_fromUtf8(":/ico/open.svg")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.freerdp_executable_button.setIcon(icon1)
        self.freerdp_executable_button.setObjectName(_fromUtf8("freerdp_executable_button"))
        self.gridLayout.addWidget(self.freerdp_executable_button, 1, 3, 1, 1)
        self.freerdp_args_label = QtGui.QLabel(SettingsPage)
        self.freerdp_args_label.setObjectName(_fromUtf8("freerdp_args_label"))
        self.gridLayout.addWidget(self.freerdp_args_label, 2, 0, 1, 1)
        self.freerdp_arguments = QtGui.QLineEdit(SettingsPage)
        self.freerdp_arguments.setObjectName(_fromUtf8("freerdp_arguments"))
        self.gridLayout.addWidget(self.freerdp_arguments, 2, 1, 1, 3)
        self.database_location_button = QtGui.QPushButton(SettingsPage)
        self.database_location_button.setMaximumSize(QtCore.QSize(32, 32))
        self.database_location_button.setText(_fromUtf8(""))
        self.database_location_button.setIcon(icon1)
        self.database_location_button.setObjectName(_fromUtf8("database_location_button"))
        self.gridLayout.addWidget(self.database_location_button, 0, 2, 1, 1)
        self.freerdp_executable = QtGui.QLineEdit(SettingsPage)
        self.freerdp_executable.setObjectName(_fromUtf8("freerdp_executable"))
        self.gridLayout.addWidget(self.freerdp_executable, 1, 1, 1, 2)
        self.verticalLayout.addLayout(self.gridLayout)
        self.information_label = QtGui.QLabel(SettingsPage)
        self.information_label.setEnabled(True)
        font = QtGui.QFont()
        font.setBold(True)
        font.setWeight(75)
        self.information_label.setFont(font)
        self.information_label.setStyleSheet(_fromUtf8("color: red"))
        self.information_label.setAlignment(QtCore.Qt.AlignCenter)
        self.information_label.setWordWrap(False)
        self.information_label.setObjectName(_fromUtf8("information_label"))
        self.verticalLayout.addWidget(self.information_label)
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        spacerItem = QtGui.QSpacerItem(678, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.horizontalLayout.addItem(spacerItem)
        self.save_button = QtGui.QPushButton(SettingsPage)
        self.save_button.setObjectName(_fromUtf8("save_button"))
        self.horizontalLayout.addWidget(self.save_button)
        self.verticalLayout.addLayout(self.horizontalLayout)
        spacerItem1 = QtGui.QSpacerItem(20, 347, QtGui.QSizePolicy.Minimum, QtGui.QSizePolicy.Expanding)
        self.verticalLayout.addItem(spacerItem1)

        self.retranslateUi(SettingsPage)
        QtCore.QMetaObject.connectSlotsByName(SettingsPage)
        SettingsPage.setTabOrder(self.database_location, self.freerdp_executable)
        SettingsPage.setTabOrder(self.freerdp_executable, self.freerdp_arguments)

    def retranslateUi(self, SettingsPage):
        SettingsPage.setWindowTitle(_translate("SettingsPage", "Settings", None))
        self.label.setText(_translate("SettingsPage", "Settings", None))
        self.logging_level_label.setText(_translate("SettingsPage", "Logging level:", None))
        self.freerdp_exec_label.setText(_translate("SettingsPage", "Freerdp executable:", None))
        self.database_location_label.setText(_translate("SettingsPage", "Database location:", None))
        self.freerdp_args_label.setText(_translate("SettingsPage", "Freerdp arguments:", None))
        self.information_label.setText(_translate("SettingsPage", "To apply database changes restart application", None))
        self.save_button.setText(_translate("SettingsPage", "Save", None))

import resources_rc
