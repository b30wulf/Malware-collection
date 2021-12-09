<h1 align="center">
  Black Vision<br>
  <img src="https://github.com/lynxmk/blackvision/blob/master/example.gif">
</h1>


**Buy me a Coffee (BTC) : 35F5tgCydWo8LPvfT3zHVBKSVY2vCcLtAL**

**Command line Remote Access tool targeting Windows Systems.**

###### Download Maxmind GeoIP2 Database from here ; [LINK](https://dev.maxmind.com/geoip/geoip2/geolite2/)
###### Extract the file under blackvision/
## How to use 

#### Install required modules
```
pip3 install -r requirements.txt
```
#### Run server 
```
git clone https://github.com/lynxmk/blackvision.git
cd blackvision
python3 main.py
```

#### Generate Agent 
```
cd blackvision
python3 generate.py
```

#### Change Host/Port
Open settings.ini. And change host, port.
##### Commands
Command | Purpose
---|---
transfer | Transfer a NON Binary file.
bsendfile | Transfer a Binary File.
kill | Kill the connection
info | View Information of client.
msgbox | Send Messageboxes.
exec | Execute a Command on the remote Machine(s).
exec-file | Execute a file on the remote Machine(s).
wanip | View WAN IP of Remote Machine(s).
hostname | View Hostname of Remote Machine(s).
username | View Username of Remote Machine(s).
monitoroff | Turn of monitor of Remote Machine(s).
monitoron | Turn monitor back on.
cdopen | Eject CDROM of Remote Machine(s).
cdclose | Close CDROM of Remote Machine(s).
playaudio | Play Audio stream on Remote Machine(s).
send | Send commands to 1 client. (NO broadcast)
keydump | DUMP Keystroke buffer (Not added yet)

##### Termux Compatibilty
This app is compatible with Termux. Run it the same way you run it in Terminal.

#### TODO
- [x] Fix sending file to single client. (Fixed)
- [x] Fix multiple clients information saving. (Fixed)
- Add keylogging.
- [x] Fix that `args[3]` does not get sent. (Fixed)
- Fix Broken connection problems.

[Video Example](https://youtu.be/sxfEDJGn-6A)
