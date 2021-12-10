# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'list_ui.ui'
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
        Form.resize(721, 442)
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Form.setWindowIcon(icon)
        Form.setStyleSheet(_fromUtf8("QWidget {\n"
"background-color: #2c3e50;\n"
"color: #c9f5f7;\n"
"}\n"
"\n"
"QScrollBar:vertical {\n"
"     border: 1px outset;\n"
"     border-color: #0F2D40;\n"
"     width: 10px;\n"
"     margin: 22px 0 22px 0;\n"
" }\n"
" QScrollBar::handle:vertical {\n"
"     background: #95a5a6;\n"
"     min-height: 20px;\n"
" }\n"
" QScrollBar::add-line:vertical {\n"
"     border: 1px outset;\n"
"     border-color: #0F2D40;\n"
"     background: #95a5a6;\n"
"     height: 16px;\n"
"     subcontrol-position: bottom;\n"
"     subcontrol-origin: margin;\n"
" }\n"
"\n"
" QScrollBar::sub-line:vertical {\n"
"     border: 1px outset;\n"
"     border-color: #0F2D40;\n"
"     background: #95a5a6;\n"
"     height: 16px;\n"
"     subcontrol-position: top;\n"
"     subcontrol-origin: margin;\n"
" }\n"
" QScrollBar::up-arrow:vertical, QScrollBar::down-arrow:vertical {\n"
"     width: 3px;\n"
"     height: 3px;\n"
"     background: white;\n"
" }\n"
"\n"
" QScrollBar::add-page:vertical, QScrollBar::sub-page:vertical {\n"
"     background: none;\n"
" }\n"
"\n"
" QScrollBar:horizontal {\n"
"border: 1px outset;\n"
"     border-color: #0F2D40;\n"
"     height: 10px;\n"
"     margin: 0px 40px 0 0px;\n"
"}\n"
"\n"
"QScrollBar::handle:horizontal {\n"
"    background: #95a5a6;\n"
"    min-width: 20px;\n"
"}\n"
"\n"
"QScrollBar::add-line:horizontal {\n"
"    border: 1px outset;\n"
"    border-color: #0F2D40;\n"
"    background: #95a5a6;\n"
"    width: 16px;\n"
"    subcontrol-position: right;\n"
"    subcontrol-origin: margin;\n"
"}\n"
"\n"
"QScrollBar::sub-line:horizontal {\n"
"    border: 1px outset;\n"
"    border-color: #0F2D40;\n"
"    background: #95a5a6;\n"
"    width: 16px;\n"
"    subcontrol-position: top right;\n"
"    subcontrol-origin: margin;\n"
"    position: absolute;\n"
"    right: 20px;\n"
"}\n"
"\n"
"QScrollBar:left-arrow:horizontal, QScrollBar::right-arrow:horizontal {\n"
"    width: 3px;\n"
"    height: 3px;\n"
"    background: white;\n"
"}\n"
"\n"
"QScrollBar::add-page:horizontal, QScrollBar::sub-page:horizontal {\n"
"    background: none;\n"
"}"))
        self.gridLayout_2 = QtGui.QGridLayout(Form)
        self.gridLayout_2.setObjectName(_fromUtf8("gridLayout_2"))
        self.groupBox = QtGui.QGroupBox(Form)
        self.groupBox.setMaximumSize(QtCore.QSize(250, 16777215))
        self.groupBox.setStyleSheet(_fromUtf8("background-color: #34495e;\n"
"border: 1px solid #2c3e50;\n"
"padding: -5px;"))
        self.groupBox.setTitle(_fromUtf8(""))
        self.groupBox.setObjectName(_fromUtf8("groupBox"))
        self.gridLayout = QtGui.QGridLayout(self.groupBox)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.verticalLayout = QtGui.QVBoxLayout()
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setSpacing(0)
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        self.searchButton = QtGui.QPushButton(self.groupBox)
        self.searchButton.setMinimumSize(QtCore.QSize(0, 28))
        self.searchButton.setMaximumSize(QtCore.QSize(16777215, 28))
        self.searchButton.setStyleSheet(_fromUtf8("background-color: #455F7A;\n"
"padding: 5px;\n"
"border-right: none;"))
        self.searchButton.setText(_fromUtf8(""))
        icon1 = QtGui.QIcon()
        icon1.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/search.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.searchButton.setIcon(icon1)
        self.searchButton.setObjectName(_fromUtf8("searchButton"))
        self.horizontalLayout.addWidget(self.searchButton)
        self.searchLine = QtGui.QLineEdit(self.groupBox)
        self.searchLine.setMinimumSize(QtCore.QSize(0, 28))
        self.searchLine.setMaximumSize(QtCore.QSize(16777215, 28))
        self.searchLine.setStyleSheet(_fromUtf8("background-color: #455F7A;\n"
"padding: 5px;\n"
"border-left: none;"))
        self.searchLine.setObjectName(_fromUtf8("searchLine"))
        self.horizontalLayout.addWidget(self.searchLine)
        self.verticalLayout.addLayout(self.horizontalLayout)
        self.pluginsList = QtGui.QListWidget(self.groupBox)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Expanding)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.pluginsList.sizePolicy().hasHeightForWidth())
        self.pluginsList.setSizePolicy(sizePolicy)
        self.pluginsList.setMaximumSize(QtCore.QSize(16777215, 16777215))
        self.pluginsList.setFocusPolicy(QtCore.Qt.NoFocus)
        self.pluginsList.setContextMenuPolicy(QtCore.Qt.DefaultContextMenu)
        self.pluginsList.setStyleSheet(_fromUtf8("QListWidget#pluginsList {\n"
"    show-decoration-selected: 1; /* make the selection span the entire width of the view */\n"
"    background-color: #455F7A;\n"
"    padding: 2px;\n"
"    color: #cff7f8;\n"
"    font: 75 11px \"MS Shell Dlg 2\";\n"
"    border: 1px solid;\n"
"    border-color: #34495e;\n"
"}\n"
"\n"
"QListWidget#pluginsList::item:selected {\n"
"    border: 1px solid #1abc9c;\n"
"    color: #cff7f8;\n"
"}\n"
"\n"
"QListWidget#pluginsList::item:hover {\n"
"    background: #517091;\n"
"}"))
        self.pluginsList.setObjectName(_fromUtf8("pluginsList"))
        self.verticalLayout.addWidget(self.pluginsList)
        self.gridLayout.addLayout(self.verticalLayout, 0, 0, 1, 1)
        self.gridLayout_2.addWidget(self.groupBox, 0, 0, 1, 1)
        self.detailsText = QtGui.QTextEdit(Form)
        self.detailsText.setStyleSheet(_fromUtf8("background-color: #2c3e50;\n"
"padding: 2px;\n"
"color: #cff7f8;\n"
"font: 75 12px \"MS Shell Dlg 2\";\n"
"border: 1px ridge;\n"
"border-color: #34495e;\n"
"\n"
"background-image: url(assets/bg.png);\n"
"background-repeat: no-repeat;"))
        self.detailsText.setReadOnly(True)
        self.detailsText.setTextInteractionFlags(QtCore.Qt.LinksAccessibleByMouse|QtCore.Qt.TextSelectableByMouse)
        self.detailsText.setObjectName(_fromUtf8("detailsText"))
        self.gridLayout_2.addWidget(self.detailsText, 0, 1, 1, 1)

        self.retranslateUi(Form)
        QtCore.QMetaObject.connectSlotsByName(Form)

    def retranslateUi(self, Form):
        Form.setWindowTitle(_translate("Form", "Plugins List", None))

