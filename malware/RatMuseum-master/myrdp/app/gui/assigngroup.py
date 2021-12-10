# -*- coding: utf-8 -*-
from PyQt4.QtGui import QDialog
from app.gui.assigngroup_ui import Ui_AssignGroupDialog


class AssignGroupDialog(QDialog):
    def __init__(self, groups):
        super(AssignGroupDialog, self).__init__()
        self.ui = Ui_AssignGroupDialog()
        self.ui.setupUi(self)
        self.ui.assignGroupComboBox.lineEdit().setPlaceholderText("Assign group")
        self.ui.assignGroupComboBox.addItem(str())  # add empty element on list begin to unset group
        for group in groups:
            self.ui.assignGroupComboBox.addItem(group)

    def assign(self):
        retCode = self.exec_()
        if retCode == QDialog.Rejected:
            return False

        return self.ui.assignGroupComboBox.currentText()
