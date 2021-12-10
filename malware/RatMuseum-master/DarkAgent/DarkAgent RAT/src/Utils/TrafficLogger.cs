using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_RAT.src.Utils
{
    class TrafficLogger
    {
        public static List<TrafficInfo> _TrafficLogs = new List<TrafficInfo>();
        public static void AddLog(TrafficInfo log)
        {
            try
            {
                _TrafficLogs.Add(log);

                string[] str = new string[6];
                str[0] = LogCount.ToString();
                str[1] = log.Id.ToString();
                str[2] = log.type;
                str[3] = log.Length.ToString();
                str[4] = log.RemoteAddress;
                str[5] = log.Date;
                ListViewItem itm = new ListViewItem(str, 0);
                settings.MainForm.listView2.Items.Add(itm);
            }catch{}
        }

        public static int LogCount
        {
            get { return _TrafficLogs.Count; }
        }
    }

    public class TrafficInfo
    {
        public int Nr { get; set; }
        public int Id { get; set; }
        public string type { get; set; }
        public string Length { get; protected set; }
        public string RemoteAddress { get; protected set; }
        public string Date { get; protected set; }
        public byte[] PacketBytes { get; set; }

        public TrafficInfo(int Id, string type, string length, string RemoteAddress, byte[] PacketBytes)
        {
            this.Nr = Logger.LogCount;
            this.Id = Id;
            this.type = type;
            this.Length = length;
            this.Date = DateTime.Now.ToString();
            this.RemoteAddress = RemoteAddress;
            this.PacketBytes = PacketBytes;
        }
    }
}
