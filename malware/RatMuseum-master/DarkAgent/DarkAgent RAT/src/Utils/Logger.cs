using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_RAT.src.Utils
{
    class Logger
    {
        private static List<LogInfo> _Logs = new List<LogInfo>();
        public static void AddLog(LogInfo log)
        {
            try
            {
                _Logs.Add(log);

                string[] str = new string[4];
                str[0] = LogCount.ToString();
                str[1] = log.Event;
                str[2] = log.Action;
                str[3] = log.Date;
                ListViewItem itm = new ListViewItem(str, 0);
                settings.MainForm.listView1.Items.Add(itm);
            }catch{}
        }

        public static int LogCount
        {
            get { return _Logs.Count; }
        }
    }

    public class LogInfo
    {
        public int Nr { get; set; }
        public string Event { get; set; }
        public string Action { get; set; }
        public string Date { get; protected set; }

        public LogInfo(string Event, string Action)
        {
            this.Nr = Logger.LogCount;
            this.Event = Event;
            this.Action = Action;
            this.Date = DateTime.Now.ToString();
        }
    }
}
