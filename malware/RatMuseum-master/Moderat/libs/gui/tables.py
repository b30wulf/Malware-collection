from PyQt4.QtGui import *
from PyQt4.QtCore import *
import os, datetime, math
from itertools import islice
from socket import inet_aton

from libs import pygeoip

# Initial geo ip database
geo_ip_database = pygeoip.GeoIP(os.path.join('assets', 'GeoIP.dat'))


def chunks(data, SIZE=10000):
    it = iter(data)
    for i in xrange(0, len(data), SIZE):
        yield {k: data[k] for k in islice(it, SIZE)}


class updateClientsTable:
    def __init__(self, moderat):

        self.moderat = moderat
        self.assets = self.moderat.assets
        self.flags = os.path.join(self.assets, 'flags')
        self.moderat.clients = {}
        self.add_tab_counters()

        self.moderat.clientsTable.setMouseTracking(True)
        self.moderat.clientsTable.cellEntered.connect(self.cellHover)

    def cellHover(self, row, column):
        client = str(self.moderat.clientsTable.item(row, 1).text())
        if self.moderat.clients.has_key(client):
            alias = self.moderat.clients[client]['alias']
            key = self.moderat.clients[client]['key']
            moderator = self.moderat.clients[client]['moderator']
            system = self.moderat.clients[client]['os']
            mic_img = os.path.join(self.assets, 'mic-{}.png'.format(
                str(self.moderat.clients[client]['audio_device'])
            ))
            webcam_img = os.path.join(self.assets, 'cam-{}.png'.format(
                str(self.moderat.clients[client]['webcamera_device'])
            ))
            infect_img = os.path.join(self.assets, 'usb-{}.png'.format(
                str(self.moderat.clients[client]['usp'])
            ))
            screen_img = os.path.join(self.assets, 'sts-{}.png'.format(
                str(self.moderat.clients[client]['sts'])
            ))
            audio_img = os.path.join(self.assets, 'ats-{}.png'.format(
                str(self.moderat.clients[client]['ats'])
            ))
            keylog_img = os.path.join(self.assets, 'kts-{}.png'.format(
                str(self.moderat.clients[client]['kts'])
            ))
            self.moderat.clientsTable.setToolTip(u'''
            <p align="center" style="background-color: #34495e;">
            {}
            </p><hr>
            <table>
            <tr>
            <td>{}:</td> <td><font color="#1abc9c">{}</font></td>
            </tr>
            <tr>
            <td>{}:</td> <td><font color="#1abc9c">{}</font></td>
            </tr>
            <tr>
            <td>{}:</td> <td><font color="#1abc9c">{}</font></td>
            </tr>
            <tr>
            <td>{}: </td>
            <td><img src="{}"/>
            <img src="{}"/></td>
            </tr>
            <tr>
            <td>{}: </td>
            <td><img src="{}"/></td>
            </tr>
            <tr>
            <td>{}: </td>
            <td><img src="{}"/>
            <img src="{}"/>
            <img src="{}"/></td>
            </tr>
            <br>
            '''.format(alias, self.moderat.MString('HEADER_ID'), key,
                       self.moderat.MString('HEADER_MODERATOR'), moderator,
                       self.moderat.MString('HEADER_OS'), system,
                       self.moderat.MString('HEADER_DEVICES'), mic_img, webcam_img,
                       self.moderat.MString('HEADER_INFECTION'), infect_img,
                       self.moderat.MString('HEADER_LOGS'), screen_img, audio_img, keylog_img))
        else:
            self.moderat.clientsTable.setToolTip(None)

    def add_tab_counters(self):
        # Online Clients Count
        self.moderat.online_clients_count = QPushButton('0')
        self.moderat.online_clients_count.setProperty('counter', '1')
        self.moderat.online_clients_count.setFocusPolicy(Qt.NoFocus)
        self.moderat.online_clients_count.clicked.connect(lambda: self.moderat.clientsTabs.setCurrentIndex(0))
        self.moderat.clientsTabs.tabBar().setTabButton(0, QTabBar.RightSide, self.moderat.online_clients_count)

        # Offline Clients Count
        self.moderat.offline_clients_count = QPushButton('0')
        self.moderat.offline_clients_count.setProperty('counter', '1')
        self.moderat.offline_clients_count.setFocusPolicy(Qt.NoFocus)
        self.moderat.offline_clients_count.clicked.connect(lambda: self.moderat.clientsTabs.setCurrentIndex(2))
        self.moderat.clientsTabs.tabBar().setTabButton(2, QTabBar.RightSide, self.moderat.offline_clients_count)

        # Direct Clients Count
        self.moderat.direct_clients_count = QPushButton('0')
        self.moderat.direct_clients_count.setProperty('counter', '1')
        self.moderat.direct_clients_count.setFocusPolicy(Qt.NoFocus)
        self.moderat.direct_clients_count.clicked.connect(lambda: self.moderat.clientsTabs.setCurrentIndex(1))
        self.moderat.clientsTabs.tabBar().setTabButton(1, QTabBar.RightSide, self.moderat.direct_clients_count)

    def clean_tables(self):
        self.moderat.clientsTable.setRowCount(0)
        self.moderat.offlineClientsTable.setRowCount(0)
        self.moderat.moderatorsTable.setRowCount(0)
        self.moderat.online_clients_count.setText('0')
        self.moderat.offline_clients_count.setText('0')

    def clean_direct_table(self):
        self.moderat.directClientsTable.setRowCount(0)
        self.moderat.direct_clients_count.setText('0')

    def update_clients(self, data=None):
        if data:
            self.moderat.clients = data['payload']
        online_clients = {}
        offline_clients = {}

        # Filter Clients
        offline_filter_exceptions = ['sts', 'ats', 'kts', 'usp', 'window_title',
                                     'user', 'privileges', 'audio_device', 'webcamera_device']
        for index, key in enumerate(self.moderat.clients):
            filter = 0
            if self.moderat.clients[key]['status']:
                for item in self.moderat.filters.keys():
                    if unicode(self.moderat.filters[item]) not in unicode(self.moderat.clients[key][item]):
                        filter = 1
                        break
                if not filter:
                    online_clients[index] = self.moderat.clients[key]
            else:
                for item in self.moderat.filters.keys():
                    if item in offline_filter_exceptions:
                        pass
                    elif unicode(self.moderat.filters[item]) not in unicode(self.moderat.clients[key][item]):
                        filter = 1
                        break
                if not filter:
                    offline_clients[index] = self.moderat.clients[key]

        # Sort And Split Clients
        cpp = self.moderat.settings.onlineClientsPerPage
        crp = self.moderat.pagination.current_page-1
        clients_keys = sorted(online_clients,
                              key=lambda x: inet_aton(online_clients[x]['ip_address']), reverse=False)
        current_clients = [{key: online_clients[key]} for key in clients_keys[cpp * crp:cpp * crp + cpp]]
        self.moderat.pagination.add_pages(int(math.ceil(len(clients_keys) / float(cpp))))

        # Arange Clients Table
        count = len(online_clients)
        self.moderat.clientsTable.setRowCount(len(current_clients))
        self.moderat.online_clients_count.setText(str(count))

        for index, obj in enumerate(current_clients):

            client = obj.values()[0]

            # add ip address & county flag
            ip_address = client['ip_address']
            item = QTableWidgetItem(ip_address)
            item.setIcon(QIcon(self.get_ip_location(ip_address)))
            self.moderat.clientsTable.setItem(index, 0, item)

            # add socket number
            socket_value = client['key']
            item = QTableWidgetItem(socket_value)
            if socket_value == 'OFFLINE':
                item.setFlags(Qt.ItemIsSelectable | Qt.ItemIsEnabled)
            item.setTextAlignment(Qt.AlignCenter | Qt.AlignVCenter)
            self.moderat.clientsTable.setItem(index, 1, item)

            # add server user
            item = QTableWidgetItem(client['user'])
            if client['privileges'] == '1':
                item.setTextColor(QColor('#3498db'))
            item.setTextAlignment(Qt.AlignCenter | Qt.AlignVCenter)
            self.moderat.clientsTable.setItem(index, 2, item)

            # add alias if avaiable
            alias = client['alias']
            item = QTableWidgetItem(alias)
            self.moderat.clientsTable.setItem(index, 3, item)

            # add active windows title
            item = QTableWidgetItem(client['window_title'])
            item.setTextAlignment(Qt.AlignRight | Qt.AlignVCenter)
            item.setTextColor(QColor('#1abc9c'))
            self.moderat.clientsTable.setItem(index, 4, item)

        # Split And SOrt Offline Clients
        cpp = self.moderat.settings.offlineClientsPerPage
        crp = self.moderat.pagination.current_offline_page - 1
        clients_keys = sorted(offline_clients,
                              key=lambda x: inet_aton(offline_clients[x]['ip_address']), reverse=False)
        current_clients = [{key: offline_clients[key]} for key in clients_keys[cpp * crp:cpp * crp + cpp]]
        self.moderat.pagination.add_offline_pages(int(math.ceil(len(clients_keys) / float(cpp))))

        # Arange Offline Clients
        count = len(offline_clients)
        self.moderat.offlineClientsTable.setRowCount(len(current_clients))
        self.moderat.offline_clients_count.setText(str(count))
        try:
            for index, obj in enumerate(current_clients):
                client = obj.values()[0]

                item = QTableWidgetItem(client['moderator'])
                item.setTextAlignment(Qt.AlignLeft | Qt.AlignVCenter)
                item.setTextColor(QColor('#f1c40f'))
                self.moderat.offlineClientsTable.setItem(index, 0, item)

                item = QTableWidgetItem(client['key'])
                item.setTextAlignment(Qt.AlignLeft | Qt.AlignVCenter)
                self.moderat.offlineClientsTable.setItem(index, 1, item)

                item = QTableWidgetItem(client['alias'])
                item.setTextAlignment(Qt.AlignLeft | Qt.AlignVCenter)
                self.moderat.offlineClientsTable.setItem(index, 2, item)

                item = QTableWidgetItem(client['ip_address'])
                item.setTextAlignment(Qt.AlignLeft | Qt.AlignVCenter)
                item.setIcon(QIcon(self.get_ip_location(client['ip_address'])))
                self.moderat.offlineClientsTable.setItem(index, 3, item)

                item = QTableWidgetItem(self.mdate(client['last_online']))
                item.setTextAlignment(Qt.AlignRight | Qt.AlignVCenter)
                self.moderat.offlineClientsTable.setItem(index, 4, item)

        except RuntimeError:
            pass

    def update_moderators(self, data):
        moderators = data['payload']
        self.moderat.moderatorsTable.setRowCount(len(moderators))

        online_clients_count = 0
        offline_clients_count = 0

        for index, key in enumerate(moderators):

            # add moderator id
            item = QTableWidgetItem(key)
            item.setTextAlignment(Qt.AlignLeft | Qt.AlignVCenter)
            self.moderat.moderatorsTable.setItem(index, 0, item)

            # add online clients count
            item = QTableWidgetItem(str(moderators[key]['online_clients']))
            online_clients_count += int(moderators[key]['online_clients'])
            item.setTextAlignment(Qt.AlignHCenter | Qt.AlignVCenter)
            self.moderat.moderatorsTable.setItem(index, 1, item)

            # add offline clients count
            item = QTableWidgetItem(str(moderators[key]['offline_clients']))
            offline_clients_count += int(moderators[key]['offline_clients'])
            item.setTextAlignment(Qt.AlignHCenter | Qt.AlignVCenter)
            self.moderat.moderatorsTable.setItem(index, 2, item)

            # add privileges
            privileges = moderators[key]['privileges']
            if privileges == 1:
                color = '#9b59b6'
                privileges = self.moderat.MString('MODERATORS_PRIVILEGES_ADMINISTRATOR')
            else:
                color = '#c9f5f7'
                privileges = self.moderat.MString('MODERATORS_PRIVILEGES_MODERATOR')
            item = QTableWidgetItem(privileges)
            item.setTextColor(QColor(color))
            item.setTextAlignment(Qt.AlignHCenter | Qt.AlignVCenter)
            self.moderat.moderatorsTable.setItem(index, 3, item)

            # add moderator status
            status = moderators[key]['status']
            if status == 1:
                style = '#1abc9c'
                text = self.moderat.MString('MODERATOR_ONLINE')
            else:
                style = '#f39c12'
                text = self.moderat.MString('MODERATOR_OFFLINE')
            item = QTableWidgetItem(text)
            item.setTextColor(QColor(style))
            item.setTextAlignment(Qt.AlignHCenter | Qt.AlignVCenter)
            self.moderat.moderatorsTable.setItem(index, 4, item)

            # add moderator last online
            item = QTableWidgetItem(self.mdate(moderators[key]['last_online']))
            item.setTextAlignment(Qt.AlignRight | Qt.AlignVCenter)
            self.moderat.moderatorsTable.setItem(index, 5, item)

        # Count All Clients
        self.moderat.onlineClientsCountLabel.setText(str(online_clients_count))
        self.moderat.offlineClientsCountLabel.setText(str(offline_clients_count))
        if not online_clients_count == 0:
            self.moderat.allClientsProgress.setValue(
                online_clients_count * 100 / (online_clients_count + offline_clients_count) + 1)

    def update_direct_clients(self):
        if self.moderat.directServerRunning:
            count = len(self.moderat.directClients)
            self.moderat.direct_clients_count.setText(str(count))
            self.moderat.directClientsTable.setRowCount(count)
            for ind, value in enumerate(self.moderat.directClients.keys()):
                # IP Address
                ip_address = self.moderat.directClients[value]['ip_address']
                item = QTableWidgetItem(ip_address)
                item.setIcon(QIcon(self.get_ip_location(ip_address)))
                self.moderat.directClientsTable.setItem(ind, 0, item)

                # ID
                item = QTableWidgetItem(value)
                item.setTextAlignment(Qt.AlignHCenter | Qt.AlignVCenter)
                self.moderat.directClientsTable.setItem(ind, 1, item)

                # Mark
                mark = self.moderat.directClients[value]['mark']
                item = QTableWidgetItem(mark)
                item.setTextAlignment(Qt.AlignRight | Qt.AlignVCenter)
                item.setTextColor(QColor('#1abc9c'))
                self.moderat.directClientsTable.setItem(ind, 2, item)

    def get_ip_location(self, ip):
        try:
            country_flag = os.path.join(self.flags, geo_ip_database.country_code_by_addr(ip).lower() + '.png')
            if os.path.exists(country_flag):
                return country_flag
            else:
                return os.path.join(self.flags, 'blank.png')
        except:
            return os.path.join(self.flags, 'blank.png')

    def mdate(self, _datetime):

        month = {
            1: self.moderat.MString('JANUARY'),
            2: self.moderat.MString('FEBRUARY'),
            3: self.moderat.MString('MARCH'),
            4: self.moderat.MString('APRIL'),
            5: self.moderat.MString('MAY'),
            6: self.moderat.MString('JUNE'),
            7: self.moderat.MString('JULY'),
            8: self.moderat.MString('AUGUST'),
            9: self.moderat.MString('SEPTEMBER'),
            10: self.moderat.MString('OCTOBER'),
            11: self.moderat.MString('NOVEMBER'),
            12: self.moderat.MString('DECEMBER'),
        }

        normalized_datetime = datetime.datetime.strptime(_datetime, '%Y-%m-%d %H:%M:%S.%f')
        return u'{date.day} {month} {date.year} ({date.hour}:{date.minute}:{date.second})'.format(
            date=normalized_datetime, month=month[normalized_datetime.month]
        )
