using System;

namespace Master.AdvancedConsole
{
    public static class ColorTools
    {
        /// <summary>
        /// Sets the default consolecolor to green
        /// </summary>
        public static void SetDefaultConsoleColor()
        {
            Console.ForegroundColor = ConsoleColor.Green;
        }

        public static void WriteCommandMessage(string message) =>
            WriteColorMessage(message, " [ ] ", ConsoleColor.Gray);

        public static void WriteCommandError(string message) =>
            WriteColorMessage(message, " [-] ", ConsoleColor.Red);

        public static void WriteCommandSuccess(string message) =>
            WriteColorMessage(message, " [+] ", ConsoleColor.Cyan);

        public static void WriteMessage(string message) =>
            WriteColorMessage(message, ConsoleColor.Gray);

        public static void WriteWarning(string message) =>
            WriteColorMessage(message, ConsoleColor.Yellow);

        public static void WriteError(string message) =>
            WriteColorMessage(message, ConsoleColor.Red);

        static void WriteColorMessage(string message, string prefix, ConsoleColor color)
        {
            Console.ForegroundColor = color;
            Console.WriteLine(prefix + message);
            Console.ForegroundColor = ConsoleColor.Green;
        }

        static void WriteColorMessage(string message, ConsoleColor color)
        {
            Console.ForegroundColor = color;
            Console.WriteLine(message);
            Console.ForegroundColor = ConsoleColor.Green;
        }

        public static void WriteInlineMessage(string message, ConsoleColor color)
        {
            Console.ForegroundColor = color;
            Console.Write(message);
            Console.ForegroundColor = ConsoleColor.Green;
        }
    }
}
