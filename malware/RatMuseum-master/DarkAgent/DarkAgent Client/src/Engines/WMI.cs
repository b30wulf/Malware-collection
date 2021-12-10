using System;
using System.Collections.Generic;
using System.Text;
using System.Management;

namespace DarkAgent_Client.src.Engines
{
    public class WMI
    {
        public static string ReadString(string column, string location, string where)
        {
            try
            {
                string query;
                if (where == "" || where == null)
                    query = "select " + column + " from " + location;
                else
                     query = "select " + column + " from " + location + " where " + column + "='" + where + "'";

                ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                objOS = new ManagementObjectSearcher(query);
                foreach (ManagementBaseObject objMgmt in objOS.Get())
                    return objMgmt[column].ToString();
            }
            catch
            {
                try
                {
                    ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                    objOS = new ManagementObjectSearcher("select * from " + location);
                    foreach (ManagementBaseObject objMgmt in objOS.Get())
                        return objMgmt[column].ToString();
                }
                catch { }
            }
            return "";
        }

        public static string[] ReadStringArray(string column, string location, string where)
        {
            string query;
            if (where == "" || where == null)
                query = "select " + column + " from " + location;
            else
                query = "select " + column + " from " + location + " where " + column + "='" + where + "'";

            List<string> StringArray = new List<string>();
            try
            {
                ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                objOS = new ManagementObjectSearcher(query);
                foreach (ManagementBaseObject objMgmt in objOS.Get())
                    StringArray.Add(objMgmt[column].ToString());
            }
            catch
            {
                try
                {
                    ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                    objOS = new ManagementObjectSearcher("select * from " + location);
                    foreach (ManagementBaseObject objMgmt in objOS.Get())
                        StringArray.Add(objMgmt[column].ToString());
                }catch{}
            }
            return StringArray.ToArray();
        }

        public static int ReadInteger(string column, string location, string where)
        {
            string query;
            if (where == "" || where == null)
                query = "select " + column + " from " + location;
            else
                query = "select " + column + " from " + location + " where " + column + "='" + where + "'";

            try
            {
                ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                objOS = new ManagementObjectSearcher(query);
                foreach (ManagementBaseObject objMgmt in objOS.Get())
                    return Int32.Parse(objMgmt[column].ToString());
            }
            catch
            {
                ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                objOS = new ManagementObjectSearcher("select * from " + location);
                foreach (ManagementBaseObject objMgmt in objOS.Get())
                    return Int32.Parse(objMgmt[column].ToString());
            }
            return 0;
        }

        public static string ExecuteQuery(string Query, string column, string where)
        {
            try
            {
                ManagementObjectSearcher objOS = default(ManagementObjectSearcher);
                objOS = new ManagementObjectSearcher(Query);
                foreach (ManagementBaseObject objMgmt in objOS.Get())
                    return objMgmt[column].ToString();
            }
            catch{}
            return "";
        }

    }
}