from PyQt4.QtCore import *
from PyQt4.QtGui import *


class Output(QTabWidget):

    def __init__(self, ide, moderat=None):
        QTabWidget.__init__(self, ide)
        self.moderat = moderat
        self.addNormalOutput()
        self.addDumpOutput()

    def addNormalOutput(self):
        self.mprintTab = QWidget()
        self.addTab(self.mprintTab, QIcon(':/icons/assets/remote_shell.png'), self.moderat.MString('MSCRIPTING_MPRINT_OUTPUT'))
        self.mprintLayout = QVBoxLayout()
        self.normalOutput = QTextEdit()
        self.normalOutput.setReadOnly(True)
        #self.normalOutput.setStyleSheet('background: #273747; border: none; padding: 10px;')
        self.mprintLayout.addWidget(self.normalOutput)
        self.mprintTab.setLayout(self.mprintLayout)

    def addNormalText(self, data):
        self.normalOutput.clear()
        self.normalOutput.insertHtml(data)
        self.setCurrentIndex(0)

    def addDumpOutput(self):
        self.mdumpTab = QWidget()
        self.addTab(self.mdumpTab, QIcon(':/icons/assets/save_as.png'), self.moderat.MString('MSCRIPTING_MDUMP_OUTPUT'))
        self.mdumpLayout = QVBoxLayout()
        self.mdumpOutput = QTableWidget()
        self.mdumpOutput.setFocusPolicy(Qt.NoFocus)
        self.mdumpOutput.setAutoFillBackground(False)
        self.mdumpOutput.setFrameShadow(QFrame.Plain)
        self.mdumpOutput.setEditTriggers(QAbstractItemView.NoEditTriggers)
        self.mdumpOutput.setDragDropOverwriteMode(False)
        self.mdumpOutput.setAlternatingRowColors(True)
        self.mdumpOutput.setSelectionMode(QAbstractItemView.ExtendedSelection)
        self.mdumpOutput.setSelectionBehavior(QAbstractItemView.SelectRows)
        self.mdumpOutput.setTextElideMode(Qt.ElideMiddle)
        self.mdumpOutput.setShowGrid(False)
        self.mdumpOutput.setGridStyle(Qt.NoPen)
        self.mdumpOutput.setWordWrap(False)
        self.mdumpOutput.setCornerButtonEnabled(False)
        self.mdumpOutput.setObjectName("mdumpOutput")
        self.mdumpOutput.setColumnCount(3)
        self.mdumpOutput.setRowCount(0)
        item = QTableWidgetItem(self.moderat.MString('MSCIPRINT_TIME'))
        self.mdumpOutput.setHorizontalHeaderItem(0, item)
        item = QTableWidgetItem(self.moderat.MString('MSCIPRINT_LENGTH'))
        self.mdumpOutput.setHorizontalHeaderItem(1, item)
        item = QTableWidgetItem(self.moderat.MString('MSCIPRINT_PATH'))
        self.mdumpOutput.setHorizontalHeaderItem(2, item)
        self.mdumpOutput.horizontalHeader().setCascadingSectionResizes(True)
        self.mdumpOutput.horizontalHeader().setDefaultSectionSize(200)
        self.mdumpOutput.horizontalHeader().setSortIndicatorShown(False)
        self.mdumpOutput.horizontalHeader().setStretchLastSection(True)
        self.mdumpOutput.verticalHeader().setVisible(False)
        self.mdumpLayout.addWidget(self.mdumpOutput)
        self.mdumpTab.setLayout(self.mdumpLayout)

    def addDumpFiles(self, data):
        self.mdumpOutput.clearContents()
        if type(data) is dict:
            self.mdumpOutput.setRowCount(len(data))
            for ind, key in enumerate(data.keys()):
                # Time Item
                item = QTableWidgetItem(data[key]['time'])
                self.mdumpOutput.setItem(ind, 0, item)

                # Content Length
                item = QTableWidgetItem(str(data[key]['length']))
                self.mdumpOutput.setItem(ind, 1, item)

                # File Path
                item = QTableWidgetItem(data[key]['path'])
                self.mdumpOutput.setItem(ind, 2, item)
        self.addNormalText('{} {}'.format(len(data.keys()), self.moderat.MString('MSCIPTING')))
        self.setCurrentIndex(1)