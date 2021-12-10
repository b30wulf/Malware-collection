from PyQt4.QtGui import *
from PyQt4.QtCore import *
from libs.moderat.Decorators import *


class FilterWindow(QWidget):
    def __init__(self, parent=None, filters=None):
        QWidget.__init__(self, parent)
        self.moderat = parent
        self.setObjectName('FilterWindow')
        self.filters = filters
        self.popup()

        self.setWindowTitle(self.moderat.MString('SIDEBAR_FILTER'))

    def popup(self):
        layout = QGridLayout(self)

        # add main filters
        main_layout = QGridLayout()
        main_group = QGroupBox()
        main_group.setTitle(self.moderat.MString('FILTER_MAIN_GROUP'))
        # Moderator Filter
        moderator_layout = QHBoxLayout()
        moderator_filter = QLineEdit()
        moderator_filter.setPlaceholderText(self.moderat.MString('FILTER_MODERATOR'))
        moderator_filter.setText(self.filters['moderator'] if self.filters.has_key('moderator') else '')
        moderator_filter.textChanged.connect(lambda: self.set_filter('moderator', str(moderator_filter.text())))
        moderator_layout.addWidget(moderator_filter)
        moderator_layout.setSpacing(0)
        main_layout.addLayout(moderator_layout, 0, 0)

        # IP Address Filter
        ip_address_layout = QHBoxLayout()
        ip_address_filter = QLineEdit()
        ip_address_filter.setPlaceholderText(self.moderat.MString('FILTER_IP_ADDRESS'))
        ip_address_filter.setText(self.filters['ip_address'] if self.filters.has_key('ip_address') else '')
        ip_address_filter.textChanged.connect(lambda: self.set_filter('ip_address', str(ip_address_filter.text())))
        ip_address_layout.addWidget(ip_address_filter)
        ip_address_layout.setSpacing(0)
        main_layout.addLayout(ip_address_layout, 0, 1)

        # Alias Filter
        alias_layout = QHBoxLayout()
        alias_filter = QLineEdit()
        alias_filter.setPlaceholderText(self.moderat.MString('FILTER_ALIAS'))
        alias_filter.setText(self.filters['alias'] if self.filters.has_key('alias') else '')
        alias_filter.textChanged.connect(lambda: self.set_filter('alias', str(alias_filter.text())))
        alias_layout.addWidget(alias_filter)
        alias_layout.setSpacing(0)
        main_layout.addLayout(alias_layout, 1, 0)

        # Alias Filter
        user_layout = QHBoxLayout()
        user_filter = QLineEdit()
        user_filter.setPlaceholderText(self.moderat.MString('FILTER_USER'))
        user_filter.setText(self.filters['user'] if self.filters.has_key('user') else '')
        user_filter.textChanged.connect(lambda: self.set_filter('user', str(user_filter.text())))
        user_layout.addWidget(user_filter)
        user_layout.setSpacing(0)
        main_layout.addLayout(user_layout, 1, 1)

        # Title Filter
        title_layout = QHBoxLayout()
        title_filter = QLineEdit()
        title_filter.setPlaceholderText(self.moderat.MString('FILTER_TITLE'))
        title_filter.setText(self.filters['window_title'] if self.filters.has_key('window_title') else '')
        title_filter.textChanged.connect(lambda: self.set_filter('window_title', str(title_filter.text())))
        title_layout.addWidget(title_filter)
        title_layout.setSpacing(0)
        main_layout.addLayout(title_layout, 2, 0, 1, 2)

        administrator_layout = QHBoxLayout()
        administrator_layout.setSpacing(1)
        administrator_label = QLineEdit(self.moderat.MString('FILTER_ADMINISTRATOR_LABEL'))
        administrator_label.setProperty('filter', '1')
        administrator_label.setReadOnly(True)
        administrator_layout.addWidget(administrator_label)

        administrator_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        administrator_yes_button.setCheckable(True)
        administrator_yes_button.setMinimumSize(QSize(50, 30))
        administrator_yes_button.setChecked(self.filters['privileges'] == '1'
                                            if self.filters.has_key('privileges') else False)
        administrator_yes_button.clicked.connect(
            lambda: (
                administrator_no_button.setChecked(False) if administrator_yes_button.isChecked() else None,
                self.set_filter('privileges', '1' if administrator_yes_button.isChecked() else '')
            )
        )
        administrator_layout.addWidget(administrator_yes_button)

        administrator_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        administrator_no_button.setCheckable(True)
        administrator_no_button.setMinimumSize(QSize(50, 30))
        administrator_no_button.setChecked(self.filters['privileges'] == '0'
                                           if self.filters.has_key('privileges') else False)
        administrator_no_button.clicked.connect(
            lambda: (
                administrator_yes_button.setChecked(False) if administrator_no_button.isChecked() else None,
                self.set_filter('privileges', '0' if administrator_no_button.isChecked() else '')
            )
        )
        line = QFrame()
        line.setFrameShape(QFrame.HLine)
        administrator_layout.addWidget(administrator_no_button)
        main_layout.addWidget(line, 3, 0, 1, 2)
        main_layout.addLayout(administrator_layout, 4, 0, 1, 2)

        # Adjust Main Group
        main_group.setLayout(main_layout)

        # Add Devices filters
        devices_layout = QGridLayout()
        devices_layout.setSpacing(1)
        devices_group = QGroupBox()
        devices_group.setTitle(self.moderat.MString('FILTER_DEVICES_GROUP'))

        microphone_label = QLineEdit(self.moderat.MString('FILTER_MICROPHONE_LABEL'))
        microphone_label.setProperty('filter', '1')
        microphone_label.setReadOnly(True)
        devices_layout.addWidget(microphone_label, 0, 0)

        microphone_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        microphone_yes_button.setCheckable(True)
        microphone_yes_button.setMinimumSize(QSize(50, 30))
        microphone_yes_button.setChecked(self.filters['audio_device'] == 'True' if self.filters.has_key('audio_device') else False)
        microphone_yes_button.clicked.connect(
            lambda: (
                microphone_no_button.setChecked(False) if microphone_yes_button.isChecked() else None,
                self.set_filter('audio_device', 'True' if microphone_yes_button.isChecked() else '')
            )
        )
        devices_layout.addWidget(microphone_yes_button, 0, 1)

        microphone_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        microphone_no_button.setCheckable(True)
        microphone_no_button.setMinimumSize(QSize(50, 30))
        microphone_no_button.setChecked(self.filters['audio_device'] == 'False'
                                 if self.filters.has_key('audio_device') else False)
        microphone_no_button.clicked.connect(
            lambda: (
                microphone_yes_button.setChecked(False) if microphone_no_button.isChecked() else None,
                self.set_filter('audio_device', 'False' if microphone_no_button.isChecked() else '')
            )
        )
        devices_layout.addWidget(microphone_no_button, 0, 2)

        webcamera_label = QLineEdit(self.moderat.MString('FILTER_WEBCAMERA_LABEL'))
        webcamera_label.setProperty('filter', '1')
        webcamera_label.setReadOnly(True)
        devices_layout.addWidget(webcamera_label, 1, 0)

        webcamera_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        webcamera_yes_button.setCheckable(True)
        webcamera_yes_button.setMinimumSize(QSize(50, 30))
        webcamera_yes_button.setChecked(self.filters['webcamera_device'] == 'True' if self.filters.has_key('webcamera_device') else False)
        webcamera_yes_button.clicked.connect(
            lambda: (
                webcamera_no_button.setChecked(False) if webcamera_yes_button.isChecked() else None,
                self.set_filter('webcamera_device', 'True' if webcamera_yes_button.isChecked() else '')
            )
        )
        devices_layout.addWidget(webcamera_yes_button, 1, 1)

        webcamera_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        webcamera_no_button.setCheckable(True)
        webcamera_no_button.setMinimumSize(QSize(50, 30))
        webcamera_no_button.setChecked(self.filters['webcamera_device'] == 'False'
                                 if self.filters.has_key('webcamera_device') else False)
        webcamera_no_button.clicked.connect(
            lambda: (
                webcamera_yes_button.setChecked(False) if webcamera_no_button.isChecked() else None,
                self.set_filter('webcamera_device', 'False' if webcamera_no_button.isChecked() else '')
            )
        )
        devices_layout.addWidget(webcamera_no_button, 1, 2)

        # Adjust Device Group
        devices_group.setLayout(devices_layout)

        # Add Infection filters
        infection_layout = QGridLayout()
        infection_layout.setSpacing(1)
        infection_group = QGroupBox()
        infection_group.setTitle(self.moderat.MString('FILTER_INFECTION_GROUP'))

        usp_label = QLineEdit(self.moderat.MString('FILTER_USP_LABEL'))
        usp_label.setProperty('filter', '1')
        usp_label.setReadOnly(True)
        infection_layout.addWidget(usp_label, 0, 0)

        usp_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        usp_yes_button.setCheckable(True)
        usp_yes_button.setMinimumSize(QSize(50, 30))
        usp_yes_button.setChecked(self.filters['usp'] == 'True' if self.filters.has_key('usp') else False)
        usp_yes_button.clicked.connect(
            lambda: (
                usp_no_button.setChecked(False) if usp_yes_button.isChecked() else None,
                self.set_filter('usp', 'True' if usp_yes_button.isChecked() else '')
            )
        )
        infection_layout.addWidget(usp_yes_button, 0, 1)

        usp_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        usp_no_button.setCheckable(True)
        usp_no_button.setMinimumSize(QSize(50, 30))
        usp_no_button.setChecked(self.filters['usp'] == 'False'
                                 if self.filters.has_key('usp') else False)
        usp_no_button.clicked.connect(
            lambda: (
                usp_yes_button.setChecked(False) if usp_no_button.isChecked() else None,
                self.set_filter('usp', 'False' if usp_no_button.isChecked() else '')
            )
        )
        infection_layout.addWidget(usp_no_button, 0, 2)

        # Adjust Infection Group
        infection_group.setLayout(infection_layout)

        # Add Control filters
        control_layout = QGridLayout()
        control_layout.setSpacing(1)
        control_group = QGroupBox()
        control_group.setTitle(self.moderat.MString('FILTER_CONTROL_GROUP'))

        screenshot_label = QLineEdit(self.moderat.MString('FILTER_SCREENSHOT_LABEL'))
        screenshot_label.setProperty('filter', '1')
        screenshot_label.setReadOnly(True)
        control_layout.addWidget(screenshot_label, 0, 0)

        screenshot_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        screenshot_yes_button.setCheckable(True)
        screenshot_yes_button.setMinimumSize(QSize(50, 30))
        screenshot_yes_button.setChecked(self.filters['sts'] == 'True'
                                         if self.filters.has_key('sts') else False)
        screenshot_yes_button.clicked.connect(
            lambda: (
                screenshot_no_button.setChecked(False) if screenshot_yes_button.isChecked() else None,
                self.set_filter('sts', 'True' if screenshot_yes_button.isChecked() else '')
            )
        )
        control_layout.addWidget(screenshot_yes_button, 0, 1)

        screenshot_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        screenshot_no_button.setCheckable(True)
        screenshot_no_button.setMinimumSize(QSize(50, 30))
        screenshot_no_button.setChecked(self.filters['sts'] == 'False'
                                        if self.filters.has_key('sts') else False)
        screenshot_no_button.clicked.connect(
            lambda: (
                screenshot_yes_button.setChecked(False) if screenshot_no_button.isChecked() else None,
                self.set_filter('sts', 'False' if screenshot_no_button.isChecked() else '')
            )
        )
        control_layout.addWidget(screenshot_no_button, 0, 2)

        keylogger_label = QLineEdit(self.moderat.MString('FILTER_KEYLOGGER_LABEL'))
        keylogger_label.setProperty('filter', '1')
        keylogger_label.setReadOnly(True)
        control_layout.addWidget(keylogger_label, 1, 0)

        keylogger_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        keylogger_yes_button.setCheckable(True)
        keylogger_yes_button.setMinimumSize(QSize(50, 30))
        keylogger_yes_button.setChecked(self.filters['kts'] == 'True'
                                        if self.filters.has_key('kts') else False)
        keylogger_yes_button.clicked.connect(
            lambda: (
                keylogger_no_button.setChecked(False) if keylogger_yes_button.isChecked() else None,
                self.set_filter('kts', 'True' if keylogger_yes_button.isChecked() else '')
            )
        )
        control_layout.addWidget(keylogger_yes_button, 1, 1)

        keylogger_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        keylogger_no_button.setCheckable(True)
        keylogger_no_button.setMinimumSize(QSize(50, 30))
        keylogger_no_button.setChecked(self.filters['kts'] == 'False'
                                       if self.filters.has_key('kts') else False)
        keylogger_no_button.clicked.connect(
            lambda: (
                keylogger_yes_button.setChecked(False) if keylogger_no_button.isChecked() else None,
                self.set_filter('kts', 'False' if keylogger_no_button.isChecked() else '')
            )
        )
        control_layout.addWidget(keylogger_no_button, 1, 2)

        audio_label = QLineEdit(self.moderat.MString('FILTER_AUDIO_LABEL'))
        audio_label.setProperty('filter', '1')
        audio_label.setReadOnly(True)
        control_layout.addWidget(audio_label, 2, 0)

        audio_yes_button = QPushButton(self.moderat.MString('FILTER_YES'))
        audio_yes_button.setCheckable(True)
        audio_yes_button.setMinimumSize(QSize(50, 30))
        audio_yes_button.setChecked(self.filters['ats'] == 'True'
                                    if self.filters.has_key('ats') else False)
        audio_yes_button.clicked.connect(
            lambda: (
                audio_no_button.setChecked(False) if audio_yes_button.isChecked() else None,
                self.set_filter('ats', 'True' if audio_yes_button.isChecked() else '')
            )
        )
        control_layout.addWidget(audio_yes_button, 2, 1)

        audio_no_button = QPushButton(self.moderat.MString('FILTER_NO'))
        audio_no_button.setCheckable(True)
        audio_no_button.setMinimumSize(QSize(50, 30))
        audio_no_button.setChecked(self.filters['ats'] == 'False'
                                   if self.filters.has_key('ats') else False)
        audio_no_button.clicked.connect(
            lambda: (
                audio_yes_button.setChecked(False) if audio_no_button.isChecked() else None,
                self.set_filter('ats', 'False' if audio_no_button.isChecked() else '')
            )
        )
        control_layout.addWidget(audio_no_button, 2, 2)

        # Adjust Infection Group
        control_group.setLayout(control_layout)

        # All in One
        layout.addWidget(main_group)
        layout.addWidget(devices_group)
        layout.addWidget(infection_group)
        layout.addWidget(control_group)

        # adjust the margins or you will get an invisible, unintended border
        layout.setContentsMargins(0, 0, 0, 0)

        # need to set the layout
        self.setLayout(layout)
        self.adjustSize()

        # tag this widget as a popup
        self.setWindowFlags(Qt.Window)
        # calculate the bottom right point from the parents rectangle
        point = self.moderat.rect().bottomRight()
        # map that point as a global position
        global_point = self.moderat.mapToGlobal(point)
        self.move(global_point - QPoint(self.width()/2, self.height()/2))

    @update_clients
    def set_filter(self, key, value):
        if len(value) == 0:
            del self.filters[key]
        else:
            self.filters[key] = value
