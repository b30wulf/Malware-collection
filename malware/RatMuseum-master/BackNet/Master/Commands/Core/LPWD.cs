using System;
using System.Collections.Generic;
using System.IO;

namespace Master.Commands.Core
{
    internal class LPWD : IMasterCommand
    {
        public string name { get; set; } = "lpwd";

        public string description { get; set; } = "Display the local current working directory";

        public bool isLocal { get; set; } = true;

        public List<string> validArguments { get; set; } = null;

        public void Process(List<string> args) => Console.WriteLine(Directory.GetCurrentDirectory());
    }
}
