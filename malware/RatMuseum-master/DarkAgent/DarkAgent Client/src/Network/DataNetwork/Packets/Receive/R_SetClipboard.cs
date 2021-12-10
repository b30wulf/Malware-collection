using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using System.Runtime.InteropServices;
using System.Diagnostics;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_SetClipboard : ReceiveBasePacket
    {
        string data = null;

        public R_SetClipboard(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {
            data = ReadString();
        }

        public override void Run()
        {
            APIs.OpenClipboard(Process.GetCurrentProcess().MainWindowHandle);
            APIs.EmptyClipboard();
            IntPtr iStr = Marshal.StringToHGlobalAnsi(data);
            APIs.SetClipboardData(1, iStr);
            Marshal.FreeHGlobal(iStr);
            APIs.CloseClipboard();
        }
    }
}
