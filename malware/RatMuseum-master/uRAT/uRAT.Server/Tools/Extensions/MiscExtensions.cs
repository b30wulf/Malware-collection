using System;
using System.IO;
using System.Linq;
using System.Windows.Forms;

namespace uRAT.Server.Tools.Extensions
{
    public static class MiscExtensions
    {
        public static void FlexibleInvoke<T>(this T ctrl, Action<T> action) where T : Control
        {
            if (ctrl.InvokeRequired)
            {
                ctrl.BeginInvoke(new Action<T, Action<T>>(FlexibleInvoke), ctrl, action);
                return;
            }

            action(ctrl);
        }

        public static string GetFileName(this string path)
        {
            return Path.GetFileName(path);
        }

        public static bool SequenceEquals(this byte[] data1, byte[] data2)
        {
            if (data1.Length != data2.Length)
                return false;
            return !data1.Where((t, i) => t != data2[i]).Any();
        }
    }
}
