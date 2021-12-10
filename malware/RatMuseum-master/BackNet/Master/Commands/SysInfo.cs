using Master.Commands.Core;
using System;
using System.Collections.Generic;

namespace Master.Commands
{
    internal class SysInfo : IMasterCommand
    {
        public string name { get; } = "sysinfo";

        public string description { get; } = "Display the remote system's informations";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = null;

        public void Process(List<string> args)
        {
            var data = "";
            while (data != "{end}")
            {
                if (data != "")
                    Console.WriteLine(data);
                data = MasterCommandsManager.networkManager.ReadLine();
            }
        }
    }
}
