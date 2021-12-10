using System;

namespace Master.AdvancedConsole
{
    public static class ProgressDisplayer
    {
        static int completion = 0;

        static long total = 0;

        static int lastLine;

        /// <summary>
        /// Hide cursor to avoid annoying blink and set total. Show the base gauge and percentage.
        /// </summary>
        /// <param name="newTotal">Total value to reach</param>
        public static void Init(long newTotal)
        {
            Console.CursorVisible = false;
            total = newTotal;

            // Display base gauge
            Console.Write($"[{ new string(' ', 50) }]\n{ new string(' ', 52 - total.ToString().Length - 7) }/{total} bytes");
            Console.SetCursorPosition(1, Console.CursorTop);
            lastLine = Console.CursorTop;
        }

        /// <summary>
        /// Updates the completion meter with a gauge and a percentage
        /// </summary>
        /// <param name="current">Current value</param>
        public static void Update(long current)
        {
            var newCompletion = (int)((decimal)current / total * 100);

            // Transfert progressed
            if (completion != newCompletion)
            {
                completion = newCompletion;

                if (completion % 2 == 0)
                {
                    Console.SetCursorPosition(1, lastLine - 1);
                    Console.Write(new string('=', completion / 2 - 1));
                    Console.Write('>');
                }

                var leftStart = 52 - total.ToString().Length - 7 - current.ToString().Length;
                Console.SetCursorPosition(leftStart, lastLine);
                Console.Write(current);

                // Transfert finished
                if (completion == 100)
                {
                    Console.SetCursorPosition(50, lastLine - 1);
                    Console.Write('=');

                    End();
                }
            }
        }

        /// <summary>
        /// Reset completion and cursor
        /// </summary>
        public static void End()
        {
            completion = 0;

            // Return cursor
            Console.SetCursorPosition(0, lastLine + 1);
            Console.CursorVisible = true;
        }
    }
}
