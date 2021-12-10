# Ransomware
### Disclamer
This repository contains materials that can be potentially damaging or dangerous, these materials are for educational and research purposes only.
I'm not responsible of any malicious use of the tool. If you don't understand what is it don't download the ransomware.

### Dependencies 
To work the tool need OpenSSL to be installed properly (https://www.openssl.org/source/)  
to do it in debian/ubuntu:  
`~$ sudo apt-get install libssl-dev`

### Using the tool
- To encrypt a directory (and sub-directory)  
`~$ bin/main crypt [PathToEncrypt]`
- To decrypt a directory (and sub-directory)  
`~$ bin/main decrypt [PathToDecrypt]`
