using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Win32;

namespace DarkAgent_Client.src.Utils
{
    class WinSerial
    {
        public static string GetSerial()
        {
            string strKey = "";
            try
            {
                RegistryKey RegKey = Registry.LocalMachine.OpenSubKey("Software\\Microsoft\\Windows NT\\CurrentVersion", false);
                byte[] bytDPID = (byte[])RegKey.GetValue("DigitalProductID");
                byte[] bytKey = new byte[15];

                Array.Copy(bytDPID, 52, bytKey, 0, 15);

                string strChar = "BCDFGHJKMPQRTVWXY2346789";
                for (int j = 0; j <= 24; j++)
                {
                    short nCur = 0;
                    for (int i = 14; i >= 0; i += -1)
                    {
                        nCur = Convert.ToInt16(nCur * 256 ^ bytKey[i]);
                        bytKey[i] = Convert.ToByte(Convert.ToInt32(nCur / 24));
                        nCur = Convert.ToInt16(nCur % 24);
                    }
                    strKey = strChar.Substring(nCur, 1) + strKey;
                }
                for (int i = 4; i >= 1; i += -1)
                {
                    strKey = strKey.Insert(i * 5, "-");
                }
            }
            catch
            {
            	return "****-****-****-****";
            }

            return strKey;
        }
    }
}
