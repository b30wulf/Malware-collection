# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/groupconfig.ui'
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

class Ui_GroupConfig(object):
    def setupUi(self, GroupConfig):
        GroupConfig.setObjectName(_fromUtf8("GroupConfig"))
        GroupConfig.resize(358, 197)
        GroupConfig.setMaximumSize(QtCore.QSize(358, 282))
        self.verticalLayout = QtGui.QVBoxLayout(GroupConfig)
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        self.gridLayout = QtGui.QGridLayout()
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.default_user_name = QtGui.QLineEdit(GroupConfig)
        self.default_user_name.setPlaceholderText(_fromUtf8(""))
        self.default_user_name.setObjectName(_fromUtf8("default_user_name"))
        self.gridLayout.addWidget(self.default_user_name, 1, 1, 1, 1)
        self.nameLabel = QtGui.QLabel(GroupConfig)
        font = QtGui.QFont()
        font.setBold(True)
        font.setWeight(75)
        self.nameLabel.setFont(font)
        self.nameLabel.setObjectName(_fromUtf8("nameLabel"))
        self.gridLayout.addWidget(self.nameLabel, 0, 0, 1, 1)
        self.name = QtGui.QLineEdit(GroupConfig)
        self.name.setAutoFillBackground(False)
        self.name.setText(_fromUtf8(""))
        self.name.setPlaceholderText(_fromUtf8(""))
        self.name.setObjectName(_fromUtf8("name"))
        self.gridLayout.addWidget(self.name, 0, 1, 1, 1)
        self.passwordLabel = QtGui.QLabel(GroupConfig)
        font = QtGui.QFont()
        font.setBold(False)
        font.setWeight(50)
        self.passwordLabel.setFont(font)
        self.passwordLabel.setObjectName(_fromUtf8("passwordLabel"))
        self.gridLayout.addWidget(self.passwordLabel, 2, 0, 1, 1)
        self.horizontalLayout_2 = QtGui.QHBoxLayout()
        self.horizontalLayout_2.setObjectName(_fromUtf8("horizontalLayout_2"))
        self.default_password = QtGui.QLineEdit(GroupConfig)
        self.default_password.setEchoMode(QtGui.QLineEdit.Password)
        self.default_password.setPlaceholderText(_fromUtf8(""))
        self.default_password.setObjectName(_fromUtf8("default_password"))
        self.horizontalLayout_2.addWidget(self.default_password)
        self.showPassword = QtGui.QPushButton(GroupConfig)
        self.showPassword.setMaximumSize(QtCore.QSize(30, 26))
        self.showPassword.setFocusPolicy(QtCore.Qt.NoFocus)
        self.showPassword.setToolTip(_fromUtf8("Show password"))
        self.showPassword.setAccessibleName(_fromUtf8(""))
        self.showPassword.setAccessibleDescription(_fromUtf8(""))
        self.showPassword.setText(_fromUtf8(""))
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/ico/eye.svg")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.showPassword.setIcon(icon)
        self.showPassword.setIconSize(QtCore.QSize(30, 20))
        self.showPassword.setCheckable(True)
        self.showPassword.setFlat(True)
        self.showPassword.setObjectName(_fromUtf8("showPassword"))
        self.horizontalLayout_2.addWidget(self.showPassword)
        self.gridLayout.addLayout(self.horizontalLayout_2, 2, 1, 1, 1)
        self.userLabel = QtGui.QLabel(GroupConfig)
        font = QtGui.QFont()
        font.setBold(False)
        font.setWeight(50)
        self.userLabel.setFont(font)
        self.userLabel.setObjectName(_fromUtf8("userLabel"))
        self.gridLayout.addWidget(self.userLabel, 1, 0, 1, 1)
        self.verticalLayout.addLayout(self.gridLayout)
        self.informationLabel = QtGui.QLabel(GroupConfig)
        self.informationLabel.setEnabled(True)
        font = QtGui.QFont()
        font.setBold(True)
        font.setWeight(75)
        self.informationLabel.setFont(font)
        self.informationLabel.setStyleSheet(_fromUtf8("color: red"))
        self.informationLabel.setText(_fromUtf8(""))
        self.informationLabel.setAlignment(QtCore.Qt.AlignCenter)
        self.informationLabel.setWordWrap(False)
        self.informationLabel.setObjectName(_fromUtf8("informationLabel"))
        self.verticalLayout.addWidget(self.informationLabel)
        spacerItem = QtGui.QSpacerItem(20, 40, QtGui.QSizePolicy.Minimum, QtGui.QSizePolicy.Expanding)
        self.verticalLayout.addItem(spacerItem)
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        spacerItem1 = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.horizontalLayout.addItem(spacerItem1)
        self.buttonBox = QtGui.QDialogButtonBox(GroupConfig)
        self.buttonBox.setLocale(QtCore.QLocale(QtCore.QLocale.English, QtCore.QLocale.UnitedStates))
        self.buttonBox.setStandardButtons(QtGui.QDialogButtonBox.Cancel|QtGui.QDialogButtonBox.Ok)
        self.buttonBox.setObjectName(_fromUtf8("buttonBox"))
        self.horizontalLayout.addWidget(self.buttonBox)
        self.verticalLayout.addLayout(self.horizontalLayout)

        self.retranslateUi(GroupConfig)
        QtCore.QObject.connect(self.buttonBox, QtCore.SIGNAL(_fromUtf8("rejected()")), GroupConfig.reject)
        QtCore.QMetaObject.connectSlotsByName(GroupConfig)
        GroupConfig.setTabOrder(self.name, self.default_user_name)
        GroupConfig.setTabOrder(self.default_user_name, self.default_password)
        GroupConfig.setTabOrder(self.default_password, self.buttonBox)

    def retranslateUi(self, GroupConfig):
        GroupConfig.setWindowTitle(_translate("GroupConfig", "Group config", None))
        self.nameLabel.setText(_translate("GroupConfig", "Name:", None))
        self.passwordLabel.setText(_translate("GroupConfig", "Default password:", None))
        self.userLabel.setText(_translate("GroupConfig", "Default user:", None))

import resources_rc
