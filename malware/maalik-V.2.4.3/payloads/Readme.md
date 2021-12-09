## Payloads
These are 'Payloads' that are executed in Memory using Reflective DLL Injection.

**output.png** is the file used to get output of these payloads. The payloads write output in this Image.
Fhdawn reads the output and sends back to server.

- ncshell - Netcat Basic Reverse shell.
- ReflectiveDll - Standard Reflective DLL, shows messagebox.
- ChromeDump - Dumps Chrome passwords to 'password.txt'.
- elevate - Used to execute Fhdawn as adminstrator.
- msf - Executes metasploit C Shellcode via reflective dll.
- keylogger - Standard GetAsyncKeyState Keylogger.
- miccapture - Records mic.
- persist - Add An application to startup via registry key as ``win32svchostdriver``.
  - ![img](https://github.com/quantumcored/maalik/raw/master/persist.PNG)
