from PyQt4.QtGui import *
import os


class ModeratTrayIcon(QSystemTrayIcon):

    def __init__(self, parent=None):
        QSystemTrayIcon.__init__(self, QIcon(':/icons/assets/logo.png'), parent)
        self.moderat = parent
        menu = QMenu()
        menu.setStyleSheet('''background-color: #2c3e50;color: #c9f5f7;''')
        menu.addAction(QIcon(':/icons/assets/close.png'), self.moderat.MString('TRAY_CLOSE'), lambda: os._exit(0))
        self.setContextMenu(menu)
        self.show()

    def info(self, header, message=''):
        self.showMessage(header, message, self.Information)

    def warning(self, header, message=''):
        self.showMessage(header, message, self.Warning)

    def critical(self, header, message=''):
        self.showMessage(header, message, self.Critical)
