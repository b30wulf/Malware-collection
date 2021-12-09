# trochilus

Trochilus, RedLeaves (RAT)

Uploaded to GitHub for those who want to analyze the code.

### References 

* http://blog.jpcert.or.jp/2017/04/redleaves---malware-based-on-open-source-rat.html
* https://github.com/5loyd/trochilus

Trochilus
========

**A fast&free windows remote administration Tool**

Coded in C++ (using VS2010)

Features
---
* Support TCP,UDP,HTTP,HTTPS
* Serilize Protocol
* Support Non-UAC
* Shellcode Extension
* Remote Uninstall
* Singled-Threaded
* File Manager
* Remote Shell
* Download & Execute
* Upload & Execute
* System Information

Compiling
---
	devenv.exe build.sln /rebuild RELEASE

Build
---
	Run build.bat

ToDo
---
* ~~Reverse SOCKS5 Proxy~~
* ~~Support UDP reliable transfer.~~
* Shellcode extension SDK.

Contributing
---
1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
