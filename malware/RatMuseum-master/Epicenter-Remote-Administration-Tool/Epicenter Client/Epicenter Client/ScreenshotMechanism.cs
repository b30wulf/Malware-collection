using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;
using System.Drawing.Imaging;
using System.Drawing;
using System.IO;

namespace Epicenter_Client
{
    class ScreenshotMechanism
    {
        public static MemoryStream latestScreenshot = new MemoryStream();


        public static bool TakeScreenshot(int quality_quotient)
        {
            Bitmap resultant = null;
            Graphics gfxScreenshot = null;
            latestScreenshot = new MemoryStream();

            try
            {
                resultant = new Bitmap(Screen.PrimaryScreen.Bounds.Width, Screen.PrimaryScreen.Bounds.Height, PixelFormat.Format32bppArgb);
                gfxScreenshot = Graphics.FromImage(resultant);
                gfxScreenshot.CopyFromScreen(Screen.PrimaryScreen.Bounds.X, Screen.PrimaryScreen.Bounds.Y, 0, 0, Screen.PrimaryScreen.Bounds.Size, CopyPixelOperation.SourceCopy);
            }
            catch
            {
                return false;
            }

            ImageCodecInfo jgpEncoder = GetEncoder(ImageFormat.Jpeg);
            System.Drawing.Imaging.Encoder myEncoder = System.Drawing.Imaging.Encoder.Quality;
            EncoderParameters myEncoderParameters = new EncoderParameters(1);

            // Setting the picture quality
            EncoderParameter myEncoderParameter = new EncoderParameter(myEncoder, (long)quality_quotient);
            myEncoderParameters.Param[0] = myEncoderParameter;

            resultant.Save(latestScreenshot, jgpEncoder, myEncoderParameters);
            


            return true;
        }

        private static ImageCodecInfo GetEncoder(ImageFormat format)
        {
            ImageCodecInfo[] codecs = ImageCodecInfo.GetImageDecoders();

            foreach (ImageCodecInfo codec in codecs)
            {
                if (codec.FormatID == format.Guid)
                {
                    return codec;
                }
            }
            return null;
        }
    }
}
