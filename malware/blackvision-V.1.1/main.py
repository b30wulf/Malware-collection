from server.core import *
from server.server import *

def main():
    clear()
    print(banner())
    try:
        db = open("GeoLite2-City.mmdb", "r")
        db.close()
    except FileNotFoundError:
        print("[Error]: 'GeoLite2-City.mmdb' missing! Make sure you downloaded the Maxmind GeoIP Database!\n--> https://github.com/lynxmk/blackvision#download-maxmind-geoip2-database-from-here--link")
        exit(True)
        
    Server()

if __name__ == "__main__":
    main()
