try:
    from SocketServer import ThreadingMixIn
    from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer
except:
    # why is python3 so terrible for backward compatibility?
    from socketserver import ThreadingMixIn
    from http.server import BaseHTTPRequestHandler, HTTPServer

import core.handler
import core.session
import core.loader
import core.payload

import socket
import random
import threading
import os
import ssl
import io
import time
import datetime
import copy

class ThreadedHTTPServer(ThreadingMixIn, HTTPServer):
    pass

class Server(threading.Thread):
    def __init__(self, stager, handler):
        threading.Thread.__init__(self)
        self.daemon = True

        self.loader = core.loader
        self.sessions = []
        self.stager = stager
        self.shell = stager.shell
        self.module = self.shell.state
        self.payload = stager.options.get("_STAGECMD_")
        self.payload_id = core.payload.Payload().id
        self.handler_class = handler
        self.killed = False

        self._create_options()
        self._setup_server()

    def _setup_server(self):
        self.http = ThreadedHTTPServer(('0.0.0.0', int(self.options.get('SRVPORT'))), self.handler_class)
        self.http.timeout = None
        self.http.daemon_threads = True
        self.http.server = self
        self.http.stager = self.stager

        self.is_https = False

        keyt = self.options.get('KEYPATH')
        cert = self.options.get('CERTPATH')

        if cert and cert != "" and keyt and keyt != "":
            self.is_https = True
            cert = os.path.abspath(cert)
            self.http.socket = ssl.wrap_socket(self.http.socket, keyfile=keyt, certfile=cert, server_side = True)

        url = self._build_url()

        self.options.register("URL", url, "url to the stager", hidden=True)

    def _create_options(self):
        self.options = copy.deepcopy(self.stager.options)
        self.options.register("SESSIONKEY", "", "unique key for a session", hidden=True)
        self.options.register("JOBKEY", "", "unique key for a job", hidden=True)

    def print_payload(self):
        self.shell.print_command(self.get_payload().decode())

    def get_session(self, key):
        for session in self.sessions:
            if session.key == key:
                return session

        return None

    def get_payload(self):
        #fixed = []
        #for payload in self.payloads:
        payload = self.payload
        payload = self.loader.apply_options(payload, self.options)
        #payload = payload.replace(b"~URL~", self.options.get("URL").encode())
        #fixed.append(payload)

        return payload

    def _build_url(self):
        hostname = self.options.get("SRVHOST")
        if hostname == '0.0.0.0':
            s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
            try:
                s.connect(('8.8.8.8', 80))
                hostname = s.getsockname()[0]
            finally:
                s.close()

        #self.hostname = "127.0.0.1"
        self.hostname = hostname
        self.port = str(self.options.get("SRVPORT"))

        prefix = "https" if self.is_https else "http"
        url = prefix + "://" + self.hostname + ':' + self.port

        endpoint = self.options.get("FENDPOINT").strip()

        if len(endpoint) > 0:
            url += "/" + endpoint

        return url

    def run(self):

        try:
            self.http.serve_forever()
        except:
            pass

    def shutdown(self):

        # shut down the server/socket
        self.http.shutdown()
        self.http.socket.close()
        self.http.server_close()
        self._Thread__stop()

        # make sure all the threads are killed
        for thread in threading.enumerate():
            if thread.isAlive():
                try:
                    thread._Thread__stop()
                except:
                    pass
