using System.Collections.Generic;

namespace Master.Commands.Core
{
    /// <summary>
    /// MasterCommand needing a check or process before sending the command to the slave should implement this interface
    /// </summary>
    public interface IPreProcessCommand
    {
        /// <summary>
        /// Method to be executed before the command is sent to the slave
        /// </summary>
        /// <param name="args">Arguments passed along the command</param>
        /// <returns>Boolean stating the result of the operation</returns>
        bool PreProcess(List<string> args);
    }
}
