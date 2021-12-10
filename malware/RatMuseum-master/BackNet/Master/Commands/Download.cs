using Master.AdvancedConsole;
using Master.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;

namespace Master.Commands
{
    internal class Download : IMasterCommand
    {
        public string name { get; } = "download";

        public string description { get; } = "Download a file from the slave";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "?:[remoteFileName] ?*:[localFileName]"
        };

        public void Process(List<string> args)
        {
            var initResult = MasterCommandsManager.networkManager.ReadLine();
            if (initResult != "OK")
            {
                ColorTools.WriteCommandError(initResult == "NotFound" ? "The remote file doesn't exist" : "An IO exception occured");
                return;
            }

            var path = args[1];
            ColorTools.WriteCommandMessage($"Starting download of file '{args[0]}' from the slave");

            try
            {
                using (var fs = new FileStream(path, FileMode.Create))
                {
                    MasterCommandsManager.networkManager.NetworkStreamToStream(fs);
                }

                ColorTools.WriteCommandSuccess("File successfully downloaded from the slave");
            }
            catch (Exception)
            {
                // Delete the partially created file
                File.Delete(path);
                ColorTools.WriteCommandError("An error occured");
            }
        }
    }
}
