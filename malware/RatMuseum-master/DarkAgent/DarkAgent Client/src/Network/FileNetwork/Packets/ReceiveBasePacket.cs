using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets
{
    public abstract class ReceiveBasePacket
    {
        private byte[] _Packet;
        private int _Offset;

        private FileTransferConnect _Client;
        public ReceiveBasePacket(FileTransferConnect client, byte[] packet)
        {
            _Client = client;
            _Packet = packet;
            _Offset = 1;
            Read();
        }

        public int Offset
        {
            get { return _Offset; }
            set { _Offset = value; }
        }

        public int ReadInteger()
        {
            int result = BitConverter.ToInt32(_Packet, _Offset);
            _Offset += 4;
            return result;
        }

        public byte ReadByte()
        {
            byte result = _Packet[_Offset];
            _Offset += 1;
            return result;
        }

        public byte[] ReadBytes(int Length)
        {
            byte[] result = new byte[Length];
            Array.Copy(_Packet, _Offset, result, 0, Length);
            _Offset += Length;
            return result;
        }

        public short ReadShort()
        {
            short result = BitConverter.ToInt16(_Packet, _Offset);
            _Offset += 2;
            return result;
        }

        public double ReadDouble()
        {
            double result = BitConverter.ToDouble(_Packet, _Offset);
            _Offset += 8;
            return result;
        }

        public long ReadLong()
        {
            long result = BitConverter.ToInt64(_Packet, _Offset);
            _Offset += 8;
            return result;
        }

        public string ReadString()
        {
            string result = "";
            try
            {
                result = System.Text.Encoding.Unicode.GetString(_Packet, _Offset, _Packet.Length - _Offset);
                int idx = result.IndexOf((char)0x00);
                if (!(idx == -1))
                {
                    result = result.Substring(0, idx);
                }
                _Offset += (result.Length * 2) + 2;
            }
            catch{}
            return result;
        }

        public FileTransferConnect Client
        {
            get { return _Client; }
        }

        public byte[] Packet
        {
            get { return _Packet; }
        }

        public abstract void Read();
        public abstract void Run();
    }
}
