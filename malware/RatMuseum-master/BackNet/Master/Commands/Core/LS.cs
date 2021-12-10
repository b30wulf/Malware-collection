using System;
using System.Collections.Generic;

namespace Master.Commands.Core
{
    internal class LS : IMasterCommand
    {
        public string name { get; set; } = "ls";

        public string description { get; set; } = "Display the files and folders name of the remote current working directory";

        public bool isLocal { get; set; } = false;

        public List<string> validArguments { get; set; } = null;

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
