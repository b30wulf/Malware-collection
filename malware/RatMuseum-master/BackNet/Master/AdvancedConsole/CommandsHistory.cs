using System.Collections.Generic;
using System.Linq;

namespace Master.AdvancedConsole
{
    internal static class CommandsHistory
    {
        static List<string> history = new List<string>();

        const int LIMIT = 30;

        static int currentCommandIndex = 0;

        /// <summary>
        /// Add a command to the history list (limited to LIMIT commands)
        /// </summary>
        /// <param name="command">Command to add the the history list</param>
        public static void AddCommand(string command)
        {
            // Don't store empty strings or spaces-only commands
            if (string.IsNullOrEmpty(command) || command.All(x => x == ' ')) return;

            // Concurrent same commands aren't saved
            if (history.Count != 0 && history[history.Count - 1] == command) return;

            history.Add(command);
            // If the history is at its size limit, remove the first element
            if (history.Count > LIMIT) history.RemoveAt(0);

            GoToLastCommand();
        }

        /// <summary>
        /// Get the command at currentCommandIndex - 1 from the history list
        /// </summary>
        /// <returns>Command string</returns>
        public static string GetPreviousCommand()
        {
            if (history.Count == 0 || currentCommandIndex == 0) return null;

            currentCommandIndex--;
            return history[currentCommandIndex];
        }

        /// <summary>
        /// Get the command at currentCommandIndex + 1 from the history list
        /// </summary>
        /// <returns>Command string</returns>
        public static string GetNextCommand()
        {
            if (history.Count == 0) return null;
            if (currentCommandIndex == history.Count - 1 || currentCommandIndex == history.Count) return "";

            currentCommandIndex++;
            return history[currentCommandIndex];
        }

        /// <summary>
        /// Set the current command index to the last command position
        /// </summary>
        public static void GoToLastCommand()
            => currentCommandIndex = history.Count;
    }
}
