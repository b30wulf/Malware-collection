# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'main_ui.ui'
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

class Ui_Form(object):
    def setupUi(self, Form):
        Form.setObjectName(_fromUtf8("Form"))
        Form.resize(766, 479)
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Form.setWindowIcon(icon)
        self.gridLayout = QtGui.QGridLayout(Form)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.explorerTable = QtGui.QTableWidget(Form)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Expanding)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.explorerTable.sizePolicy().hasHeightForWidth())
        self.explorerTable.setSizePolicy(sizePolicy)
        self.explorerTable.setFocusPolicy(QtCore.Qt.NoFocus)
        self.explorerTable.setAcceptDrops(True)
        self.explorerTable.setFrameShape(QtGui.QFrame.StyledPanel)
        self.explorerTable.setFrameShadow(QtGui.QFrame.Plain)
        self.explorerTable.setLineWidth(1)
        self.explorerTable.setEditTriggers(QtGui.QAbstractItemView.NoEditTriggers)
        self.explorerTable.setProperty("showDropIndicator", False)
        self.explorerTable.setDragDropOverwriteMode(False)
        self.explorerTable.setAlternatingRowColors(False)
        self.explorerTable.setSelectionMode(QtGui.QAbstractItemView.SingleSelection)
        self.explorerTable.setSelectionBehavior(QtGui.QAbstractItemView.SelectRows)
        self.explorerTable.setTextElideMode(QtCore.Qt.ElideMiddle)
        self.explorerTable.setShowGrid(False)
        self.explorerTable.setGridStyle(QtCore.Qt.DotLine)
        self.explorerTable.setWordWrap(False)
        self.explorerTable.setCornerButtonEnabled(True)
        self.explorerTable.setObjectName(_fromUtf8("explorerTable"))
        self.explorerTable.setColumnCount(4)
        self.explorerTable.setRowCount(0)
        item = QtGui.QTableWidgetItem()
        self.explorerTable.setHorizontalHeaderItem(0, item)
        item = QtGui.QTableWidgetItem()
        self.explorerTable.setHorizontalHeaderItem(1, item)
        item = QtGui.QTableWidgetItem()
        self.explorerTable.setHorizontalHeaderItem(2, item)
        item = QtGui.QTableWidgetItem()
        self.explorerTable.setHorizontalHeaderItem(3, item)
        self.explorerTable.horizontalHeader().setVisible(True)
        self.explorerTable.horizontalHeader().setCascadingSectionResizes(True)
        self.explorerTable.horizontalHeader().setDefaultSectionSize(50)
        self.explorerTable.horizontalHeader().setHighlightSections(True)
        self.explorerTable.horizontalHeader().setSortIndicatorShown(False)
        self.explorerTable.horizontalHeader().setStretchLastSection(True)
        self.explorerTable.verticalHeader().setVisible(False)
        self.explorerTable.verticalHeader().setCascadingSectionResizes(False)
        self.gridLayout.addWidget(self.explorerTable, 0, 0, 1, 1)

        self.retranslateUi(Form)
        QtCore.QMetaObject.connectSlotsByName(Form)

    def retranslateUi(self, Form):
        Form.setWindowTitle(_translate("Form", "Form", None))
        self.explorerTable.setSortingEnabled(False)
        item = self.explorerTable.horizontalHeaderItem(0)
        item.setText(_translate("Form", "Type", None))
        item = self.explorerTable.horizontalHeaderItem(1)
        item.setText(_translate("Form", "Name", None))
        item = self.explorerTable.horizontalHeaderItem(2)
        item.setText(_translate("Form", "Date Modified", None))
        item = self.explorerTable.horizontalHeaderItem(3)
        item.setText(_translate("Form", "Size", None))

