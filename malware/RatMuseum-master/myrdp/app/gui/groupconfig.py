# -*- coding: utf-8 -*-
from app.gui.configdialog import ConfigDialog
from app.gui.groupconfig_ui import Ui_GroupConfig
from app.gui.deletegroup_ui import Ui_DeleteGroupDialog
from PyQt4.QtGui import QLineEdit, QDialog


class GroupConfigDialog(ConfigDialog):
    def __init__(self, configObject):
        optionalAttributes = ['default_user_name', 'default_password']
        attributes = ['name'] + optionalAttributes
        super(GroupConfigDialog, self).__init__(configObject, Ui_GroupConfig,
                                                attributes, optionalAttributes)
        self.ui.showPassword.clicked.connect(self.changePasswordVisibility)

    def changePasswordVisibility(self):
        if self.ui.showPassword.isChecked():
            self.ui.default_password.setEchoMode(QLineEdit.Normal)
        else:
            self.ui.default_password.setEchoMode(QLineEdit.Password)


class DeleteGroupDialog(QDialog):
    def __init__(self, hosts):
        super(DeleteGroupDialog, self).__init__()
        self.ui = Ui_DeleteGroupDialog()
        self.ui.setupUi(self)

        self.hosts = hosts

    def setGroupList(self):
        groupList = self.hosts.getGroupsList()
        self.ui.groupsComboxBox.addItems(groupList)

    def deleteGroup(self):
        self.setGroupList()
        ret = self.exec_()
        if ret == QDialog.Rejected:
            return

        self.hosts.deleteGroup(self.ui.groupsComboxBox.currentText())
