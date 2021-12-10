using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_MessageBox : ReceiveBasePacket
    {
        public R_MessageBox(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string Message;
        private string Title;
        private byte Icon;
        private byte Buttons;
        public override void Read()
        {
            Message = ReadString();
            Title = ReadString();
            Icon = ReadByte();
            Buttons = ReadByte();
        }

        public override void Run()
        {
            MessageBoxButtons _buttons;
            MessageBoxIcon _icon;

            switch (Buttons)
            {
                case 0:
                    _buttons = MessageBoxButtons.AbortRetryIgnore;
                    break;
                case 1:
                    _buttons = MessageBoxButtons.OK;
                    break;
                case 2:
                    _buttons = MessageBoxButtons.OKCancel;
                    break;
                case 3:
                    _buttons = MessageBoxButtons.RetryCancel;
                    break;
                case 4:
                    _buttons = MessageBoxButtons.YesNo;
                    break;
                default:
                    _buttons = MessageBoxButtons.OK;
                    break;
            }

            switch (Icon)
            {
                case 0:
                    _icon = MessageBoxIcon.Asterisk;
                    break;
                case 1:
                    _icon = MessageBoxIcon.Error;
                    break;
                case 2:
                    _icon = MessageBoxIcon.Exclamation;
                    break;
                case 3:
                    _icon = MessageBoxIcon.Hand;
                    break;
                case 4:
                    _icon = MessageBoxIcon.Information;
                    break;
                case 5:
                    _icon = MessageBoxIcon.None;
                    break;
                case 6:
                    _icon = MessageBoxIcon.Question;
                    break;
                case 7:
                    _icon = MessageBoxIcon.Stop;
                    break;
                case 8:
                    _icon = MessageBoxIcon.Warning;
                    break;
                default:
                    _icon = MessageBoxIcon.None;
                    break;
            }

            MessageBox.Show(Message, Title, _buttons, _icon);
        }
    }
}
