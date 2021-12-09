# eda2, Stolich, Win32.Stolich
Open Source Ransomware

Uploaded to GitHub for those who want to analyze the code.


### References

- http://www.securitynewspaper.com/2016/01/25/eda2-open-source-ransomware-code-used-real-life-attacks/



                  _       ___  
                 | |     |__ \ 
          ___  __| | __ _   ) |
         / _ \/ _` |/ _` | / / 
        |  __/ (_| | (_| |/ /_ 
         \___|\__,_|\__,_|____|

It's a ransomware-like file crypter sample which can be modified for specific purposes. It's more extended version of hidden tear.

**Features**

* Uses both RSA and AES algorithms.
* Coordinates with a Command&Control server.
* Uses CSPRNG
* Uses phplibsec
* Encrypted files can be decrypted in decryption program with encryption key.
* Changes desktop background.

**Demonstration Video**

https://www.youtube.com/watch?v=PD16u1Rz2QI

**Workflow**

* Program sends a POST request to the C&C server with pcname and username variables.
* C&C server creates RSA public/private key pair. Sends public key to the program, saves private key inside the Mysql database
* Program creates a random key for AES algorithm
* Program encrypts files with AES algorithm
* Program encrypts AES key with RSA public key and sends it to the C&C server with POST request
* C&C server saves encrypted AES key inside the Mysql Database

**Usage**

* You need to have a web server which runs Php and Mysql. Change this line with your URL

  ```
  string generatorUrl = "http://www.example.com/panel/createkeys.php"; 
  string keySaveUrl = "http://www.example.com/panel/savekey.php"; 
  ```
  
* It uses 2048 as RSA key size. You can change it

  `const int keySize = 2048;`



* Target file extensions can be change. Default list:

  ```
  var validExtensions = new[]{".txt", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".odt", ".jpg", ".png",     ".csv", ".sql", ".mdb", ".sln", ".php", ".asp", ".aspx", ".html", ".xml", ".psd"};
  ```

* Edit your database settings in db.php
* Default login credentials for web panel: username:test password:test
* You can use Hidden Tear's decryption program to decrypt files.

## Legal Warning

While this may be helpful for some, there are significant risks. eda2 may be used only for Educational Purposes. Do not use it as a ransomware! You could go to jail on obstruction of justice charges just for running eda2, even though you are innocent.
