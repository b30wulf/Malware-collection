using System;
using System.Collections.Generic;
using System.Linq;

namespace Master.AdvancedConsole
{
    internal static class AutoCompletionManager
    {
        /// <summary>
        /// Filled from MasterCommandsManager with commands names
        /// </summary>
        public static List<string> commands { get; set; }

        static List<string> autoCompleteResults = new List<string>();

        static bool tabbedOnce = false;

        /// <summary>
        /// Do the auto-completion work
        /// </summary>
        /// <param name="currentText">Command to complete ('ref' to edit it)</param>
        internal static void AutoComplete(ref string currentText)
        {
            if (currentText.Length == 0 || currentText.Contains(' ')) return;

            if (tabbedOnce)
            {
                if (autoCompleteResults.Count != 0)
                {
                    DisplayPossibilities(currentText);
                }
            }
            else
            {
                var text = currentText;
                autoCompleteResults = commands.Where(x => x.StartsWith(text)).ToList();
                if (autoCompleteResults.Count == 1)
                {
                    Console.Write(autoCompleteResults[0].Substring(currentText.Length));
                    currentText = autoCompleteResults[0];
                    autoCompleteResults.Clear();
                }
                else if (autoCompleteResults.Count != 0)
                {
                    var commonPrefix = GetLongestCommonPrefix(autoCompleteResults, currentText);
                    if (commonPrefix.Length != currentText.Length && commonPrefix != "")
                    {
                        CustomConsole.EraseCurrentCommand();
                        currentText = commonPrefix;

                        Console.Write(commonPrefix);
                    }
                }
                else
                {
                    autoCompleteResults.Clear();

                    // Don't invert tabbedOnce because it will require the user to tab once more for no reason
                    return;
                }
            }

            tabbedOnce = !tabbedOnce;
        }

        /// <summary>
        /// Get the longest common preffix between a list of strings
        /// </summary>
        /// <param name="baseCommands">List of strings to process</param>
        /// <param name="currentText">Text to start with</param>
        /// <returns>Longest common preffix string</returns>
        static string GetLongestCommonPrefix(List<string> baseCommands, string currentText)
        {
            if (baseCommands.Count == 0) return null;

            // There can't be a greater common prefix since a command as the same lenght as the user's input
            if (baseCommands.Any(x => x.Length == currentText.Length)) return "";

            var shortestString = baseCommands.OrderBy(x => x.Length).First().Length;
            int index;
            for (index = currentText.Length; index < shortestString; index++)
            {
                if (baseCommands.Any(x => x[index] != baseCommands[0][index])) break;
            }

            return baseCommands[0].Substring(0, index);
        }

        /// <summary>
        /// Display in columns the commands name starting with the given string
        /// </summary>
        /// <param name="currentText"></param>
        static void DisplayPossibilities(string currentText)
        {
            // Avoid displaying possibilities when text changed
            if (autoCompleteResults.Count < 1 ||
                autoCompleteResults.Any(x => x.Substring(0, currentText.Length) != currentText)) return;

            Console.Write('\n');
            foreach (var result in autoCompleteResults)
            {
                ColorTools.WriteInlineMessage($"    {currentText}", ConsoleColor.Cyan);
                ColorTools.WriteMessage(result.Substring(currentText.Length));
            }

            CustomConsole.firstCharHeight = Console.CursorTop;

            ConsoleTools.DisplayCommandPrompt();
            Console.Write(currentText);

            autoCompleteResults.Clear();
        }

        /// <summary>
        /// Empty autocompletion results list and restore the TAB behaviour to default
        /// </summary>
        public static void ResetAutoCompletionState()
        {
            autoCompleteResults.Clear();
            tabbedOnce = false;
        }
    }
}
