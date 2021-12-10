using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_ProcessWindowtext : ReceiveBasePacket
    {
        public R_ProcessWindowtext(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private int PID;
        private string WindowText;

        public override void Read()
        {
            PID = ReadInteger();
            WindowText = ReadString();
        }

        public override void Run()
        {
            try
            {
                Process proc = Process.GetProcessById(PID);
                Int32 hWnd = proc.MainWindowHandle.ToInt32();
                if (hWnd > 1)
                    APIs.SetWindowText(hWnd, WindowText);
            }catch{}
        }
    }
}
