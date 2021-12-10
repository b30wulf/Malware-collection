using System.Collections.Generic;

namespace Shared
{
    public interface ICommand
    {
        /// <summary>
        /// Name of the command
        /// </summary>
        string name { get; }

        /// <summary>
        /// Main method to be executed
        /// </summary>
        /// <param name="args">Args passed with the command</param>
        void Process(List<string> args);
    }
}