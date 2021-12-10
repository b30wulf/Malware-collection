using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_CursorPosition : ReceiveBasePacket
    {
        public R_CursorPosition(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private int X;
        private int Y;


        public const int MOUSEEVENTF_MOVE = 0x1;
        public const int MOUSEEVENTF_LEFTDOWN = 0x02;
        public const int MOUSEEVENTF_LEFTUP = 0x04;
        public const int MOUSEEVENTF_RIGHTDOWN = 0x08;
        public const int MOUSEEVENTF_RIGHTUP = 0x10;
        public const int MOUSEEVENTF_MIDDLEDOWN = 0x20;
        public const int MOUSEEVENTF_MIDDLEUP = 0x40;
        public const int MOUSEEVENTF_XDOWN = 0x80;
        public const int MOUSEEVENTF_XUP = 0x100;
        public const int MOUSEEVENTF_WHEEL = 0x800;

        public override void Read()
        {
            X = ReadInteger();
            Y = ReadInteger();
        }

        public override void Run()
        {
            APIs.SetCursorPos(X, Y);
            APIs.mouse_event(MOUSEEVENTF_LEFTDOWN, X, Y, 0, 0);
            APIs.mouse_event(MOUSEEVENTF_LEFTUP, X, Y, 0, 0);
        }
    }
}
