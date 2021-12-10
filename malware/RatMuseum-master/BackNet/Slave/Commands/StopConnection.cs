using Shared;
using System.Collections.Generic;

namespace Slave.Commands
{
    internal class StopConnection : ICommand
    {
        public string name { get; } = "stopconnection";

        public void Process(List<string> args) => throw new ExitException();
    }
}
