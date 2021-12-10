using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Xml;

namespace uRAT.Server.Tools
{
    public class SettingsHelper
    {
        public class PluginMetadata
        {
            public Guid Guid { get; set; }
            public string Name { get; set; }
            public string Author { get; set; }
            public Version Version { get; set; }
            public string Description { get; set; }
            public bool Enabled { get; set; }
        }

        public void CreateSettingsFile()
        {
            XmlWriterSettings xmlWriterSettings = new XmlWriterSettings()
            {
                Indent = true,
                IndentChars = "\t",
                NewLineOnAttributes = true
            };


            using (XmlWriter writer = XmlWriter.Create("settings.xml", xmlWriterSettings))
            {
                writer.WriteStartDocument();
                writer.WriteStartElement("PluginSettings");
                foreach (var pluginHost in Globals.PluginAggregator.LoadedPlugins)
                {
                    writer.WriteStartElement("Plugin");

                    writer.WriteElementString("GUID", pluginHost.PluginHostGuid.ToString());
                    writer.WriteElementString("Name", pluginHost.PluginHost.Name);
                    writer.WriteElementString("Author", pluginHost.PluginHost.Author);
                    writer.WriteElementString("Version", pluginHost.PluginHost.Version.ToString());
                    writer.WriteElementString("Description", pluginHost.PluginHost.Description);
                    writer.WriteElementString("Enabled", pluginHost.Enabled ? "True" : "False");
    
                    writer.WriteEndElement();
                }


                writer.WriteEndElement();

                writer.WriteEndDocument();
            }
        }

        public List<PluginMetadata> FetchAllPlugins()
        {
            return FetchAllPluginsImpl().ToList();
        }

        //TODO: optimize..
        public PluginMetadata FetchPlugin(Guid pluginGuid)
        {
            XmlDocument xmlDocument;

            xmlDocument = new XmlDocument();
            xmlDocument.LoadXml(File.ReadAllText("settings.xml"));

            return (from XmlElement xmlElemenet in xmlDocument.DocumentElement.SelectNodes("Plugin")
                where xmlElemenet["GUID"].InnerText == pluginGuid.ToString()
                select new PluginMetadata
                {
                    Guid = new Guid(xmlElemenet.GetElementsByTagName("GUID")[0].InnerText),
                    Name = xmlElemenet.GetElementsByTagName("Name")[0].InnerText,
                    Author = xmlElemenet.GetElementsByTagName("Author")[0].InnerText,
                    Version = new Version(xmlElemenet.GetElementsByTagName("Version")[0].InnerText),
                    Description = xmlElemenet.GetElementsByTagName("Description")[0].InnerText,
                    Enabled = xmlElemenet.GetElementsByTagName("Enabled")[0].InnerText == "True"
                }).FirstOrDefault();

            
        }

        private IEnumerable<PluginMetadata> FetchAllPluginsImpl()
        {
            XmlDocument xmlDocument;

            xmlDocument = new XmlDocument();
            xmlDocument.LoadXml(File.ReadAllText("settings.xml"));

            foreach (XmlElement xmlElemenet in
                xmlDocument.DocumentElement.SelectNodes("Plugin"))
            {
                var pluginMd = new PluginMetadata
                {
                    Guid = new Guid(xmlElemenet.GetElementsByTagName("GUID")[0].InnerText),
                    Name = xmlElemenet.GetElementsByTagName("Name")[0].InnerText,
                    Author = xmlElemenet.GetElementsByTagName("Author")[0].InnerText,
                    Version = new Version(xmlElemenet.GetElementsByTagName("Version")[0].InnerText),
                    Description = xmlElemenet.GetElementsByTagName("Description")[0].InnerText,
                    Enabled = xmlElemenet.GetElementsByTagName("Enabled")[0].InnerText == "True"
                };
                
                yield return pluginMd;
            }
        }

        public void UpdatePlugin(Guid pluginGuid, Action<PluginMetadata> action)
        {
            var pluginMd = FetchPlugin(pluginGuid);
            action(pluginMd);

            var xmlDocument = new XmlDocument();
            xmlDocument.LoadXml(File.ReadAllText("settings.xml"));
            foreach (XmlElement xmlElement in xmlDocument.GetElementsByTagName("Plugin"))
            {
                if (xmlElement["GUID"].InnerText != pluginGuid.ToString())
                    continue;
                xmlElement["Name"].InnerText = pluginMd.Name;
                xmlElement["Author"].InnerText = pluginMd.Author;
                xmlElement["Version"].InnerText = pluginMd.Version.ToString();
                xmlElement["Description"].InnerText = pluginMd.Description;
                xmlElement["Enabled"].InnerText = pluginMd.Enabled ? "True" : "False";
            }
            xmlDocument.Save("settings.xml");
        }

        public void TogglePluginStatus(Guid pluginGuid, bool status)
        {
            UpdatePlugin(pluginGuid, p => p.Enabled = status);
        }
    }
}