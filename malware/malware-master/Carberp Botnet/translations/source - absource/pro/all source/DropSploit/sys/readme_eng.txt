Keylogger beta version

How to use it:
1) first - install driver.sys driver
The driver intercepts keyboard interrupt INT13 and replaces interrupt handler. Installs its own 'device' on the system and sets a flag(scancode detected) when a scancode is generated. When a flag is received DLL user part send request to the buffer where the scancode is stored. The scancode is extracted and stored in C:\key.log including the name of the active window and process name.
I included drvload.exe to make it easier to install the driver.

2)second - inject DLL in any process
Regardless of the driver installation, keylogger will start up right after being injected into DLL.

I haven't changed the DLL in any way. It's just a test version.

Uncompleted parts and disadvantages:
1) difficulty working with Numpad
2) difficulty displaying keys pressed with 'Shift' or 'Ctrl'. For example "@" is stored in the log as <SHIFT>2 or capital letters as <SHIFT>à, etc.
3) window name module can't obtain an active window name if the process running it is hidden.

In overall it works well
Going forward if you wish to pay some extra ;) i can add a polymorphic engine to DLL file, so it'll add some 'padding' rubbish to the DLL code