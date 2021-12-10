using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Objects
{
    public class CpuInfo
    {
        public byte Cpu1Usage { get; set; }
        public byte Cpu2Usage { get; set; }
        public byte Cpu3Usage { get; set; }
        public byte Cpu4Usage { get; set; }
        public byte Cpu1C1 { get; set; }
        public byte Cpu1C2 { get; set; }
        public byte Cpu1C3 { get; set; }
        public byte Cpu2C1 { get; set; }
        public byte Cpu2C2 { get; set; }
        public byte Cpu2C3 { get; set; }
        public byte Cpu3C1 { get; set; }
        public byte Cpu3C2 { get; set; }
        public byte Cpu3C3 { get; set; }
        public byte Cpu4C1 { get; set; }
        public byte Cpu4C2 { get; set; }
        public byte Cpu4C3 { get; set; }
        public byte Cpu1DPC { get; set; }
        public byte Cpu2DPC { get; set; }
        public byte Cpu3DPC { get; set; }
        public byte Cpu4DPC { get; set; }
        public byte Cpu1Idle { get; set; }
        public byte Cpu2Idle { get; set; }
        public byte Cpu3Idle { get; set; }
        public byte Cpu4Idle { get; set; }
        public byte Cpu1Interrupt { get; set; }
        public byte Cpu2Interrupt { get; set; }
        public byte Cpu3Interrupt { get; set; }
        public byte Cpu4Interrupt { get; set; }
        public byte Cpu1Privileged { get; set; }
        public byte Cpu2Privileged { get; set; }
        public byte Cpu3Privileged { get; set; }
        public byte Cpu4Privileged { get; set; }
        public byte Cpu1User { get; set; }
        public byte Cpu2User { get; set; }
        public byte Cpu3User { get; set; }
        public byte Cpu4User { get; set; }
        public int CPU1TransitionC1 { get; set; }
        public int CPU1TransitionC2 { get; set; }
        public int CPU1TransitionC3 { get; set; }
        public int CPU2TransitionC1 { get; set; }
        public int CPU2TransitionC2 { get; set; }
        public int CPU2TransitionC3 { get; set; }
        public int CPU3TransitionC1 { get; set; }
        public int CPU3TransitionC2 { get; set; }
        public int CPU3TransitionC3 { get; set; }
        public int CPU4TransitionC1 { get; set; }
        public int CPU4TransitionC2 { get; set; }
        public int CPU4TransitionC3 { get; set; }
        public int CPU1DpcRate { get; set; }
        public int CPU2DpcRate { get; set; }
        public int CPU3DpcRate { get; set; }
        public int CPU4DpcRate { get; set; }
        public int CPU1DpcQueued { get; set; }
        public int CPU2DpcQueued { get; set; }
        public int CPU3DpcQueued { get; set; }
        public int CPU4DpcQueued { get; set; }
        public int CPU1InterruptsSec { get; set; }
        public int CPU2InterruptsSec { get; set; }
        public int CPU3InterruptsSec { get; set; }
        public int CPU4InterruptsSec { get; set; }
        public short Cpu1Frequency { get; set; }
        public short Cpu2Frequency { get; set; }
        public short Cpu3Frequency { get; set; }
        public short Cpu4Frequency { get; set; }
    }
}
