Builder folder structure:

- Tools - packaging utilities
  - WhiteJoeBuild.exe - used for inserting strings into DLLs and DLLs into EXEs
  - mystic.exe - cryptor for the final file 'ring3' version   
  - builder.exe - WhiteJoeBuild.exe GUI for inserting URL strings into locker.dll
  
- SrcDir - source files folder for builder process. 
  Has to contain:
  - locker.dll - working DLL for the BootKit and for Ring3 version  
  - locker.exe - ring3 version of the Locker. locker.dll.wjb_out goes into it - extracts the Locker into the memory.

- OutDir - Results folder. Content of the folder is cleared at each start of the build process.
  Build results are stored here:
  - bootkit-locker.dll - BootKit file with embedded strings
  - ring3-locker.exe - ring3 version file with embedded bootkit-locker.dll
  - mystic-ring3-locker.exe - processed by Mystic.exe 'ring3-locker.exe' file
    
    
RunBuild.bat - batch file to start the build process.

How to make a new build:
- run RunBuild.bat

- the batch file will execute Tools\builder.exe and you'll be prompted to supply the following data:
  - enter the list of URL separated by 'spaces' 
  - enter URL suffix that will be added to each URL
  - click "готово" button to complete embedding 
  - click "Cancel" to finish the process
  
- the build process will continue after this.
- once the build is finished press 'Enter' to close the CMD console.  

All the built files can be found in OutDir folder