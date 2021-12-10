using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_RAT.src.Utils
{
    public class ClipboardBuilder
    {
        private StringBuilder str;

        public ClipboardBuilder()
        {
            str = new StringBuilder();
        }

        public void Write(string data)
        {
            str.Append(data);
        }

        public void WriteLine(string data)
        {
            str.AppendLine(data);
        }

        public bool Finish()
        {
            try
            {
                Clipboard.SetText(str.ToString()); str.Remove(0, str.Length); return true;
            }
            catch { str.Remove(0, str.Length); return false; }
        }
    }
}
