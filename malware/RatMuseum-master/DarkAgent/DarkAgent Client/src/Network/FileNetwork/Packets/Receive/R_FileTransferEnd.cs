using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets.Receive
{
    class R_FileTransferEnd : ReceiveBasePacket
    {
        public R_FileTransferEnd(FileTransferConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private short Id;
        public override void Read()
        {
            Id = ReadShort();
        }

        public override void Run()
        {
            try
            {
                if (Client.fileTransfer.ContainsKey(Id))
                {
                    using (BinaryWriter BW = new BinaryWriter(File.Open(Client.fileTransfer[Id].Destination + Client.fileTransfer[Id].FileName, FileMode.Append)))
                    {
                        for (int i = 0; i < Client.fileTransfer[Id].FileBytes.Count; i++)
                        {
                            //just a check again to see if we received everything..
                            //hopefully we received everything in a sync connection :)
                            if(Client.fileTransfer[Id].FileBytes.ContainsKey(i))
                                BW.Write(Client.fileTransfer[Id].FileBytes[i]);
                        }
                        BW.Close();
                    }
                    Client.fileTransfer.Remove(Id);
                }
            }
            catch
            {
            	//Exception should only happen if we upload too fast our data about the file
            }

        }
    }
}