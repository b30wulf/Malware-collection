using Master.Commands.Core;
using System;
using System.Collections.Generic;

namespace Master.Commands
{
    internal class ListProcesses : IMasterCommand
    {
        public string name { get; } = "ps";

        public string description { get; } = "Displays a list of all processes on the remote slave";

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
