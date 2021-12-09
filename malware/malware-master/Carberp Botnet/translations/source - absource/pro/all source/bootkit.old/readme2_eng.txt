Loader setup
-----------------
1. The installer analyses the hard disk (\??\PHYSICALDRIVEx): checks partition table,
   calculates the size of unused space before the first partition and after the last one
2. If found a big enough area - the driver code is written into it.
3. The installer reads VBR (Volume Boot Record) code that is located in the first 15 sectors of the boot partition (\Device\HarddiskÕ\PartitionÕ).
4. VBR code is compressed and the loader is added to it, so that when VBR is started it gets the control.
5. The new VBR code that includes the loader overwrites the old VBR code.

Windows 7 disables writing into file system volume sectors on the hard disk, but
it always has ~1MB of unused space before the first partition.
For XP and Vista, if no unused space is found it's possible to use the last sectors of the last partition to store the driver's code.
Hard disk sectors are read and written by opening \??\PHYSICALDRIVEx device using native NT functions: NtCreateFile, NtReadFile, NtWriteFile.

The loader and the driver will be guaranteed to install successfully under the following conditions:
1. operation is performed with admin rights. Also, for Vista and Win7 elevated UAC rights are required.
2. Unrestricted access to open, read and write to the following devices
 \??\PHYSICALDRIVEx è \Device\HarddiskÕ\PartitionÕ

FAQ


> where will my DLLs that are injected into processes be stored?

The last sectors of the system volume.

>if Windows is reinstalled will the rootkit and my DLLs survive?

No, will not survive.
Not many kits can survive Operating System reinstall.
I'm sure it's doable, but it'll substantially increase the cost and I’m not sure it's really required.

> and you were saying there is some really cool method for bootkit?
> like, not using MBR, but something else... could you give more details please?

Simply saying, we do not use MBR, we use OS boot files.
