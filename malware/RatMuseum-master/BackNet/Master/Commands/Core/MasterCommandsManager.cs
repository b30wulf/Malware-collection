using Master.AdvancedConsole;
using Shared;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Master.Commands.Core
{
    public class MasterCommandsManager : GlobalCommandsManager
    {
        // Call base constructor and fill AdvancedConsole AutoCompletionManager command list
        public MasterCommandsManager(GlobalNetworkManager networkManager) : base(networkManager)
        {
            AutoCompletionManager.commands = commandList.Select(x => x.name).ToList();
        }

        /// <summary>
        /// Check if the given arguments match at least one validArguments combinaison of the given IMasterCommand class.
        /// The given command string can be modified to avoid disclosing user's infos.
        /// </summary>
        /// <param name="command">Invoked Command</param>
        /// <param name="arguments">Passed arguments</param>
        /// <param name="commandString">Command from user input</param>
        /// <returns>Correct syntax boolean</returns>
        public bool CheckCommandSyntax(IMasterCommand command, List<string> arguments, ref string commandString)
        {
            if (command.validArguments == null ||
                (arguments.Count == 0 && command.validArguments.Any(string.IsNullOrEmpty)))
            {
                return arguments.Count == 0;
            }

            foreach (var validSyntax in command.validArguments)
            {
                var splittedValidSyntax = validSyntax.Split(' ');
                if (splittedValidSyntax.Length != arguments.Count)
                {
                    continue;
                }

                var error = false;

                for (var i = 0; i < splittedValidSyntax.Length; i++)
                {
                    if (splittedValidSyntax[i].Contains("?") || splittedValidSyntax[i].Contains("?*")) continue;

                    if (splittedValidSyntax[i] == "0")
                    {
                        if (!int.TryParse(arguments[i], out int dummy))
                        {
                            error = true;
                            break;
                        }
                    }
                    else if (splittedValidSyntax[i] != arguments[i])
                    {
                        error = true;
                        break;
                    }
                }

                if (!error)
                {
                    var splittedString = GetSplittedCommandWithQuotes(commandString);
                    var list = new List<string> { splittedString[0] };

                    // Remove private informations (marked with '*' mark)
                    for (int i = 0; i < splittedValidSyntax.Length; i++)
                    {
                        list.Add(splittedValidSyntax[i].Contains("?*") ? "*" : splittedString[i + 1]);
                    }
                    commandString = list.Aggregate((x, y) => $"{x} {y}");
                    return true;
                }
            }

            return false;
        }

        /// <summary>
        /// Display an help message for the given IMasterCommand on the console
        /// </summary>
        /// <param name="command">Command to show the help for</param>
        public void ShowCommandHelp(IMasterCommand command)
        {
            ColorTools.WriteMessage(command.description);
            ColorTools.WriteInlineMessage("Syntax: ", ConsoleColor.DarkCyan);

            var help = new StringBuilder();

            if (command.validArguments == null) help.Append(command.name);

            for (int i = 0; i < command.validArguments?.Count; i++)
            {
                if (i != 0)
                {
                    help.Append(new string(' ', 8));
                }
                var syntax = command.name + ' ' + command.validArguments[i].Replace("?", "").Replace("*", "").Replace(":", "");

                if (i == command.validArguments.Count - 1)
                {
                    // Don't add a line return for the last syntax
                    help.Append(syntax);
                }
                else
                {
                    help.AppendLine(syntax);
                }
            }

            ColorTools.WriteMessage(help.ToString());
        }

        /// <summary>
        /// Display an help message for all the commands on the console, calls ShowCommandHelp
        /// </summary>
        public void ShowGlobalHelp()
        {
            foreach (var command in commandList)
            {
                Console.WriteLine(" ");
                ColorTools.WriteInlineMessage($"-- {command.name} --\n", ConsoleColor.Cyan);
                ShowCommandHelp((IMasterCommand)command);
            }
        }
    }
}
