msiexec.exe /i node.msi INSTALLDIR="C:\Tools\NodeJS" /quiet
ping 127.0.0.1 -n 6 > nul
npm i pm2 -g
npm i pm2-windows-service -g
pm2 start server.js
pm2 save