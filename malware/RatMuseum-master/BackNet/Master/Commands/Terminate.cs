using Master.Commands.Core;
using Shared;
using System.Collections.Generic;

namespace Master.Commands
{
    internal class Terminate : IMasterCommand
    {
        public string name { get; } = "terminate";

        public string description { get; } = "Close the connection and exit the slave application";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = null;

        public void Process(List<string> args) => throw new ExitException();
    }
}
