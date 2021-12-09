This is the Maalik Client (Fhdawn) completely rewritten in C.

#### What's changed? 
- File Upload / Download functions were completely rewritten to support non overlapped socket and are now very much improved.
- Added Reflective DLL Injection over Socket.
- Core commands (ls, dir, cd, pinfo) were ported from C++ to C and much improved.
- Compared to [old Fhdawn](https://github.com/quantumcored/maalik/tree/af34b239512564b226e204275bc661f9699eadd0/fhdawn), This is lightweight and smaller in size.
- Visual Studio 2019, Mingw-w64 support.
- Code can be cross compiled.
- Eternal Blue scanner added in Fhdawn. [Read here.](https://netsec.quantumcored.com/index.php/2020/09/22/writing-a-eternal-blue-ms17-010-network-vulnerability-scanner-in-c-c/)
