# LaRat
### Lightweight Android Remote Administration Tool
In the world of mobile phone security, Android is the leading platform in malware. There is no limit to the damage that can be caused to a phone, simply by installing an app. LaRat was built PURELY for demonstration of security flaws within Android. This project was built roughly in a week or so of man hours for a project in my Computer Ethics course at Montana State University and was not built for malicuous intentions!

### Version
1.0.0 - RC 1
- Most underlying technology is in place, overall functionality of the application is seemingly finished.
- Web interface is fully functional.
	- Get messages and threads implemented. Can be viewed directly by clicking in notification area!
	- Clients: functional
	- Notifications: functional
- Finished SMS support
- Added 'ClearView', which clears any obnoxious views from the screen
- This is the final application to be displayed during presentation

### Todos
 - Web interface *COMPLETE* (RC1)
 - More task functionality
    - Get messages (display in web interface) ~~IN PROGRESS~~ *COMPLETE* (RC1)
    - Propietary animation *CANCELLED*
    - Screenshot Functionality *RESEARCHING*
    - Camera Access ~~*REMOVED*~~ *ADDED* (RC1)
    - Audio recording *REMOVED* (RC1)
    - Add Google form for passwords *IN PROGRESS*
 - Leverage the Parse callback API to remove all calls to the backend from clients *IN PROGRESS*

### What's included
- web_interface
 	- Web interface, practically drag/drop setup on web server supporting PHP
 	- This is just a basic way to interface with clients! You can implement this in any flavor of app you want! Desktop, web, mobile, etc!
- Android
        - Android application source

###Third Party
LaRat was built with many third party libraries and tools to ease development. Check them out!
- SugarORM (for saving messages locally, currently nonfunctional): http://satyan.github.io/sugar/index.html
- JetStrap (for aid in building Bootstrap interface): https://jetstrap.com/
- db.php (for various database management functions): https://github.com/tschoffelen/db.php

License
----

MIT

Copyright (c) 2015 Cory Forward

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
