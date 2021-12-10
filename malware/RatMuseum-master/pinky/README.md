Uploading a webshell is almost always the next step after exploiting a web vulnerability, but services like Cloudflare and the new generation of firewalls do a really good job preventing attackers to run commands in the target via HTTP or HTTPS. On the other hand, text content filtering and whitelisting applications policies can be easily exploited with a minimum effort and **pinky is a PoC** of that.

### How is pinky different?

First, **pinky** tries to find which function is enabled to run system commands; after finding which php function is the best, **all communication is encrypted**, so even if the Firewall is enabled to read the traffic, it won't be able to determine whether the activity is malicious or not. Also, **pinky is able to communicate through any kind of proxy**. In addition to this, we need to send a Basic Authentication (completely insecure, I know!) to avoid others to communicate with the pinky's agent.

### Installation.

```
$ git clone git@github.com:davidtavarez/pinky.git
Cloning into 'pinky'...
remote: Counting objects: 223, done.
remote: Compressing objects: 100% (79/79), done.
remote: Total 223 (delta 54), reused 72 (delta 27), pack-reused 103
Receiving objects: 100% (223/223), 385.73 KiB | 73.00 KiB/s, done.
Resolving deltas: 100% (101/101), done.

$ cd pinky

$ php pinky.php
        _       _
  _ __ (_)_ __ | | ___   _
 | '_ \| | '_ \| |/ / | | |
 | |_) | | | | |   <| |_| |
 | .__/|_|_| |_|_|\_\__,  |
 |_|                 |___/  v2.0
 The PHP Mini RAT.

 + Author: David Tavarez
 + Twitter: @davidtavarez
 + Website: https://davidtavarez.github.io/

 +[WARNING]------------------------------------------+
 | DEVELOPERS ASSUME NO LIABILITY AND ARE NOT        |
 | RESPONSIBLE FOR ANY MISUSE OR DAMAGE CAUSED BY    |
 | THIS PROGRAM  ¯\_(ツ)_/¯                          |
 +---------------------------------------------------+


 [-] I need a json file containing the settings.

```
### How to use it.

First, exploit the vulnerability found on the target.

Now, we're ready to generate our agent using the **built-in generator** like this:

![pinky v2](https://github.com/davidtavarez/pinky/raw/master/screenshots/pinkyV2_generator_new.png "pinky v2 agent generator")

I'm using [Obfuscator-Class](https://github.com/pH-7/Obfuscator-Class/ "Obfuscator-Class") by [Pierre-Henry Soria](http://ph7s.github.io/ "Pierre-Henry Soria") to obfuscate the agent because results are pretty good.

![pinky v2](https://github.com/davidtavarez/pinky/raw/master/screenshots/pinkyV2_virustotal.png "virus total")

After the agent is generated, we need to upload it into the target machine and paste the URL into the json file created previously. If we want (and we must), use a SOCKS5 proxy, we need to add the settings:

```
{
  "key":"[KEY]",
  "url":"[URL]",
  "login":{
    "username":"[LOGIN]",
    "password":"[PASSWORD]"
  },
  "proxy":{
    "ip":"127.0.0.1",
    "port":9150,
    "type":"SOCKS5"
  },
  "cookies": "[COOKIES]"
}
```

The last step is to upload the agent, open your terminal and then pass the json file as a parameter.

```
$ php pinky.php config.json
```

![pinky v2](https://raw.githubusercontent.com/davidtavarez/pinky/master/screenshots/pinkyV2_openning.png "pinky v2")

![pinky v2](https://raw.githubusercontent.com/davidtavarez/pinky/master/screenshots/pinkyV2_narf.png "pinky v2")

### Contributing.

In order to contribute, please, ***fork this project***, create a new branch from **master** and send me the PR. Also you can contribute adding more pages to the [Wiki](https://github.com/davidtavarez/pinky/wiki "Wiki") :)
