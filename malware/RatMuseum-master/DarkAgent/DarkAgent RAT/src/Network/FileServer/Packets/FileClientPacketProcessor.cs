using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive;
using System.Net.Sockets;
using DarkAgent_RAT.src.Utils;
using DarkAgent_RAT.src.Network.FileServer.Packets.Receive;

namespace DarkAgent_RAT.src.Network.FileServer.Packets
{
    class FileClientPacketProcessor
    {
        private static SortedList<short, PacketType> packetList;
        private static SortedList<short, PacketType> OutgoingpacketList;

        public static void Initialize()
        {
            packetList = new SortedList<short, PacketType>();
            OutgoingpacketList = new SortedList<short, PacketType>();
            RegisterPacket(new PacketType("FileTransfer Begin",     0x00, typeof(R_FileTransferBegin)));
            RegisterPacket(new PacketType("FileTransfer Data",      0x01, typeof(R_FileTransferSend)));
            RegisterPacket(new PacketType("FileTransfer Complete",  0x02, typeof(R_FileTransferEnd)));
       }

        private static void RegisterPacket(PacketType packet)
        {
            Logger.AddLog(new LogInfo("New packet registered", "Opcode: " + packet.Opcode.ToString("x4") + " Name: " + packet.Name));
            packetList.Add(packet.Opcode, packet);
        }

        public static Type ProcessPacket(byte[] packet, string IP)
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
