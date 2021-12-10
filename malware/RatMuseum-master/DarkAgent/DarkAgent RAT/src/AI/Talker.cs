using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Events;
using System.Speech.Synthesis;
using System.Threading;
using DarkAgent_RAT.src.Utils;

namespace DarkAgent_RAT.src.AI
{
    public class Talker
    {
        //This is not really an AI but i'm thinking about creating a real 1
        //For this RAT
        //An self thinking RAT would be that frickin awsome

        static Talker instance = null;
        static readonly object padlock = new object();

        public static Talker Instance
        {
            get { lock (padlock) { if (instance == null)instance = new Talker(); return instance; } }
        }

        public Talker()
        {
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);
        }

        private void Speak(string text)
        {
            if(settings.AI)
            {
                Thread thread = new Thread(new ParameterizedThreadStart(Speakthread));
                thread.Start(text);
            }
        }

        private void Speakthread(object text)
        {
            SpeechSynthesizer speaker = new SpeechSynthesizer();
            speaker.Rate = -3;
            speaker.Volume = 100;
            speaker.SelectVoiceByHints(VoiceGender.Male, VoiceAge.Child);
            speaker.Speak((string)text);
        }

        public void onClientDisconnect(object o, ClientDisconnectEventArgs e)
        {
            Speak("A client has been disconnected");
        }

        public void Hello()
        {
            Speak("Hello im your AI also known as Artificial Intelligence i will gui de you through if things are not going your way have a nice day");
        }
    }
}
