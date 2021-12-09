@echo off
echo 拷贝相关文件

taskkill /f /im Trochilus.exe
taskkill /f /im Generator.exe

echo 拷贝控制端内容
copy /Y "%cd%\server\bin\master.dll" "%cd%\bin\bin\master.dll"
copy /Y "%cd%\server\bin\midutils.dll" "%cd%\bin\bin\midutils.dll" 
copy /Y "%cd%\server\bin\Trochilus.exe" "%cd%\bin\bin\Trochilus.exe"

echo 拷贝生成器内容
copy /Y "%cd%\client\bin\Generator.exe" "%cd%\bin\Generator\Generator.exe"
copy /Y "%cd%\client\bin\shell.dll" "%cd%\bin\Generator\bingo\shell.bak"
copy /Y "%cd%\client\bin\body.dll" "%cd%\bin\Generator\bingo\body.dll"

pause
echo 拷贝完毕！