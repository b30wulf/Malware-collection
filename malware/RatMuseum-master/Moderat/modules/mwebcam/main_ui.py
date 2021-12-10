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
        Form.resize(557, 404)
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Form.setWindowIcon(icon)
        Form.setStyleSheet(_fromUtf8("background-color: #2c3e50;\n"
"color: #bdc3c7;"))
        self.gridLayout = QtGui.QGridLayout(Form)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.horizontalLayout_2 = QtGui.QHBoxLayout()
        self.horizontalLayout_2.setObjectName(_fromUtf8("horizontalLayout_2"))
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setSpacing(0)
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        self.cameraButton = QtGui.QPushButton(Form)
        self.cameraButton.setMinimumSize(QtCore.QSize(0, 28))
        self.cameraButton.setMaximumSize(QtCore.QSize(16777215, 28))
        self.cameraButton.setStyleSheet(_fromUtf8("QPushButton#cameraButton {\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 5px;\n"
"            background-color: #2c3e50;\n"
"            }\n"
"\n"
"QPushButton#cameraButton:pressed {\n"
"            background-color: #34495e;\n"
"            }"))
        self.cameraButton.setText(_fromUtf8(""))
        icon1 = QtGui.QIcon()
        icon1.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/webcam.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.cameraButton.setIcon(icon1)
        self.cameraButton.setObjectName(_fromUtf8("cameraButton"))
        self.horizontalLayout.addWidget(self.cameraButton)
        self.saveButton = QtGui.QPushButton(Form)
        self.saveButton.setMinimumSize(QtCore.QSize(0, 28))
        self.saveButton.setMaximumSize(QtCore.QSize(16777215, 28))
        self.saveButton.setStyleSheet(_fromUtf8("QPushButton#saveButton {\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 5px;\n"
"            background-color: #2c3e50;\n"
"            }\n"
"\n"
"QPushButton#saveButton:pressed {\n"
"            background-color: #34495e;\n"
"            }"))
        self.saveButton.setText(_fromUtf8(""))
        icon2 = QtGui.QIcon()
        icon2.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/save_as.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.saveButton.setIcon(icon2)
        self.saveButton.setObjectName(_fromUtf8("saveButton"))
        self.horizontalLayout.addWidget(self.saveButton)
        self.clearButton = QtGui.QPushButton(Form)
        self.clearButton.setMinimumSize(QtCore.QSize(0, 28))
        self.clearButton.setMaximumSize(QtCore.QSize(16777215, 28))
        self.clearButton.setStyleSheet(_fromUtf8("QPushButton#clearButton {\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 5px;\n"
"            background-color: #2c3e50;\n"
"            }\n"
"\n"
"QPushButton#clearButton:pressed {\n"
"            background-color: #34495e;\n"
"            }"))
        self.clearButton.setText(_fromUtf8(""))
        icon3 = QtGui.QIcon()
        icon3.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/trash.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.clearButton.setIcon(icon3)
        self.clearButton.setObjectName(_fromUtf8("clearButton"))
        self.horizontalLayout.addWidget(self.clearButton)
        self.horizontalLayout_2.addLayout(self.horizontalLayout)
        spacerItem = QtGui.QSpacerItem(40, 20, QtGui.QSizePolicy.Expanding, QtGui.QSizePolicy.Minimum)
        self.horizontalLayout_2.addItem(spacerItem)
        self.alwaysTopButton = QtGui.QPushButton(Form)
        self.alwaysTopButton.setMinimumSize(QtCore.QSize(0, 24))
        self.alwaysTopButton.setStyleSheet(_fromUtf8("QPushButton#alwaysTopButton {\n"
"            border: none;\n"
"            border-radius: none;\n"
"            padding: 5px;\n"
"            background-color: #2c3e50;\n"
"            }\n"
"\n"
"QPushButton#alwaysTopButton:checked {\n"
"            border: 1px ridge #c792ea;\n"
"            }"))
        self.alwaysTopButton.setText(_fromUtf8(""))
        icon4 = QtGui.QIcon()
        icon4.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/pin.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        self.alwaysTopButton.setIcon(icon4)
        self.alwaysTopButton.setCheckable(True)
        self.alwaysTopButton.setObjectName(_fromUtf8("alwaysTopButton"))
        self.horizontalLayout_2.addWidget(self.alwaysTopButton)
        self.gridLayout.addLayout(self.horizontalLayout_2, 0, 0, 1, 1)
        self.cameraLabel = QtGui.QLabel(Form)
        self.cameraLabel.setStyleSheet(_fromUtf8("background: none;\n"
"background-color: #34495e;\n"
"color: grey;"))
        self.cameraLabel.setAlignment(QtCore.Qt.AlignCenter)
        self.cameraLabel.setObjectName(_fromUtf8("cameraLabel"))
        self.gridLayout.addWidget(self.cameraLabel, 1, 0, 1, 1)

        self.retranslateUi(Form)
        QtCore.QMetaObject.connectSlotsByName(Form)

    def retranslateUi(self, Form):
        Form.setWindowTitle(_translate("Form", "Remote Camera Preview", None))
        self.cameraLabel.setText(_translate("Form", "Desktop Preview", None))

