# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'log_settings.ui'
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
        Form.resize(388, 255)
        icon = QtGui.QIcon()
        icon.addPixmap(QtGui.QPixmap(_fromUtf8(":/icons/assets/logo.png")), QtGui.QIcon.Normal, QtGui.QIcon.Off)
        Form.setWindowIcon(icon)
        Form.setStyleSheet(_fromUtf8("background-color: #2c3e50;\n"
"color: #c9f5f7;"))
        self.gridLayout = QtGui.QGridLayout(Form)
        self.gridLayout.setObjectName(_fromUtf8("gridLayout"))
        self.keyloggerGroup = QtGui.QGroupBox(Form)
        self.keyloggerGroup.setStyleSheet(_fromUtf8("background-color: #34495e;\n"
"border: 1px solid #2c3e50;\n"
"border-radius: 5px;\n"
"padding-top: 10px;"))
        self.keyloggerGroup.setCheckable(True)
        self.keyloggerGroup.setObjectName(_fromUtf8("keyloggerGroup"))
        self.gridLayout_2 = QtGui.QGridLayout(self.keyloggerGroup)
        self.gridLayout_2.setObjectName(_fromUtf8("gridLayout_2"))
        self.horizontalLayout = QtGui.QHBoxLayout()
        self.horizontalLayout.setObjectName(_fromUtf8("horizontalLayout"))
        self.kTimerLabel = QtGui.QLabel(self.keyloggerGroup)
        self.kTimerLabel.setMinimumSize(QtCore.QSize(200, 0))
        self.kTimerLabel.setStyleSheet(_fromUtf8("border: none;padding: 1px;"))
        self.kTimerLabel.setObjectName(_fromUtf8("kTimerLabel"))
        self.horizontalLayout.addWidget(self.kTimerLabel)
        self.kTimerLine = QtGui.QLineEdit(self.keyloggerGroup)
        self.kTimerLine.setStyleSheet(_fromUtf8("background-color: #182733; \n"
"border: none;\n"
"border: 1px ridge;\n"
"border-color: #2c3e50;\n"
"border-radius: none;\n"
"padding: 3px;"))
        self.kTimerLine.setObjectName(_fromUtf8("kTimerLine"))
        self.horizontalLayout.addWidget(self.kTimerLine)
        self.gridLayout_2.addLayout(self.horizontalLayout, 0, 0, 1, 1)
        self.gridLayout.addWidget(self.keyloggerGroup, 0, 0, 1, 1)
        self.screenshotsGroup = QtGui.QGroupBox(Form)
        self.screenshotsGroup.setStyleSheet(_fromUtf8("background-color: #34495e;\n"
"border: 1px solid #2c3e50;\n"
"border-radius: 5px;\n"
"padding-top: 10px;"))
        self.screenshotsGroup.setCheckable(True)
        self.screenshotsGroup.setObjectName(_fromUtf8("screenshotsGroup"))
        self.gridLayout_4 = QtGui.QGridLayout(self.screenshotsGroup)
        self.gridLayout_4.setObjectName(_fromUtf8("gridLayout_4"))
        self.horizontalLayout_3 = QtGui.QHBoxLayout()
        self.horizontalLayout_3.setObjectName(_fromUtf8("horizontalLayout_3"))
        self.sTimerLabel = QtGui.QLabel(self.screenshotsGroup)
        self.sTimerLabel.setMinimumSize(QtCore.QSize(200, 0))
        self.sTimerLabel.setStyleSheet(_fromUtf8("border: none;padding: 1px;"))
        self.sTimerLabel.setObjectName(_fromUtf8("sTimerLabel"))
        self.horizontalLayout_3.addWidget(self.sTimerLabel)
        self.sTimerLine = QtGui.QLineEdit(self.screenshotsGroup)
        self.sTimerLine.setStyleSheet(_fromUtf8("background-color: #182733; \n"
"border: none;\n"
"border: 1px ridge;\n"
"border-color: #2c3e50;\n"
"border-radius: none;\n"
"padding: 3px;"))
        self.sTimerLine.setObjectName(_fromUtf8("sTimerLine"))
        self.horizontalLayout_3.addWidget(self.sTimerLine)
        self.gridLayout_4.addLayout(self.horizontalLayout_3, 0, 0, 1, 1)
        self.horizontalLayout_4 = QtGui.QHBoxLayout()
        self.horizontalLayout_4.setObjectName(_fromUtf8("horizontalLayout_4"))
        self.sDelayLabel = QtGui.QLabel(self.screenshotsGroup)
        self.sDelayLabel.setMinimumSize(QtCore.QSize(200, 0))
        self.sDelayLabel.setStyleSheet(_fromUtf8("border: none;padding: 1px;"))
        self.sDelayLabel.setObjectName(_fromUtf8("sDelayLabel"))
        self.horizontalLayout_4.addWidget(self.sDelayLabel)
        self.sDelayLine = QtGui.QLineEdit(self.screenshotsGroup)
        self.sDelayLine.setStyleSheet(_fromUtf8("background-color: #182733; \n"
"border: none;\n"
"border: 1px ridge;\n"
"border-color: #2c3e50;\n"
"border-radius: none;\n"
"padding: 3px;"))
        self.sDelayLine.setObjectName(_fromUtf8("sDelayLine"))
        self.horizontalLayout_4.addWidget(self.sDelayLine)
        self.gridLayout_4.addLayout(self.horizontalLayout_4, 1, 0, 1, 1)
        self.gridLayout.addWidget(self.screenshotsGroup, 2, 0, 1, 1)
        self.audioGroup = QtGui.QGroupBox(Form)
        self.audioGroup.setStyleSheet(_fromUtf8("background-color: #34495e;\n"
"border: 1px solid #2c3e50;\n"
"border-radius: 5px;\n"
"padding-top: 10px;"))
        self.audioGroup.setCheckable(True)
        self.audioGroup.setObjectName(_fromUtf8("audioGroup"))
        self.gridLayout_3 = QtGui.QGridLayout(self.audioGroup)
        self.gridLayout_3.setObjectName(_fromUtf8("gridLayout_3"))
        self.horizontalLayout_2 = QtGui.QHBoxLayout()
        self.horizontalLayout_2.setObjectName(_fromUtf8("horizontalLayout_2"))
        self.aTimerLabel = QtGui.QLabel(self.audioGroup)
        self.aTimerLabel.setMinimumSize(QtCore.QSize(200, 0))
        self.aTimerLabel.setStyleSheet(_fromUtf8("border: none;padding: 1px;"))
        self.aTimerLabel.setObjectName(_fromUtf8("aTimerLabel"))
        self.horizontalLayout_2.addWidget(self.aTimerLabel)
        self.aTimerLine = QtGui.QLineEdit(self.audioGroup)
        self.aTimerLine.setStyleSheet(_fromUtf8("background-color: #182733; \n"
"border: none;\n"
"border: 1px ridge;\n"
"border-color: #2c3e50;\n"
"border-radius: none;\n"
"padding: 3px;"))
        self.aTimerLine.setObjectName(_fromUtf8("aTimerLine"))
        self.horizontalLayout_2.addWidget(self.aTimerLine)
        self.gridLayout_3.addLayout(self.horizontalLayout_2, 0, 0, 1, 1)
        self.gridLayout.addWidget(self.audioGroup, 1, 0, 1, 1)
        self.setButton = QtGui.QPushButton(Form)
        self.setButton.setStyleSheet(_fromUtf8("QPushButton#setButton {\n"
"            border: 1px ridge;\n"
"            border-color: #2c3e50;\n"
"            padding: 2px;\n"
"            background-color: #34495e;\n"
"            border-left: none;\n"
"            }\n"
"\n"
"QPushButton#setButton:pressed {\n"
"            background-color: #2c3e50;\n"
"            }"))
        self.setButton.setObjectName(_fromUtf8("setButton"))
        self.gridLayout.addWidget(self.setButton, 3, 0, 1, 1)

        self.retranslateUi(Form)
        QtCore.QMetaObject.connectSlotsByName(Form)

    def retranslateUi(self, Form):
        Form.setWindowTitle(_translate("Form", "Moderat Log Settings", None))
        self.keyloggerGroup.setTitle(_translate("Form", "Keylogger", None))
        self.kTimerLabel.setText(_translate("Form", "Upload Timer:", None))
        self.screenshotsGroup.setTitle(_translate("Form", "Screenshot", None))
        self.sTimerLabel.setText(_translate("Form", "Upload Timer:", None))
        self.sDelayLabel.setText(_translate("Form", "Screenshot Delay: ", None))
        self.audioGroup.setTitle(_translate("Form", "Audio", None))
        self.aTimerLabel.setText(_translate("Form", "Upload Timer:", None))
        self.setButton.setText(_translate("Form", "SET", None))

import res_rc
