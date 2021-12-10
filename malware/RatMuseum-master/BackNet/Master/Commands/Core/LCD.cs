using System;
using System.Collections.Generic;
using System.IO;
using Master.AdvancedConsole;

namespace Master.Commands.Core
{
    internal class LCD : IMasterCommand
    {
        public string name { get; set; } = "lcd";

        public string description { get; set; } = "Change the local current working directory";

        public bool isLocal { get; set; } = true;

        public List<string> validArguments { get; set; } = new List<string>()
        {
            "?:[directory]"
        };

        public void Process(List<string> args)
        {
            if (Directory.Exists(args[0]))
            {
                Directory.SetCurrentDirectory(args[0]);
                Console.WriteLine($"lcwd => {Directory.GetCurrentDirectory()}");
            }
            else
            {
                ColorTools.WriteCommandError("No such directory");
            }
        }
    }
}
