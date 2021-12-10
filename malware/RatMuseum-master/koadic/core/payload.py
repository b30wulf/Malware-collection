import string
import threading
import uuid

class Payload(object):
    PAYLOAD_ID = 0
    PAYLOAD_ID_LOCK = threading.Lock()

    def __init__(self, name = "", data = ""):
        self.data = data
        self.name = name

        with Payload.PAYLOAD_ID_LOCK:
            self.id = Payload.PAYLOAD_ID
            Payload.PAYLOAD_ID += 1
