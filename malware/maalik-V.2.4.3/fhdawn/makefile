
COMPILER 				:=
ifeq ($(OS),Windows_NT)
	COMPILER += gcc
else
	COMPILER += i686-w64-mingw32-gcc
endif

main:
	$(COMPILER) main.c sys.c connect.c fhdawn.c network.c LoadLibraryR.c GetProcAddressR.c -o Fhdawn.exe -lws2_32 -static -liphlpapi -m32 -mwindows -lpsapi -lwininet -lshlwapi -MD -s -O3