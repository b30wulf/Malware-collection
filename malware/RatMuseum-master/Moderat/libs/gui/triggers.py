class ModeratTriggers:

    def __init__(self, moderat):

        self.moderat = moderat

        # Connect & Disconnect triggers
        self.moderat.actionConnect.triggered.connect(self.moderat.on_connect_to_server)
        self.moderat.actionDisconnect.triggered.connect(self.moderat.on_moderator_connect_fail)
        self.moderat.actionStartServer.triggered.connect(self.moderat.on_server_started)
        self.moderat.actionStopServer.triggered.connect(self.moderat.on_server_stopped)
        self.moderat.actionSetupFilters.triggered.connect(self.moderat.filter.handlePopup)
        self.moderat.actionRunSettings.triggered.connect(self.moderat.show_settings_window)

        # Online Menu Triggers
        self.moderat.viewLogsButton.clicked.connect(lambda: self.moderat.execute_module(module='MVIEWER'))
        self.moderat.noteButton.clicked.connect(lambda: self.moderat.execute_module(module='MNOTE'))
        self.moderat.logSettingsButton.clicked.connect(self.moderat.set_logs_settings)
        self.moderat.setAliasButton.clicked.connect(self.moderat.set_alias)
        self.moderat.updateSourceButton.clicked.connect(self.moderat.update_source)
        self.moderat.shellButton.clicked.connect(lambda: self.moderat.execute_module(module='MSHELL'))
        self.moderat.explorerButton.clicked.connect(lambda: self.moderat.execute_module(module='MEXPLORER'))
        self.moderat.scriptingButton.clicked.connect(lambda: self.moderat.execute_module(module='MSCRIPTING'))
        self.moderat.screenshotButton.clicked.connect(lambda: self.moderat.execute_module(module='MDESKTOP'))
        self.moderat.webcamButton.clicked.connect(lambda: self.moderat.execute_module(module='MWEBCAM'))

        # Offline Menu Triggers
        self.moderat.viewOfflineLogsButton.clicked.connect(lambda: self.moderat.execute_module(module='logviewer'))
        self.moderat.setOfflineAliasButton.clicked.connect(self.moderat.set_alias)
        self.moderat.removeClientButton.clicked.connect(self.moderat.remove_client)

        # Moderators Menu Triggers
        self.moderat.addModeratorButton.clicked.connect(self.moderat.create_moderator)
        self.moderat.changePasswordButton.clicked.connect(self.moderat.change_moderator_password)
        self.moderat.changePrivilegesButton.clicked.connect(self.moderat.change_moderator_privilege)
        self.moderat.removeModeratorButton.clicked.connect(self.moderat.remove_moderator)
