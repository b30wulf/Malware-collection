<p align = "center">
  <img src="https://github.com/quantumcored/maalik/raw/master/maaliklogo.png" height = "290" width = "250">
  </p>

---

# Maalik
Network Pivoting and Post Exploitation Framework.

## Features
---

#### Console Features

- Desktop notification on new session.
- Kill Online session easily.
- Build Maalik Client, Fhdawn easily.
- Configurable values in ``settings.ini`` 
- Root shell.
- Multithreaded, Get multiple sessions.
- Maalik is extremenly easy to use.

---
#### Fhdawn Features
Fhdawn is the maalik client.

Feature | Description
--------|-------------
Stealth | Runs in background, Only writes hidden plaintext file to disk.
Execute / list / delete files and Browse | Full access to all files. Browse the system remotley.
Windows Defender Exclusions | Add Windows Defender Exclusions.
Network Pivoting | Forwards a Port to another Host on the network to forward exploit traffic onto it.
Enable / Disable Firewall | Enable or Disable Windows Firewall, Use full for pivoting scenarios. Firewall is automatically turned off during Pivot attack.
Network Scanner | Discover Hosts in the subnet.
Port Scanner | Scans discovered hosts for [common ports](https://github.com/quantumcored/maalik/blob/master/common_ports).
MS17-10 Network Scanner | Scans the network for Hosts vulnerable to MS17-10, The Eternal Blue.
Automatic Eternal Blue | Automatically runs metasploit using rc file to potentially exploit Port 445.
Reverse Shell | Stable Reverse Shell, Commands executed as ``cmd.exe /c <your input>``.
File upload / download | Upload or Download Files.
Reflective DLL Injection | Reflective DLL Injection into any process.
Screenshot | Take screenshot (``bmp=>png``).
GeoLocation | Geolocate Fhdawn.
SAM Dump | Dumps SAM and SYSTEM files to disk, Downloads and dumps them using ``samdump2``.


##### Dynamic Payload System (DPS). 
Executes 'Payloads' in Memory using Reflective DLL Injection.
The Payload is a 32 bit Reflective DLL, That carries out tasks after successful Injection.
DLL output is written to a TEXT file named ``output.png`` which is used to smuggle output back to server, And also give the DLL Payload commands.

Payload | Description
--------|------------
(DPS) Reverse Shell | Netcat Reverse shell.
(DPS) Administrator Prompt Trigger | Forcefully attempt to Execute an Application as Administrator.
(DPS) Chrome Password Recovery | Dumps Saved Google Chrome passwords. (Does not work on latest version) :shipit:
(DPS) In Memory Meterpreter | Execute Metasploit C Shellcode.
(DPS) Keystroke logging | Log keystrokes.
(DPS) Capture Mic Input | Record Mic.
(DPS) Registry Persistence | Add any application to startup using registry keys.

##### CMD Post Exploitation Commands (CMD)
These are cmd commands that are useful in a post exploit situation. Not listed here.
- List users.
- View full System Information.
- View all Drivers.
- Get list of running processes.
- Get Available Drive Letters.
- Get all envoironment variables.
- Displays information about sessions on a Remote Desktop Session Host server.
- Acronym for 'netsh wlan show profiles'.
- Enable Remote Desktop.
- Disable Remote Desktop.
- Disable Firewall.
- Enable firewall.
- Forward a PORT on the Remote PC.
- Reset all forwarded Ports.

---

![img](https://github.com/quantumcored/maalik/raw/master/maalikp.png)

---

#### Install : 
Do not clone the repository.
- Recommended OS is Kali Linux.
- Download and extract the [Latest Release](https://github.com/quantumcored/maalik/releases/)
```
cd maalik
sudo chmod +x install.sh
sudo ./install.sh
```
- [Click here for Maalik Usage guide.](https://netsec.quantumcored.com/index.php/2020/09/21/maalik-framework/)

This Project is active in developement. There may be Errors and bugs that I may have missed. If you find any, Or you have an idea or suggestion. Please submit [here](https://github.com/quantumcored/maalik/issues).

#### TODO 
- Add Route pivoting.
- Add Reflective Exploits.

#### Developer
Hi my name's [Fahad](https://github.com/quantumcore).
You may contact me, on [Discord](https://discordapp.com/invite/8snh7nx) or [My Website](https://quantumcored.com/)
Contact discord for fastest response.

#### LICENSE
[VIEW LICENSE](https://github.com/quantumcored/maalik/blob/master/LICENSE) 

The Developer is not responsible for any misuse of Damage caused by the program. This is created only to innovate NetSec and **YOU**. :point_left:

#### Donate
Help me with my future projects. Thank you.
[Donate with Crypto](https://commerce.coinbase.com/checkout/cebcb394-f73e-4990-98b9-b3fdd852358f)
