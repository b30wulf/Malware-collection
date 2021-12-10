using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uNet2.Channel;
using uNet2.Peer;

namespace uRAT.Server.Controls
{
    public class ConnectionListItem : ListViewItem
    {
        internal Peer AssociatedPeerInternal;
        internal TcpServerChannel AssociatedChannelInternal;

        public Peer AssociatedPeer
        {
            get { return AssociatedPeerInternal; }
        }

        public TcpServerChannel AssociatedChannel
        {
            get { return AssociatedChannelInternal; }
        }

        public ConnectionListItem(Peer associatedPeer, TcpServerChannel associatedChannel)
        {
            AssociatedPeerInternal = associatedPeer;
            AssociatedChannelInternal = associatedChannel;
        }
    }
}
