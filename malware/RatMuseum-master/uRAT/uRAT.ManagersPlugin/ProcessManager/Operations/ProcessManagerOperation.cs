using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;
using uNet2.Channel;
using uNet2.Packet;
using uNet2.SocketOperation;
using uRAT.ManagersPlugin.ProcessManager.Packets;

namespace uRAT.ManagersPlugin.ProcessManager.Operations
{
    public class ProcessManagerOperation : SocketOperationBase
    {
        public override int OperationId
        {
            get { return 1; }
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
