using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Utils;
using System.Threading;

namespace DarkAgent_Client.src.Features
{
    public class SysMutex
    {
        public static bool CheckMutex()
        {
            if (Settings.Mutex_Enable)
            {
                Mutex mutex;
                string MutexString = "";
                if(Settings.Mutex_MUTEX.Length > 0)
                    MutexString = Settings.Mutex_MUTEX;
                else
                {
                    byte[] buffer = new byte[20];
                    Random rnd = new Random();

                    for(int i = 0; i < buffer.Length; i++)
                        buffer[i] = (byte)rnd.Next(70, 122);
                    MutexString = ASCIIEncoding.ASCII.GetString(buffer);
                }
                try
                {
                    mutex = Mutex.OpenExisting(MutexString);
                    return false;
                }
                catch
                {
                    mutex = new Mutex(true, MutexString);
                    return true;
                }
            }
            return false;
        }
    }
}