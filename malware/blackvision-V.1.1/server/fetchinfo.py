import geoip2.database

# Is it wierd that Function names look cool with Capital?
database_path = "GeoLite2-City.mmdb"

def GetINFO(sock, filename):

    def SendData(data):
        data = data.encode()
        try:
            sock.send(data)
        except Exception as serror:
            print("[ERROR] " + str(serror))
    
    def writetofile():
        with open(filename, "w+") as infofile:
            infofile.write("\n[+] WAN IP : " +str(wanip)) # Save Wan IP to File
            infofile.write("\n"+str(osinfo)) # Save OS info to file
            infofile.write("\n[+] ISO Code : " + str(ISO_CODE))
            infofile.write("\n[+] Country : "+ str(country))
            infofile.write("\n[+] Postal Code : "+ str(pstlcode))
            infofile.write("\n[+] Region : " + str(reigon))
            infofile.write("\n[+] City : " + str(city))
            infofile.write("\n[+] Location : " + str(location))
            infofile.close()
            print("[+] Done!")

    database = geoip2.database.Reader(database_path)
    try:
        SendData("wanip\n")
        wanip = sock.recv(1024).decode()
        SendData("sysinfo\n")
        osinfo = sock.recv(1024).decode()
        ip_info = database.city(wanip)
        ISO_CODE = ip_info.country.iso_code
        country = ip_info.country.name
        pstlcode = ip_info.postal.code
        reigon = ip_info.subdivisions.most_specific.name
        city = ip_info.city.name
        location = str(ip_info.location.latitude) + " " + str(ip_info.location.longitude)

        try:
            file = open(filename, "r")
            print("[*] Information exists, Updating...")
            file.close()
            writetofile()
        except FileNotFoundError:
            print("[+] Saving Information..")
            writetofile()

    except Exception as e:
        print("[x] Somethings wrong.... Failed to get Information..")
        print("[x] Error : " + str(e))
        pass

