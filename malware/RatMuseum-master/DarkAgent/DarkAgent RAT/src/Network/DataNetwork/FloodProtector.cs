using System;
using System.Collections.Generic;
using System.Text;
using System.Net;
using System.Diagnostics;

namespace DarkAgent_RAT.src.Network.DataNetwork
{
    class FloodProtector
    {
        private SortedList<EndPoint, FloodCounter> floods;
        private int maxPacketASecond;
        private int msecTime;

        public FloodProtector(int MaxPacketASecond, int Time)
        {
            floods = new SortedList<EndPoint, FloodCounter>();
            maxPacketASecond = MaxPacketASecond;
            msecTime = Time;
        }

        public bool HandleFlood(EndPoint id)
        {
            if (floods.ContainsKey(id))
            {
                FloodCounter counter = floods[id];
                counter.PacketCount += 1;
                floods[id] = counter;
                if (counter.StopWatch.ElapsedMilliseconds > msecTime)
                {
                    floods.Remove(id);
                }
                else if (counter.PacketCount > maxPacketASecond)
                {
                    floods.Remove(id);
                    return false;
                }
            }
            else
            {
                FloodCounter counter = new FloodCounter();
                counter.PacketCount = 1;
                counter.StopWatch = Stopwatch.StartNew();
                floods.Add(id, counter);
            }
            return true;
        }

        struct FloodCounter
        {
            public Stopwatch StopWatch { get; set; }
            public int PacketCount;
        }
    }
}
