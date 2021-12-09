import os

def hostport(host, port):
    try:
        with open("agent/blackvision_c.cpp", "r+") as src_code:
            file_content = src_code.read()
            server_replace = file_content.replace('server.sin_addr.s_addr = inet_addr("127.0.0.1");', 'server.sin_addr.s_addr = inet_addr("'+host+'");')
            port_replace = server_replace.replace('server.sin_port = htons(3567);', 'server.sin_port = htons('+port+');')
            with open("agent/blackvision.cpp", "w") as now_write:
                now_write.write(port_replace)
        return True
    except FileNotFoundError:
        return False