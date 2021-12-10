from ui.log_settings import Ui_Form as LogSettingsUi
from PyQt4.QtGui import *


class LogSettings(QWidget, LogSettingsUi):

    def __init__(self, args):
        QWidget.__init__(self)
        self.setupUi(self)

        self.moderat = args['moderat']
        self.alias = args['alias']
        self.ip_address = args['ip_address']
        self.client = args['client']
        self.kts = args['kts']
        self.kt = args['kt']
        self.ats = args['ats']
        self.at = args['at']
        self.sts = args['sts']
        self.std = args['std']
        self.st = args['st']
        self.no_audio = not not args['audio_device']
        self.p2p = args['p2p']

        title_prefix = self.alias if len(self.alias) > 0 else self.ip_address
        self.setWindowTitle(u'[{}] {}'.format(title_prefix, self.moderat.MString('LOG_SETTINGS_TITLE')))

        # Init UI
        self.init_values()

        # Triggers
        self.setButton.clicked.connect(self.set_values)

    def init_values(self):
        # Keylogger Status
        self.keyloggerGroup.setChecked(self.kts)
        # Keylogger Timer
        self.kTimerLine.setText(str(self.kt))
        # Audio Status
        self.audioGroup.setChecked(self.ats)
        # Audio Timer
        self.aTimerLine.setText(str(self.at))
        # Screenshot Status
        self.screenshotsGroup.setChecked(self.sts)
        # Screenshot Timer
        self.sTimerLine.setText(str(self.st))
        # Screenshot Delay
        self.sDelayLine.setText(str(self.std))

        # Disable Audio Settings if Client Has no Microphone
        if self.no_audio:
            self.audioGroup.setChecked(False)
            self.audioGroup.setDisabled(self.no_audio)

    def get_values(self):
        # Keylogger Status
        kts = self.keyloggerGroup.isChecked()
        # Keylogger Timer
        kt = int(self.kTimerLine.text())
        # Audio Status
        ats = self.audioGroup.isChecked()
        # Audio Timer
        at = int(self.aTimerLine.text())
        # Screenshot Status
        sts = self.screenshotsGroup.isChecked()
        # Screenshot Timer
        st = int(self.sTimerLine.text())
        # Screenshot Delay
        std = int(self.sDelayLine.text())

        return {
            'kts':  kts,
            'kt':   kt,
            'ats':  ats,
            'at':   at,
            'sts':  sts,
            'st':   st,
            'std':  std,
        }

    def set_values(self):
        self.moderat.send_message(self.get_values(),
                                  'setLogSettings',
                                  session_id=self.moderat.session_id,
                                  _to=self.client,
                                  module_id='',
                                  p2p=self.p2p)
        self.close()