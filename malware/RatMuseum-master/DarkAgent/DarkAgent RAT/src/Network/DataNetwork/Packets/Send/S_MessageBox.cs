using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    public class S_MessageBox : SendBasePacket
    {
        private byte _button;
        private byte _icon;
        private string _message;
        private string _title;
        public S_MessageBox(RatClients client, string message, string title, MessageBoxButtons buttons, MessageBoxIcon icon)
            : base(client)
        {
            _message = message;
            _title = title;

            switch (buttons)
            {
                case MessageBoxButtons.AbortRetryIgnore:
                    _button = 0;
                    break;
                case MessageBoxButtons.OK:
                    _button = 1;
                    break;
                case MessageBoxButtons.OKCancel:
                    _button = 2;
                    break;
                case MessageBoxButtons.RetryCancel:
                    _button = 3;
                    break;
                case MessageBoxButtons.YesNo:
                    _button = 4;
                    break;
                case MessageBoxButtons.YesNoCancel:
                    _button = 5;
                    break;
            }

            if (icon == MessageBoxIcon.Asterisk)
                _icon = 0;
            if (icon == MessageBoxIcon.Error)
                _icon = 1;
            if (icon == MessageBoxIcon.Exclamation)
                _icon = 2;
            if (icon == MessageBoxIcon.Hand)
                _icon = 3;
            if (icon == MessageBoxIcon.Information)
                _icon = 4;
            if (icon == MessageBoxIcon.None)
                _icon = 5;
            if (icon == MessageBoxIcon.Question)
                _icon = 6;
            if (icon == MessageBoxIcon.Stop)
                _icon = 7;
            if (icon == MessageBoxIcon.Warning)
                _icon = 8;
        }
        protected internal override void Write()
        {
            WriteByte(0);
            WriteString(_message);
            WriteString(_title);
            WriteByte(_icon);
            WriteByte(_button);
        }
    }
}