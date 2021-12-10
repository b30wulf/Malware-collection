using System;

namespace Master.AdvancedConsole
{
    public static class ConsoleTools
    {
        const string BANNER = "\n    ██████╗  █████╗  ██████╗██╗  ██╗███╗   ██╗███████╗████████╗\n    ██╔══██╗██╔══██╗██╔════╝██║ ██╔╝████╗  ██║██╔════╝╚══██╔══╝\n    ██████╔╝███████║██║     █████╔╝ ██╔██╗ ██║█████╗     ██║   \n    ██╔══██╗██╔══██║██║     ██╔═██╗ ██║╚██╗██║██╔══╝     ██║   \n    ██████╔╝██║  ██║╚██████╗██║  ██╗██║ ╚████║███████╗   ██║   \n    ╚═════╝ ╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚══════╝   ╚═╝\n\n";

        internal const string PROMPT = "BackNet>";

        /// <summary>
        /// Show the program's banner
        /// </summary>
        public static void DisplayBanner()
        {
            Console.WriteLine(BANNER);
        }

        /// <summary>
        /// Print the cmd prompt of the program to indicate it's waiting for user input
        /// </summary>
        public static void DisplayCommandPrompt() => Console.Write(PROMPT);

        /// <summary>
        /// Ask the user to type a port number, this will loop until the user entered a valid port number
        /// </summary>
        /// <param name="promptMessage">Message to display before the ReadLine()</param>
        /// <returns>Chosen port number</returns>
        public static int AskForPortNumber(string promptMessage)
        {
            Console.Write($"{promptMessage} : ");
            return PromptInt((input) => input > 0 && input <= 65535, "Invalid port, please type again");
        }

        /// <summary>
        /// Loop until the user enters a valid integer,
        /// the additionnalCheck provides another level of verification
        /// </summary>
        /// <param name="additionnalCheck"></param>
        /// <param name="errorMessage">Error message to display when the user didn't answer correctly</param>
        /// <returns></returns>
        public static int PromptInt(Func<int, bool> additionnalCheck, string errorMessage)
        {
            var input = -1;
            while (input == -1)
            {
                try
                {
                    input = int.Parse(Console.ReadLine());
                    if (additionnalCheck != null && !additionnalCheck(input))
                        throw new ArgumentException();
                }
                catch (Exception)
                {
                    Console.Write($"{errorMessage}\n> ");
                    input = -1;
                }
            }

            return input;
        }

        /// <summary>
        /// Loop until the user enters a non-empty string
        /// </summary>
        /// <returns>Input string</returns>
        public static string PromptNonEmptyString()
        {
            var input = "";
            while (input == "")
            {
                input = Console.ReadLine();
                if (input == "") Console.Write("Empty string is not valid\n> ");
            }

            return input;
        }
    }
}
