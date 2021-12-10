using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows.Forms;

namespace Master.AdvancedConsole
{
    public static class CustomConsole
    {
        #region Current text

        static string currentText = string.Empty;

        internal static int firstCharHeight;

        internal static int lastCharHeight
            => firstCharHeight + (currentText.Length + ConsoleTools.PROMPT.Length) / Console.BufferWidth;

        static int lineLenght
            => currentText.Length - (Console.CursorTop - firstCharHeight) * Console.BufferWidth + ConsoleTools.PROMPT.Length;

        static int cursorPositionInString
            => (Console.CursorTop - firstCharHeight) * Console.BufferWidth + Console.CursorLeft - ConsoleTools.PROMPT.Length;

        #endregion Current text

        #region Misc

        static readonly List<ConsoleKey> printableKeys = new List<ConsoleKey>()
        {
            ConsoleKey.A, ConsoleKey.Z, ConsoleKey.E, ConsoleKey.R, ConsoleKey.T, ConsoleKey.Y, ConsoleKey.U, ConsoleKey.I, ConsoleKey.O, ConsoleKey.P, ConsoleKey.Q, ConsoleKey.S, ConsoleKey.D, ConsoleKey.F, ConsoleKey.G, ConsoleKey.H, ConsoleKey.J, ConsoleKey.K, ConsoleKey.L, ConsoleKey.M, ConsoleKey.W, ConsoleKey.X, ConsoleKey.C, ConsoleKey.V, ConsoleKey.B, ConsoleKey.N, ConsoleKey.Oem7, ConsoleKey.D1, ConsoleKey.D2, ConsoleKey.D3, ConsoleKey.D4, ConsoleKey.D5, ConsoleKey.D6, ConsoleKey.D7, ConsoleKey.D8, ConsoleKey.D9, ConsoleKey.D0, ConsoleKey.Oem4, ConsoleKey.OemPlus, ConsoleKey.Oem6, ConsoleKey.Oem1, ConsoleKey.Oem1, ConsoleKey.Oem3, ConsoleKey.Oem5, ConsoleKey.OemComma, ConsoleKey.OemPeriod, ConsoleKey.Oem2, ConsoleKey.Oem8, ConsoleKey.Oem102, ConsoleKey.NumPad0, ConsoleKey.Decimal, ConsoleKey.NumPad1, ConsoleKey.NumPad2, ConsoleKey.NumPad3, ConsoleKey.NumPad4, ConsoleKey.NumPad5, ConsoleKey.NumPad6, ConsoleKey.NumPad7, ConsoleKey.NumPad8, ConsoleKey.NumPad9, ConsoleKey.Divide, ConsoleKey.Multiply, ConsoleKey.Subtract, ConsoleKey.Add, ConsoleKey.Spacebar
        };

        static readonly int initialCursorSize = Console.CursorSize;

        #endregion Misc

        static bool insertMode = false;

        static ConsoleKeyInfo keyInfo;

        /// <summary>
        /// Console.WriteLine() replacement with auto-completion and clear console (CTRL + L)
        /// </summary>
        /// <returns>Command issued by the user</returns>
        public static string ReadLine()
        {
            // Resets here
            firstCharHeight = Console.CursorTop;
            currentText = "";
            CommandsHistory.GoToLastCommand();
            AutoCompletionManager.ResetAutoCompletionState();

            while (true)
            {
                keyInfo = Console.ReadKey(true);

                switch (keyInfo.Key)
                {
                    case ConsoleKey.Enter:
                        CommandsHistory.AddCommand(currentText);
                        Console.Write('\n');
                        // Don't return whitespaces only
                        return currentText.All(x => x == ' ') ? "" : currentText;

                    case ConsoleKey.Tab:
                        AutoCompletionManager.AutoComplete(ref currentText);
                        break;

                    case ConsoleKey.UpArrow:
                        WritePreviousCommand();
                        break;

                    case ConsoleKey.DownArrow:
                        WriteNextCommand();
                        break;

                    case ConsoleKey.LeftArrow:
                        if (keyInfo.Modifiers == ConsoleModifiers.Control)
                        {
                            SkipLeft();
                        }
                        else
                        {
                            MoveCursorLeft();
                        }
                        break;

                    case ConsoleKey.RightArrow:
                        if (keyInfo.Modifiers == ConsoleModifiers.Control)
                        {
                            SkipRight();
                        }
                        else
                        {
                            MoveCursorRight();
                        }
                        break;

                    case ConsoleKey.Home:
                        Console.SetCursorPosition(ConsoleTools.PROMPT.Length, firstCharHeight);
                        break;

                    case ConsoleKey.End:
                        Console.SetCursorPosition(ConsoleTools.PROMPT.Length, lastCharHeight);
                        Console.SetCursorPosition(lineLenght, lastCharHeight);
                        break;

                    case ConsoleKey.Delete:
                        EraseNextChar();
                        break;

                    case ConsoleKey.Backspace:
                        ErasePreviousChar();
                        break;

                    case ConsoleKey.Insert:
                        insertMode = !insertMode;
                        Console.CursorSize = Console.CursorSize == 100 ? initialCursorSize : 100;
                        break;

                    default:
                        if (!printableKeys.Contains(keyInfo.Key)) break;

                        if (keyInfo.Modifiers == ConsoleModifiers.Control)
                        {
                            if (keyInfo.Key == ConsoleKey.L)
                            {
                                ClearConsole();
                            }
                            else if (keyInfo.Key == ConsoleKey.V)
                            {
                                PasteText();
                            }
                            break;
                        }

                        PrintCurrentChar();
                        break;
                }
            }
        }

        static void MoveCursorLeft()
        {
            if (Console.CursorLeft != ConsoleTools.PROMPT.Length)
            {
                Console.SetCursorPosition(Console.CursorLeft - 1, Console.CursorTop);
            }
            else if (firstCharHeight != Console.CursorTop)
            {
                Console.SetCursorPosition(Console.BufferWidth - 1, Console.CursorTop - 1);
            }
        }

        static void MoveCursorRight()
        {
            if (Console.CursorLeft != Console.BufferWidth - 1 && Console.CursorLeft < lineLenght)
            {
                Console.SetCursorPosition(Console.CursorLeft + 1, Console.CursorTop);
            }
            else if (lastCharHeight != Console.CursorTop)
            {
                Console.SetCursorPosition(0, Console.CursorTop + 1);
            }
        }

        static void SkipLeft()
        {
            var wordsBegginning = new List<int>();

            for (int i = 1; i < cursorPositionInString - 2; i++)
            {
                if (currentText[i] == ' ' && currentText[i + 1] != ' ') wordsBegginning.Add(i + 1);
            }

            if (wordsBegginning.Count == 0)
            {
                // Go to begginning
                Console.SetCursorPosition(ConsoleTools.PROMPT.Length, firstCharHeight);
            }
            else
            {
                // Go to closest word begginning
                wordsBegginning = wordsBegginning.OrderByDescending(x => x).ToList();

                while (cursorPositionInString != wordsBegginning[0])
                {
                    MoveCursorLeft();
                }
            }
        }

        static void SkipRight()
        {
            var wordsBegginning = new List<int>();

            for (int i = cursorPositionInString; i < currentText.Length - 2; i++)
            {
                if (currentText[i] == ' ' && currentText[i + 1] != ' ') wordsBegginning.Add(i + 1);
            }

            if (wordsBegginning.Count == 0)
            {
                // Go to end
                Console.SetCursorPosition(ConsoleTools.PROMPT.Length, lastCharHeight);
                Console.SetCursorPosition(lineLenght, lastCharHeight);
            }
            else
            {
                // Go to closest word begginning
                while (cursorPositionInString != wordsBegginning[0])
                {
                    MoveCursorRight();
                }
            }
        }

        static void ErasePreviousChar()
        {
            if (cursorPositionInString == 0) return;

            var newEnd = currentText.Substring(cursorPositionInString);
            currentText = currentText.Substring(0, cursorPositionInString - 1) + newEnd;

            MoveCursorLeft();
            var leftPos = Console.CursorLeft;
            var topPos = Console.CursorTop;
            Console.Write(newEnd + " ");
            MoveCursorLeft();

            Console.SetCursorPosition(leftPos, topPos);
        }

        static void EraseNextChar()
        {
            if (cursorPositionInString == currentText.Length) return;

            var newEnd = currentText.Substring(cursorPositionInString + 1);
            currentText = currentText.Substring(0, cursorPositionInString) + newEnd;

            var leftPos = Console.CursorLeft;
            var topPos = Console.CursorTop;

            Console.Write(newEnd + " ");
            MoveCursorLeft();

            Console.SetCursorPosition(leftPos, topPos);
        }

        static void ClearConsole()
        {
            Console.Clear();
            ConsoleTools.DisplayCommandPrompt();
            Console.Write(currentText);
            firstCharHeight = Console.CursorTop;
        }

        static void PasteText()
        {
            var data = Clipboard.GetText();
            Console.Write(data);
            currentText += data;
        }

        static void PrintCurrentChar()
        {
            if (insertMode)
            {
                if (cursorPositionInString == currentText.Length)
                {
                    Console.Write(keyInfo.KeyChar);
                    currentText += keyInfo.KeyChar;
                }
                else
                {
                    var newEnd = keyInfo.KeyChar + currentText.Substring(cursorPositionInString + 1);
                    currentText = currentText.Substring(0, cursorPositionInString) + newEnd;
                    Console.Write(keyInfo.KeyChar);
                }
            }
            else
            {
                if (cursorPositionInString == currentText.Length)
                {
                    Console.Write(keyInfo.KeyChar);
                    currentText += keyInfo.KeyChar;
                }
                else
                {
                    var leftPos = Console.CursorLeft;
                    var topPos = Console.CursorTop;

                    var newEnd = keyInfo.KeyChar + currentText.Substring(cursorPositionInString);
                    currentText = currentText.Substring(0, cursorPositionInString) + newEnd;

                    Console.Write(newEnd);

                    if (cursorPositionInString != currentText.Length - 1)
                    {
                        Console.SetCursorPosition(leftPos, topPos);
                        MoveCursorRight();
                    }
                }
            }
        }

        static void WritePreviousCommand()
        {
            var newText = CommandsHistory.GetPreviousCommand();
            if (newText == null)
                return;

            EraseCurrentCommand();

            currentText = newText;
            Console.Write(currentText);
        }

        static void WriteNextCommand()
        {
            var newText = CommandsHistory.GetNextCommand();
            if (newText == null)
                return;

            EraseCurrentCommand();

            currentText = newText;
            Console.Write(currentText);
        }

        internal static void EraseCurrentCommand()
        {
            Console.SetCursorPosition(ConsoleTools.PROMPT.Length, firstCharHeight);
            while (cursorPositionInString != currentText.Length)
                Console.Write(' ');

            Console.SetCursorPosition(ConsoleTools.PROMPT.Length, firstCharHeight);
        }
    }
}
