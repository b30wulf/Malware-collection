from PyQt4.QtGui import *
from PyQt4.QtCore import *
from list_ui import Ui_Form


class listPopup(QWidget, Ui_Form):

    def __init__(self, parent, plugins):
        QWidget.__init__(self)
        self.setupUi(self)

        self.mscript = parent
        self.plugins = plugins
        self.filter = ''

        self.init_plugins_list()

        self.pluginsList.clicked.connect(self.plugin_clicked)
        self.pluginsList.doubleClicked.connect(self.plugin_doubleclicked)
        self.searchLine.textChanged.connect(self.filter_plugins)

    def init_plugins_list(self):
        self.pluginsList.clear()
        for ind, key in enumerate(sorted(self.plugins.keys())):
            if self.filter in key:
                self.pluginsList.insertItem(ind, key)

    def plugin_clicked(self):
        curr_key = str(self.pluginsList.currentItem().text())
        if self.plugins.has_key(curr_key):
            detail = '''
            <font color="#1abc9c">Name: </font>{0}<br>
            <font color="#1abc9c">Type: </font>{1}<br>
            <font color="#1abc9c">Description: </font>{2}<br>
            '''.format(
                curr_key,
                self.plugins[curr_key]['type'],
                self.plugins[curr_key]['description'],
            )
            self.detailsText.setHtml(detail)

    def plugin_doubleclicked(self):
        curr_key = str(self.pluginsList.currentItem().text())
        if self.plugins.has_key(curr_key):
            self.mscript.insert_plugin(curr_key)

    def filter_plugins(self):
        filter = str(self.searchLine.text())
        self.filter = filter
        self.init_plugins_list()