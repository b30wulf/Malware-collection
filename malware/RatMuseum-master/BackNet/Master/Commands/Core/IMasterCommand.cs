using Shared;
using System.Collections.Generic;

namespace Master.Commands.Core
{
    public interface IMasterCommand : ICommand
    {
        /// <summary>
        /// Complete description of the command, doesn't include the syntax
        /// </summary>
        string description { get; }

        /// <summary>
        /// Is the command only executed locally or also from the slave side ?
        /// </summary>
        bool isLocal { get; }

        /// <summary>
        /// List of all possible arguments combinaisons, null if none.
        /// "?" is for a string, and "0" is for an integer
        /// If a string is confidential, add a "*" => "?*", this way, it will not be sent to the slave as is
        /// ? arguments must be followed by [ArgumentName] => example: "?:[filename]" or "?*:[filename]"
        /// </summary>
        List<string> validArguments { get; }
    }
}
