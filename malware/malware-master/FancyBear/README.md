# Fancy Bear Source Code 

Uploaded to GitHub for those who want to analyze the code.

This repo contains actual source code found during IR.
The code provides a communication channel for the attacker and infected client. It uses Google's gmail servers to send and receive encoded messages.

### Some artifacts are summorized below
- Comments are in english, with a lot of grammar mistakes
- Subject of an email is: '**piradi nomeri**'. This means Personal Number in Georgian
- It saves files with **detaluri_**timetsamp.dat. 'Detaluri' is also Georgian for "details".
- In the email body it uses the word: "**gamarjoba**". Meaning 'Hello' in  Georgian.


### These are the Gmail account details used, I've verified they once worked (but not anymore!)
- POP3_MAIL_IP = 'pop.gmail.com'  
- POP3_PORT = 995
- POP3_ADDR = 'jassnovember30@gmail.com'
- POP3_PASS = '30Jass11'
- SMTP_MAIL_IP = 'smtp.gmail.com'
- SMTP_PORT = 587
- SMTP_TO_ADDR = 'userdf783@mailtransition.com'
- SMTP_FROM_ADDR = 'ginabetz75@gmail.com'
- SMTP_PASS = '75Gina75'
  
### Command and Control server
- XAS_IP = '104.152.187.66'
- XAS_GATE = '/updates/'

**The code is completely left as found on the original server, including the log files.**

**ESET** has the complete source code of XAgent, read their report here:

* https://www.welivesecurity.com/wp-content/uploads/2016/10/eset-sednit-part1.pdf
* http://www.welivesecurity.com/wp-content/uploads/2016/10/eset-sednit-part-2.pdf
* https://www.welivesecurity.com/wp-content/uploads/2016/10/eset-sednit-part3.pdf

### Other References

- https://assets.documentcloud.org/documents/3461560/Google-Aquarium-Clean.pdf
- https://www.fireeye.com/content/dam/fireeye-www/global/en/current-threats/pdfs/rpt-apt28.pdf
- https://securelist.com/blog/research/72924/sofacy-apt-hits-high-profile-targets-with-updated-toolset/
- https://www.welivesecurity.com/wp-content/uploads/2016/06/visiting_the_bear_den_recon_2016_calvet_campos_dupuy-1.pdf
- http://download.microsoft.com/download/4/4/C/44CDEF0E-7924-4787-A56A-16261691ACE3/Microsoft_Security_Intelligence_Report_Volume_19_English.pdf
- https://download.bitdefender.com/resources/media/materials/white-papers/en/Bitdefender_In-depth_analysis_of_APT28%E2%80%93The_Political_Cyber-Espionage.pdf
- https://www2.fireeye.com/rs/848-DID-242/images/APT28-Center-of-Storm-2017.pdf
- https://www.trendmicro.de/cloud-content/us/pdfs/security-intelligence/white-papers/wp-operation-pawn-storm.pdf
- https://en.wikipedia.org/wiki/Fancy_Bear
