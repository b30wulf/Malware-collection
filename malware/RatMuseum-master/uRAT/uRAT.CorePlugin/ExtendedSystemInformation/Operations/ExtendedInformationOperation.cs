using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uNet2.Channel;
using uNet2.Packet;
using uNet2.SocketOperation;

namespace uRAT.CorePlugin.ExtendedSystemInformation.Operations
{
    public class ExtendedInformationOperation : SocketOperationBase
    {
        public override int OperationId
        {
            get { return 2; }
        }

        public override void PacketReceived(IDataPacket packet, IChannel sender)
        {
            
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
