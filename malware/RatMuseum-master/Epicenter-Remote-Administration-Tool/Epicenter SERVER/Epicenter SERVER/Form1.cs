using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Net.Sockets;
using System.Threading;
using System.Net;
using System.Text.RegularExpressions;

namespace Epicenter_SERVER
{
    public partial class Form1 : Form
    {
        bool keepListening = false;
        TcpListener listener;

        private void btnControl_Click(object sender, EventArgs e)
        {
            if(keepListening == false)
            {
                if (String.IsNullOrEmpty(txtPort.Text))
                {
                    MessageBox.Show("Please enter a port to listen.", "Missing Field", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }

                // Booting up the server
                keepListening = true;
                btnControl.Text = "Stop Listening";

                Thread thListen = new Thread( new ThreadStart(Listener) );
                thListen.Start();
                txtPort.Enabled = false;
            }
            else
            {
                // Stopping the server
                keepListening = false;
                btnControl.Text = "Start Listening";
                txtPort.Enabled = true;
            }
        }


        private void Listener()
        {
            // Starting to listen to the incoming connection requests
            listener = new TcpListener( IPAddress.Any, Convert.ToInt32(txtPort.Text) );
            listener.Start();

            while (keepListening)
            {
                TcpClient client = listener.AcceptTcpClient();
                string clientEndPoint = StripIP( client.Client.RemoteEndPoint.ToString() );
                
                // A Connection request from a client is received, HandleClientComm will take it from here
                Thread clientThread = new Thread( new ParameterizedThreadStart(HandleClientComm) );
                clientThread.Start(client);
            }
        }

        ASCIIEncoding encoder = new ASCIIEncoding();
        private void HandleClientComm(object client)
        {
            // Getting the dataStream
            TcpClient tcpClient = (TcpClient)client;            
            NetworkStream clientStream = tcpClient.GetStream();
            
            string whoami = StripIP(tcpClient.Client.RemoteEndPoint.ToString());
            string given_name = "to-be-determined";
            string os_data = "to-be-determined";

            // Custom variables
            byte[] incoming_raw_data = new byte[2048];
            int bytesRead;
            bool disconnect = false;
            int session = 0;

            while (!disconnect)
            {
                bytesRead = 0;

                try
                {
                    bytesRead = clientStream.Read(incoming_raw_data, 0, 2048);
                }
                catch
                {
                    #region Disconnection Routine + return directive
                    DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                    return;
                    #endregion
                }

                if (bytesRead == 0)
                {
                    #region Disconnection Routine + return directive
                    DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                    return;
                    #endregion
                }

                if (CheckValidity(ref incoming_raw_data) == false)
                {
                    if (session > 0) // We have an amorphous package! We should celebrate it!
                    {
                        administrate activePanel = GetAssosicatedPanel(whoami);
                        if (activePanel != null) // Panel is open
                        {
                            // Passing the communication over to the associated admin panel.
                            activePanel.reportAmorphPack(incoming_raw_data, bytesRead);
                        }
                        continue;
                    }
                    else
                    {
                        #region Disconnection Routine + return directive
                        DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                        return;
                        #endregion
                    }
                }
                

                // -- We got a valid raw data - proceeding
                // -- Be careful to start reading the package from [scheme.Length]

                // CLIENT IS IDENTIFYING ITSELF
                if (incoming_raw_data[commonData.scheme.Length] == 10)
                {
                    // A client can't identify itself twice
                    if (session != 0)
                    {
                        #region Disconnection Routine + return directive
                        DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                        return;
                        #endregion
                    }

                    // Interpreting the rest as ASCII
                    string[] nameAndOS = Regex.Split( encoder.GetString(incoming_raw_data, commonData.scheme.Length + 1, bytesRead - commonData.scheme.Length - 1), "<%SEP%>") ;

                    if (nameAndOS.Length != 2)
                    {
                        #region Disconnection Routine + return directive
                        DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                        return;
                        #endregion
                    }

                    given_name = nameAndOS[0];
                    os_data = nameAndOS[1];

                    // Adding the client to the common storage and the list
                    commonData.ns_collection.Add(clientStream);
                    addToList(given_name, os_data, whoami);

                    try
                    {
                        clientStream.Write(commonData.recv_signal, 0, commonData.recv_signal.Length);
                    }
                    catch { }
                }
                // GENERAL INFORMATION CHANNEL
                else if (incoming_raw_data[commonData.scheme.Length] == 20)
                {
                    // Identification is a must before conducting any further communication
                    if (session == 0)
                    {
                        #region Disconnection Routine + return directive
                        DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                        return;
                        #endregion
                    }

                    administrate activePanel = GetAssosicatedPanel(whoami);
                    if (activePanel != null) // Panel is open
                    {
                        // Passing the communication over to the associated admin panel.
                        activePanel.reportIncomingComm(incoming_raw_data, bytesRead);
                    } 
                }
                else
                {
                    #region Disconnection Routine + return directive
                    DisconnectionRoutine(whoami, ref tcpClient, ref clientStream);
                    return;
                    #endregion
                }

                session++;
            }
        }


        private void DisconnectionRoutine(string ip, ref TcpClient tcpClient, ref NetworkStream clientStream)
        {
            this.Invoke((MethodInvoker)delegate 
            { 
                removeFromList(ip);
            });

            if (commonData.ns_collection.Contains(clientStream))
                commonData.ns_collection.Remove(clientStream);

            clientStream.Close();
            tcpClient.Close();
        }

        private bool CheckValidity(ref byte[] package)
        {
            if(package.Length < commonData.scheme.Length + 1)
                return false;

            for(int i = 0; i < commonData.scheme.Length; i++)
                if(package[i] != commonData.scheme[i])
                    return false;
           
            return true;
        }

        private administrate GetAssosicatedPanel(string ipaddr)
        {
            if (commonData.ip_panel_pair.ContainsKey(ipaddr)) // Panel open
            {
                administrate activePanel;
                if (commonData.ip_panel_pair.TryGetValue(ipaddr, out activePanel))
                    return activePanel;
            }
            return null;
        }

        #region TCP/IP STUFF
        private string StripIP(string endpt)
        {
            if (endpt.Contains(":"))
                return endpt.Substring(0, endpt.IndexOf(':'));
            else
                return endpt;
        }
        #endregion

        #region ListView Controls
        public Form1()
        {
            InitializeComponent();
        }

        private void addToList(string name, string os, string ip)
        {
            if (this.InvokeRequired)
                this.Invoke((MethodInvoker)delegate { addToList(name, os, ip); });
            else
            {
                if (!lbClients.Items.Contains(new ListViewItem(new string[] { name, os, ip })))
                    lbClients.Items.Add(new ListViewItem(new string[] { name, os, ip }));
            }
        }

        private void removeFromList(string ip)
        {
            if (this.InvokeRequired)
                this.Invoke((MethodInvoker)delegate { removeFromList(ip); });
            else
            {
                for (int i = 0; i < lbClients.Items.Count; i++)
                {
                    if (lbClients.Items[i].SubItems[2].Text == ip)
                    {

                        administrate panel = GetAssosicatedPanel(lbClients.Items[i].SubItems[2].Text);
                        lbClients.Items.RemoveAt(i);

                        if (panel != null)
                            panel.Intermission();
                        break;
                    }
                }
            }
        }
        #endregion

        #region Trivial Stuff
        private void Form1_FormClosed(object sender, FormClosedEventArgs e)
        {
            Environment.Exit(0);
        }
        #endregion

        private void lbClients_DoubleClick(object sender, EventArgs e)
        {
            if (lbClients.SelectedItems.Count == 1)
            {
                administrate admin = new administrate(lbClients.SelectedItems[0].SubItems[0].Text,
                    commonData.ns_collection[lbClients.SelectedItems[0].Index], lbClients.SelectedItems[0].SubItems[2].Text);
                admin.Show();
                commonData.ip_panel_pair.Add(lbClients.SelectedItems[0].SubItems[2].Text, admin);
            }
        }

        private void btnCreateClient_Click(object sender, EventArgs e)
        {
            CreateClient cc = new CreateClient();
            cc.Show();
        }
    }
}
