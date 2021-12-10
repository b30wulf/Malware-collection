using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace Epicenter_Client
{
    class FilelistMechanism
    {
        public static string latestList = String.Empty;
        public static void ListContents(string loc)
        {
            try
            {
                string toSend = String.Empty;

                // Seeking the files in the directory
                System.IO.DirectoryInfo dir = new System.IO.DirectoryInfo(loc);
                foreach (System.IO.DirectoryInfo g in dir.GetDirectories())
                    toSend += g.Name + "<%FSEP%>FOLDER<%FSEP%>" + "-<%SEP%>";

                FileInfo[] rgFiles = dir.GetFiles("*.*");
                foreach (FileInfo fi in rgFiles)
                    toSend += fi.Name + "<%FSEP%>FILE<%FSEP%>" + (fi.Length / 1000).ToString() + "KB<%SEP%>";

                latestList = toSend;
            }
            catch
            {
                latestList = "AN ERROR OCCURED<%FSEP%>NONE<%FSEP%>NA";
            }
        }
    }
}
