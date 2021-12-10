using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_GetCpuInfo : ReceiveBasePacket
    {
        public R_GetCpuInfo(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        CpuInfo info = new CpuInfo();
        public override void Read()
        {
            info.Cpu1Usage = ReadByte();
            info.Cpu2Usage = ReadByte();
            info.Cpu3Usage = ReadByte();
            info.Cpu4Usage = ReadByte();

            info.Cpu1C1 = ReadByte();
            info.Cpu1C2 = ReadByte();
            info.Cpu1C3 = ReadByte();

            info.Cpu2C1 = ReadByte();
            info.Cpu2C2 = ReadByte();
            info.Cpu2C3 = ReadByte();

            info.Cpu3C1 = ReadByte();
            info.Cpu3C2 = ReadByte();
            info.Cpu3C3 = ReadByte();

            info.Cpu4C1 = ReadByte();
            info.Cpu4C2 = ReadByte();
            info.Cpu4C3 = ReadByte();

            info.Cpu1DPC = ReadByte();
            info.Cpu2DPC = ReadByte();
            info.Cpu3DPC = ReadByte();
            info.Cpu4DPC = ReadByte();

            info.Cpu1Idle = ReadByte();
            info.Cpu2Idle = ReadByte();
            info.Cpu3Idle = ReadByte();
            info.Cpu4Idle = ReadByte();

            info.Cpu1Interrupt = ReadByte();
            info.Cpu2Interrupt = ReadByte();
            info.Cpu3Interrupt = ReadByte();
            info.Cpu4Interrupt = ReadByte();

            info.Cpu1Privileged = ReadByte();
            info.Cpu2Privileged = ReadByte();
            info.Cpu3Privileged = ReadByte();
            info.Cpu4Privileged = ReadByte();

            info.Cpu1User = ReadByte();
            info.Cpu2User = ReadByte();
            info.Cpu3User = ReadByte();
            info.Cpu4User = ReadByte();

            info.CPU1TransitionC1 = ReadInteger();
            info.CPU1TransitionC2 = ReadInteger();
            info.CPU1TransitionC3 = ReadInteger();
            info.CPU2TransitionC1 = ReadInteger();
            info.CPU2TransitionC2 = ReadInteger();
            info.CPU2TransitionC3 = ReadInteger();
            info.CPU3TransitionC1 = ReadInteger();
            info.CPU3TransitionC2 = ReadInteger();
            info.CPU3TransitionC3 = ReadInteger();
            info.CPU4TransitionC1 = ReadInteger();
            info.CPU4TransitionC2 = ReadInteger();
            info.CPU4TransitionC3 = ReadInteger();

            info.CPU1DpcRate = ReadInteger();
            info.CPU2DpcRate = ReadInteger();
            info.CPU3DpcRate = ReadInteger();
            info.CPU4DpcRate = ReadInteger();

            info.CPU1DpcQueued = ReadInteger();
            info.CPU2DpcQueued = ReadInteger();
            info.CPU3DpcQueued = ReadInteger();
            info.CPU4DpcQueued = ReadInteger();

            info.CPU1InterruptsSec = ReadInteger();
            info.CPU2InterruptsSec = ReadInteger();
            info.CPU3InterruptsSec = ReadInteger();
            info.CPU4InterruptsSec = ReadInteger();

            info.Cpu1Frequency = ReadShort();
            info.Cpu2Frequency = ReadShort();
            info.Cpu3Frequency = ReadShort();
            info.Cpu4Frequency = ReadShort();
        }

        public override void Run()
        {
            CpuInfoEventArgs e1 = new CpuInfoEventArgs(Client.RemoteEndPoint.ToString(), info);
            CpuInfoEvent.OnCpuInfo(e1);
            info = null; //clean memory
        }
    }
}
