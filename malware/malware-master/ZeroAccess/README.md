
#ZeroAccess

from: [hfiref0x/ZeroAccess](https://github.com/hfiref0x/ZeroAccess)

## Toolkit for ZeroAccess/Sirefef v3

ZeroAccess is an advanced malware family (probably most advanced from all of available), whose first appearance was in the middle of 2009. Initially Win32 kernel mode rootkit, transformed then into user mode toolkit. Uses self made p2p engine for communication (main purpose - download files). Based on modular structure. Survived multiple takedown attempts (they were mostly serving marketing purposes of various so-called security companies/corporations). Has multiple generations of various toolkit modules. This project provide you insights into ZeroAccess v3 code and several instruments to work with ZeroAccess v3 files. Mostly for education purposes.

# Project Contents

**Umikaze - peer list (@ file) decoder**

Processes input file as ZeroAccess peer file, type required for correct port assignation. 
Result is output file with Time and IP+Port pairs as text. 

> **Usage:** zadecode peerlist_filename [type 32 or 64, default 32], for example: zadecode s32 32

**Shigure - payload decryptor**

Processes input as ZeroAccess payload container, attempting to decode it using RC4 and extract Microsoft Cabinet afterthat.

> **Usage:** zadecrypt inputfile [outputfile], for example: zadecrypt 80000000.@ out.bin

**Harusame - payload container verificator**

Verifies if given file is valid container for ZeroAccess. Requires EA to be set at input file. More information about verification algorithm can be found in source.

> **Usage:** zacheck inputfile [mode 32 or 64, default 32], for example: zacheck 80000000.@ 32

**Yuudachi - ZeroAccess p2p network crawler**

GUI application that monitors given p2p botnet network and downloads payload from it. Downloaded files contain  all required information for further verification by zacheck tool. Dumps collected peers in ZeroAccess format so they can be used as bootstrap next. Use x86-32 version for win32 botnet and x64 for win64. For work required proper bootstrap list and read/write access to current directory.

**Murasame - dropper extractor**

Extracts actual bot installation dropper from encrypted resource of high level dropper.

> **Usage:** zaextract inputfile [outputfile] hexkey, for example: zaextract highlvlbot.bin lowlvlbot.bin 0x12345678

# System Requirements

Does not require administrative privileges. Some tools may require read/write access for the their directories. Modern compatible NT version required, Windows XP not supported. For best appearance allow zamon32/zamon64 in firewall.

# Build 

Project comes with full source code written in C. 
In order to build from source you need: Microsoft Visual Studio 2015 U1 and later versions.
 
# Authors

(c) 2016 ZeroAccess Project
