using Newtonsoft.Json;

namespace Master.Botnet.JSON
{
    internal class ConnectClientRequestJson : BaseRequestJson
    {
        public int host_id { get; set; }

        public string hostname_ip { get; set; }

        public int port { get; set; }

        public ConnectClientRequestJson(int host_id, string hostname_ip, int port) : base(command: "connect_to_client")
        {
            this.host_id = host_id;
            this.hostname_ip = hostname_ip;
            this.port = port;
        }
    }

    internal class ConnectClientResponseJson
    {
        [JsonProperty(Required = Required.Always)]
        public bool result { get; set; }
    }
}
