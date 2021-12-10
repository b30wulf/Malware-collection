# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'ui/mainwindow.ui'
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

class Ui_MainWindow(object):
    def setupUi(self, MainWindow):
        MainWindow.setObjectName(_fromUtf8("MainWindow"))
        MainWindow.resize(819, 584)
        MainWindow.setFocusPolicy(QtCore.Qt.StrongFocus)
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/ico/myrdp.svg")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        MainWindow.setWindowIcon(icon)
        MainWindow.setAutoFillBackground(True)
        self.centralWidget = QtGui.QWidget(MainWindow)
        self.centralWidget.setObjectName(_fromUtf8("centralWidget"))
        self.verticalLayout_2 = QtGui.QVBoxLayout(self.centralWidget)
        self.verticalLayout_2.setObjectName(_fromUtf8("verticalLayout_2"))
        MainWindow.setCentralWidget(self.centralWidget)
        self.hostsDock = QtGui.QDockWidget(MainWindow)
        self.hostsDock.setAutoFillBackground(False)
        self.hostsDock.setFeatures(QtGui.QDockWidget.DockWidgetClosable|QtGui.QDockWidget.DockWidgetFloatable)
        self.hostsDock.setAllowedAreas(QtCore.Qt.LeftDockWidgetArea|QtCore.Qt.RightDockWidgetArea)
        self.hostsDock.setObjectName(_fromUtf8("hostsDock"))
        self.hostsWidget = QtGui.QWidget()
        self.hostsWidget.setObjectName(_fromUtf8("hostsWidget"))
        self.verticalLayout_3 = QtGui.QVBoxLayout(self.hostsWidget)
        self.verticalLayout_3.setMargin(0)
        self.verticalLayout_3.setSpacing(3)
        self.verticalLayout_3.setObjectName(_fromUtf8("verticalLayout_3"))
        self.buttonsLayout = QtGui.QHBoxLayout()
        self.buttonsLayout.setSpacing(3)
        self.buttonsLayout.setObjectName(_fromUtf8("buttonsLayout"))
        self.menu = QtGui.QPushButton(self.hostsWidget)
        self.menu.setMinimumSize(QtCore.QSize(24, 24))
        self.menu.setMaximumSize(QtCore.QSize(24, 24))
        self.menu.setFocusPolicy(QtCore.Qt.NoFocus)
        self.menu.setContextMenuPolicy(QtCore.Qt.NoContextMenu)
        self.menu.setAutoFillBackground(True)
        self.menu.setText(_fromUtf8(""))
        icon1 = QtGui.QIcon()
        icon1.addPixmap(QtGui.QPixmap(_fromUtf8(":/ico/menu.svg")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.menu.setIcon(icon1)
        self.menu.setIconSize(QtCore.QSize(24, 24))
        self.menu.setFlat(True)
        self.menu.setObjectName(_fromUtf8("menu"))
        self.buttonsLayout.addWidget(self.menu)
        self.filter = QtGui.QLineEdit(self.hostsWidget)
        self.filter.setMinimumSize(QtCore.QSize(0, 24))
        self.filter.setMaximumSize(QtCore.QSize(16777215, 24))
        self.filter.setFrame(False)
        self.filter.setObjectName(_fromUtf8("filter"))
        self.buttonsLayout.addWidget(self.filter)
        self.clear = QtGui.QPushButton(self.hostsWidget)
        self.clear.setMinimumSize(QtCore.QSize(24, 24))
        self.clear.setMaximumSize(QtCore.QSize(24, 24))
        self.clear.setFocusPolicy(QtCore.Qt.NoFocus)
        self.clear.setText(_fromUtf8(""))
        icon2 = QtGui.QIcon()
        icon2.addPixmap(QtGui.QPixmap(_fromUtf8(":/ico/clear.svg")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.clear.setIcon(icon2)
        self.clear.setFlat(True)
        self.clear.setObjectName(_fromUtf8("clear"))
        self.buttonsLayout.addWidget(self.clear)
        self.verticalLayout_3.addLayout(self.buttonsLayout)
        self.hostsList = QtGui.QListWidget(self.hostsWidget)
        self.hostsList.setEnabled(True)
        self.hostsList.setContextMenuPolicy(QtCore.Qt.CustomContextMenu)
        self.hostsList.setAutoFillBackground(True)
        self.hostsList.setAlternatingRowColors(True)
        self.hostsList.setSelectionMode(QtGui.QAbstractItemView.ExtendedSelection)
        self.hostsList.setObjectName(_fromUtf8("hostsList"))
        self.verticalLayout_3.addWidget(self.hostsList)
        self.hostsDock.setWidget(self.hostsWidget)
        MainWindow.addDockWidget(QtCore.Qt.DockWidgetArea(1), self.hostsDock)

        self.retranslateUi(MainWindow)
        QtCore.QObject.connect(self.clear, QtCore.SIGNAL(_fromUtf8("clicked()")), self.filter.clear)
        QtCore.QMetaObject.connectSlotsByName(MainWindow)

    def retranslateUi(self, MainWindow):
        MainWindow.setWindowTitle(_translate("MainWindow", "MyRDP", None))
        self.hostsDock.setWindowTitle(_translate("MainWindow", "Hosts", None))
        self.menu.setToolTip(_translate("MainWindow", "Menu", None))
        self.menu.setShortcut(_translate("MainWindow", "Ctrl+R", None))
        self.filter.setPlaceholderText(_translate("MainWindow", "Filter", None))

import resources_rc
