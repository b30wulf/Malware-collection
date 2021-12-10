using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;
using System.Net;
using System.Net.Sockets;
using System.IO;
using System.Windows.Forms;

namespace Epicenter_SERVER
{
    class FileServer
    {
        private string expectedIP;
        private int port;
        private string locationToSave;
        private string locToReadFrom;
        private TcpListener listener;
        private administrate owner;

        private bool isListening = false;

        public FileServer(administrate _owner, string _expectedIP, int _port, string _locationToSave, string _locToReadFrom)
        {
            // Internalizing the variables
            expectedIP = _expectedIP;
            port = _port;
            locationToSave = _locationToSave;
            owner = _owner;
            locToReadFrom = _locToReadFrom;
        }


        public void StartListeningForFile()
        {
            isListening = true;

            Thread thListening = new Thread(new ParameterizedThreadStart(Listener));
            thListening.Start((object)false);
        }

        public void StartListeningForSS()
        {
            isListening = true;

            Thread thListening = new Thread(new ParameterizedThreadStart(Listener));
            thListening.Start((object)true);
        }

        public void StartListeningForUpload()
        {
            isListening = true;
            owner.ReportIn = "Listening to upload...";
            Thread thListening = new Thread( new ThreadStart(UploadListener) );
            thListening.Start();


        }

        public void StopListening()
        {
            isListening = false;
        }


        private void Listener(object forSS)
        {
            bool isSS = (bool)forSS;
            // Starting to listen to the incoming connection requests
            listener = new TcpListener(IPAddress.Any, port);
            listener.Start();

            while (isListening)
            {
                TcpClient client = null;
                try
                {
                    client = listener.AcceptTcpClient();
                }
                catch { return; }
                
                // A Connection request from a client is received, HandleClientComm will take it from here

                if (!isSS)
                {
                    Thread clientThread = new Thread(new ParameterizedThreadStart(HandleClientComm));
                    clientThread.Start(client);
                }
                else
                { 
                    Thread clientThread = new Thread(new ParameterizedThreadStart(HandleClientCommSS));
                    clientThread.Start(client);
                }
            }
        }

        private void UploadListener()
        {
            // Starting to listen to the incoming connection requests
            listener = new TcpListener(IPAddress.Any, port);
            listener.Start();

            while (isListening)
            {
                TcpClient client = null;
                try
                {
                    client = listener.AcceptTcpClient();
                }
                catch { return; }

                // A Connection request from a client is received, HandleClientComm will take it from here
                owner.ReportIn = "Client connected, will receive the file.";
                Thread clientThread = new Thread(new ParameterizedThreadStart(HandleClientCommUpload));
                clientThread.Start(client);
            }
        }

        private FileStream toWrite = null;
        private void HandleClientComm(object client)
        {
            // Getting the dataStream
            TcpClient tcpClient = (TcpClient)client;            
            NetworkStream clientStream = tcpClient.GetStream();
            
            // Custom variables
            byte[] incoming_raw_data = new byte[4096];
            int bytesRead;
            int oldPos = 0;
            long currentPos = 0;

            while (true)
            {
                bytesRead = 0;

                try
                {
                    bytesRead = clientStream.Read(incoming_raw_data, 0, 4096);
                }
                catch
                {
                    break;
                }

                if (bytesRead == 0)
                {
                    break;
                }

                // -- We got a valid raw data - proceeding to write...
                if(toWrite==null)
                    toWrite = new FileStream(locationToSave, FileMode.Create, FileAccess.Write);

                toWrite.Write(incoming_raw_data, 0, bytesRead);
                currentPos += bytesRead;


                if ((currentPos/ 1000) - oldPos > 50)
                {
                    owner.ReportIn = (currentPos / 1000).ToString() + " KB transferred";
                    oldPos = (int)(currentPos / 1000);
                }

                
            }

            clientStream.Close();
            clientStream.Dispose();
            tcpClient.Close();

            if (toWrite != null)
            {
                toWrite.Close();
                toWrite.Dispose();
            }

            isListening = false;
            listener.Stop();
            MessageBox.Show("File transfer complete.", "Complete", MessageBoxButtons.OK, MessageBoxIcon.Information);
            owner.ReportIn = "File transfer complete.";
        }

        private void HandleClientCommSS(object client)
        {
            // Getting the dataStream
            TcpClient tcpClient = (TcpClient)client;            
            NetworkStream clientStream = tcpClient.GetStream();
            
            // Custom variables
            byte[] incoming_raw_data = new byte[4096];
            int bytesRead;
            int oldPos = 0;
            long currentPos = 0;

            while (true)
            {
                bytesRead = 0;

                try
                {
                    bytesRead = clientStream.Read(incoming_raw_data, 0, 4096);
                }
                catch
                {
                    break;
                }

                if (bytesRead == 0)
                {
                    break;
                }

                // -- We got a valid raw data - proceeding to write...
                if(toWrite==null)
                    toWrite = new FileStream(locationToSave, FileMode.Create, FileAccess.Write);

                toWrite.Write(incoming_raw_data, 0, bytesRead);
                currentPos += bytesRead;

                if ((currentPos / 1000) - oldPos > 10)
                {
                    owner.ReportInSS = (currentPos / 1000).ToString() + " KB transferred";
                    oldPos = (int)(currentPos / 1000);
                }

                
            }

            clientStream.Close();
            clientStream.Dispose();
            tcpClient.Close();

            if (toWrite != null)
            {
                toWrite.Close();
                toWrite.Dispose();
            }

            isListening = false;
            listener.Stop();
            owner.ReportInSS = "complete<%SEP%>" + locationToSave;
        
        }

        private void HandleClientCommUpload(object client)
        {
            // Getting the dataStream
            TcpClient tcpClient = (TcpClient)client;            
            NetworkStream clientStream = tcpClient.GetStream();
            
            // Custom variables
            byte[] outgoing_raw_data = new byte[4096];
            int oldPos = 0;

            FileStream toRead = new FileStream(locToReadFrom, FileMode.Open, FileAccess.Read);
            long len = toRead.Length;
            for (long i = 0; i < len; )
            {
                if (len - i > 4096)
                {
                    toRead.Read(outgoing_raw_data, 0, 4096);
                    clientStream.Write(outgoing_raw_data, 0, 4096);
                    i += 4096;
                }
                else
                {
                    toRead.Read(outgoing_raw_data, 0, (int)(len - i));
                    clientStream.Write(outgoing_raw_data, 0, (int)(len - i));
                    i += len - i;
                }

                if ( (i / 1000) - oldPos > 20)
                {
                    owner.ReportIn = (i / 1000).ToString() + "/" + (len/1000).ToString() + "KB uploaded";
                    oldPos = (int)(i / 1000);
                }
            }

            clientStream.Close();
            clientStream.Dispose();
            tcpClient.Close();

            isListening = false;
            listener.Stop();
            MessageBox.Show("File upload complete.", "Complete", MessageBoxButtons.OK, MessageBoxIcon.Information);
            owner.ReportIn = "File upload complete.";
        }
        
    }
}
