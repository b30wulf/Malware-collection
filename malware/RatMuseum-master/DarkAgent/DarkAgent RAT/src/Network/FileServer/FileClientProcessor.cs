using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;
using System.Net;
using System.Windows.Forms;
using DarkAgent_RAT.src.Network.FileServer.Packets;
using DarkAgent_RAT.src.Utils;

namespace DarkAgent_RAT.src.Network.FileServer
{
    public class FileClientProcessor
    {
        static FileClientProcessor instance = null;
        static readonly object padlock = new object();

        public static FileClientProcessor Instance
        {
            get { lock (padlock) { if (instance == null)instance = new FileClientProcessor(); return instance; } }
        }

        public SortedList<int, FileClients> _clientList;
        private static int NetworkKey;

        public FileClientProcessor()
        {
            _clientList = new SortedList<int, FileClients>();
        }

        public static void SetNetworkKey(int key)
        {
            NetworkKey = key;
        }

        public void ProcessClient(Socket client)
        {
            //System.Threading.Thread.Sleep(50); //Small delay so the process will be using 0-1% cpu usage at flood attack
            if (File_MaxConnections.AcceptConnection(client.RemoteEndPoint))
            {
                int ID = _clientList.Count;
                FileClients c = new FileClients(client, NetworkKey);
                _clientList.Add(ID, c);
                c.DisconnectHandle = OnDisconnect;
                c.ClientID = ID;

                settings.ClientsConnected++;
                Logger.AddLog(new LogInfo("Incoming client", "Accepted"));
            }
            else
            {
                client.Disconnect(false);
                client = null;
            }
        }

        public void BroadCast(SendBasePacket packet)
        {
            for (int i = 0; i < _clientList.Count; i++)
            {
                try { _clientList[i].SendPacket(packet); settings.SendedPackets++; }
                catch { }
            }
        }

        //This method is called by any client when its disconnected, this is done through a delegate thats set.
        private void OnDisconnect(FileClients client)
        {
            _clientList.Remove(client.ClientID);
        }

        public int ConnectedClientCount
        {
            get { return _clientList.Count; }
        }
    }

    public class File_MaxConnections
    {
        private static SortedList<string, int> _connections;
        private static int _maxConnections = 100;

        public File_MaxConnections()
        {
            _connections = new SortedList<string, int>();
        }

        private static void AddConnection(EndPoint IP)
        {
            string TempIP = IP.ToString().Split(':')[0];

            if (!_connections.ContainsKey(TempIP))
                _connections.Add(TempIP, 0);
            else
                _connections[TempIP]++;
        }

        public static void Disconnect(EndPoint IP)
        {
            string TempIP = IP.ToString().Split(':')[0];
            if (_connections.ContainsKey(TempIP))
                _connections[TempIP]--;

            if (_connections.ContainsKey(TempIP))
            {
                if (_connections[TempIP] == 0)
                    _connections.Remove(TempIP); //No connections... remove it from the list
            }
        }

        public static bool AcceptConnection(EndPoint IP)
        {
            AddConnection(IP);
            string TempIP = IP.ToString().Split(':')[0];
            if (_connections[TempIP] > _maxConnections)
                return false;
            else
                return true;
        }
    }
}
