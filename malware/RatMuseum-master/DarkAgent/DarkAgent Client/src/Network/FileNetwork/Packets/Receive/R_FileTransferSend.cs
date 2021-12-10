using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets.Receive
{
    class R_FileTransferSend : ReceiveBasePacket
    {
        public R_FileTransferSend(FileTransferConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private short Id;
        private byte[] fileBytes;
        private int Index;
        public override void Read()
        {
            Id = ReadShort();
            int Temp = ReadShort();
            Index = ReadInteger();
            fileBytes = ReadBytes(Temp);
        }

        public override void Run()
        {
            if(Client.fileTransfer.ContainsKey(Id))
            {
                try
                {
                    if(Client.fileTransfer[Id].FileBytes == null)
                        Client.fileTransfer[Id].FileBytes = new SortedList<int, byte[]>();

                    Client.fileTransfer[Id].FileBytes.Add(Index, fileBytes);
                    Client.fileTransfer[Id].CurFileSize += fileBytes.Length;
                }
                catch
                {
                    //exception in here wouldn't happen... except something goes totally wrong :P
                }
            }
        }
    }
}