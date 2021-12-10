using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;
using System.Net;
using System.IO;

namespace Epicenter_Client
{
    class FileClient
    {
        public static void SendFile(object args)
        {
            string[] arguments = (string[])args;

            // Defining the port and IP to form the connection.
            TcpClient client = null;
            IPEndPoint serverside = new IPEndPoint(IPAddress.Parse(Program.ipaddr), Convert.ToInt32(arguments[2]));
            bool isConnected = false;

            while (!isConnected)
            {
                try
                {
                    client = new TcpClient();
                    client.Connect(serverside);
                    isConnected = true;
                }
                catch { System.Threading.Thread.Sleep(2000); }
            }

            FileStream fs = null;
            NetworkStream ns = null;
            try
            {
                ns = client.GetStream();
                fs = new FileStream(arguments[1], FileMode.Open, FileAccess.Read);
            }
            catch { if (client != null) { client.Close(); } }

            if (fs == null)
                return;

            byte[] message = new byte[4096];
            long fsLen = fs.Length;

            for (long i = 0; i < fsLen; )
            {
                if (fsLen - i > 4096)
                {
                    fs.Read(message, 0, 4096);
                    ns.Write(message, 0, 4096);

                    i += 4096;
                }
                else
                {
                    fs.Read(message, 0, (int)(fsLen - i));
                    ns.Write(message, 0, (int)(fsLen - i));

                    i += fsLen - i;
                }

            }

            ns.Close();
            ns.Dispose();
            client.Close();
        }

        public static void SendByteArray(object args)
        {
            int port = (int)args;

            // Defining the port and IP to form the connection.
            TcpClient client = null;
            IPEndPoint serverside = new IPEndPoint(IPAddress.Parse(Program.ipaddr), port);
            bool isConnected = false;

            while (!isConnected)
            {
                try
                {
                    client = new TcpClient();
                    client.Connect(serverside);
                    isConnected = true;
                }
                catch { System.Threading.Thread.Sleep(2000); }
            }

            NetworkStream ns = null;
            try
            {
                ns = client.GetStream();
            }
            catch { return; }

            if (ScreenshotMechanism.latestScreenshot == null)
                return;

            byte[] message = new byte[4096];
            long fsLen = ScreenshotMechanism.latestScreenshot.Length;
            ScreenshotMechanism.latestScreenshot.Seek(0, SeekOrigin.Begin);

            for (long i = 0; i < fsLen; )
            {
                if (fsLen - i > 4096)
                {
                    ScreenshotMechanism.latestScreenshot.Read(message, 0, 4096);
                    ns.Write(message, 0, 4096);

                    i += 4096;
                }
                else
                {
                    ScreenshotMechanism.latestScreenshot.Read(message, 0, (int)(fsLen - i));
                    ns.Write(message, 0, (int)(fsLen - i));

                    i += fsLen - i;
                }
            }

            ns.Close();
            ns.Dispose();
            client.Close();
        
        }

        public static void ReceiveFile(object args)
        {
            string[] arguments = (string[])args;

            // Defining the port and IP to form the connection.
            TcpClient client = null;
            IPEndPoint serverside = new IPEndPoint(IPAddress.Parse(Program.ipaddr), Convert.ToInt32(arguments[1]));
            bool isConnected = false;

            while (!isConnected)
            {
                try
                {
                    client = new TcpClient();
                    client.Connect(serverside);
                    isConnected = true;
                }
                catch { System.Threading.Thread.Sleep(2000); }
            }

            FileStream fs = null;
            NetworkStream ns = null;
            try
            {
                ns = client.GetStream();
                fs = new FileStream(arguments[0], FileMode.Create, FileAccess.Write);
            }
            catch { if (client != null) { client.Close(); } }

            if (fs == null)
                return;

            // Custom variables
            byte[] incoming_raw_data = new byte[4096];
            int bytesRead;

            while (true)
            {
                bytesRead = 0;

                try
                {
                    bytesRead = ns.Read(incoming_raw_data, 0, 4096);
                }
                catch
                {
                    break;
                }

                if (bytesRead == 0)
                {
                    break;
                }

                fs.Write(incoming_raw_data, 0, bytesRead);
            }
            ns.Close();
            ns.Dispose();
            client.Close();
            if (fs != null)
            {
                fs.Close(); fs.Dispose();
            }
        }
    }
}
