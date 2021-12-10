from PyQt4.QtGui import *
from PyQt4.QtCore import *
from libs.gui.loading import Loading
import main_ui
import ast
import zlib
from PIL import Image, ImageQt


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
        self.setWindowTitle(u'{}[{}] {}'.format('(P2P)' if self.p2p else '', title_prefix, self.moderat.MString('MDESKTOP_TITLE')))

        self.current_bits = None
        self.addTools()

        self.loading = Loading(self)
        self.loading.hide()

    def addTools(self):
        self.screenshotAction = QAction(self)
        self.screenshotAction.setIcon(QIcon(':/icons/assets/desktop.png'))
        self.screenshotAction.triggered.connect(self.get_screenshot)

        self.saveAction = QAction(self)
        self.saveAction.setIcon(QIcon(':/icons/assets/save_as.png'))
        self.saveAction.triggered.connect(self.save_screenshot)

        self.toolBox = QToolBar(self)
        self.toolBox.setIconSize(QSize(16, 16))
        self.toolBox.addAction(self.screenshotAction)
        self.toolBox.addSeparator()
        self.toolBox.addAction(self.saveAction)
        self.addToolBar(self.toolBox)

    def signal(self, data):
        self.callback(data)

    def get_screenshot(self):
        self.moderat.send_message('getScreen', 'getScreen', session_id=self.moderat.session_id, _to=self.client,
                                  module_id=self.module_id, p2p=self.p2p)
        self.callback = self.on_screenshot_received
        self.loading.show()

    def on_screenshot_received(self, data):
        screen_dict = data['payload']
        try:
            screen_info = ast.literal_eval(screen_dict)
            im = Image.frombuffer('RGB', (int(screen_info['width']), int(screen_info['height'])),
                                  zlib.decompress(screen_info['screenshotbits']), 'raw', 'BGRX', 0, 1)
            screen_bits = im.convert('RGBA')
            self.screenshot = Screenshot(QPixmap.fromImage(ImageQt.ImageQt(screen_bits)).scaled(
                self.size(),
                Qt.KeepAspectRatio, Qt.SmoothTransformation))
            self.setCentralWidget(self.screenshot)
            self.current_bits = screen_bits
            self.loading.hide()
        except SyntaxError:
            pass

    def save_screenshot(self):
        if self.current_bits:
            file_name = QFileDialog.getSaveFileName(self, 'Save file', '', 'Image (*.png)')
            windows_path = str(file_name).replace('/', '\\')
            if file_name:
                self.current_bits.save(windows_path, 'png')

    def resizeEvent(self, event):
        self.loading.resize(event.size())
        event.accept()


class Screenshot(QLabel):
    def __init__(self, img):
        super(Screenshot, self).__init__()
        self.setFrameStyle(QFrame.StyledPanel)
        self.pixmap = QPixmap(img)

    def paintEvent(self, event):
        size = self.size()
        painter = QPainter(self)
        point = QPoint(0, 0)
        scaledPix = self.pixmap.scaled(size, Qt.KeepAspectRatio, transformMode=Qt.SmoothTransformation)
        painter.drawPixmap(point, scaledPix)
