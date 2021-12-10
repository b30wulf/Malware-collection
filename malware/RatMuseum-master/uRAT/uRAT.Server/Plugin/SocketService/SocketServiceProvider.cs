using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uRAT.Server.Plugin.UIService;
using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.Server.Plugin.SocketService
{
    class SocketServiceProvider
    {
        private static readonly Dictionary<string, ISocketService> _socketServices = new Dictionary<string, ISocketService>
        {
        
        };

        public static ISocketService GetService(string identifier)
        {
            return !_socketServices.ContainsKey(identifier) ? null : _socketServices[identifier];
        }
    }
}
