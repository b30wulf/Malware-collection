using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.Server.Plugin.UIService
{
    public static class UiServiceProvider
    {
        private static readonly Dictionary<string, IUiService> _uiServices = new Dictionary<string, IUiService>
        {
            {"ConnectionList", new ConnectionListService()}
        };

        public static IUiService GetService(string identifier)
        {
            return !_uiServices.ContainsKey(identifier) ? null : _uiServices[identifier];
        }
    }
}
