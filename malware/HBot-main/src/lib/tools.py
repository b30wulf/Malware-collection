import requests, random, base64, math

class IP_Tools:
    def __init__(self):
        self.blacklist_pattern = open('./asset/ip_range.txt', 'r+').read().splitlines()

    def ip_lookup(self, ip: str):
        # businessName, businessWebsite, city, continent, country, countryCode, ipName, ipType, isp, lat, lon, org, query, region, status
        return requests.get(f'https://extreme-ip-lookup.com/json/{ip}').json()
    
    def get_ip_addr(self):
        p1, p2, p3, p4 = random.randrange(0, 255, 1), random.randrange(0, 255, 1), random.randrange(0, 255, 1), random.randrange(0, 255, 1)
        ip_addr = f'{p1}.{p2}.{p3}.{p4}'

        for pattern in self.blacklist_pattern:
            parsed = pattern.split('.')
            
            for i in range(4):
                if parsed[i] == '*':
                    parsed[i] = ip_addr.split('.')[i]
        
            if parsed == ip_addr.split('.'):
                return self.get_ip_addr()

        return ip_addr
    
    # Grr https://stackoverflow.com/questions/5194057/better-way-to-convert-file-sizes-in-python
    def convert_network_size(self, size_bytes):
        if size_bytes == 0:
           return "0B"
        
        size_name = ("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB")
        i = int(math.floor(math.log(size_bytes, 1024)))
        p = math.pow(1024, i)
        s = round(size_bytes / p, 2)
        return "%s %s" % (s, size_name[i])

class Encoder:
    def __init__(self):
        pass

    def base_64(self, data: str):
        return base64.b64encode(data)