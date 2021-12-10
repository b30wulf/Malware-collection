using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Engines;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_GetCpuInfo : SendBasePacket
    {
        public S_GetCpuInfo(ClientConnect client)
            : base(client)
        {
        }

        protected internal override void Write()
        {
            WriteByte(7);
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentProcessorTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentProcessorTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentProcessorTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentProcessorTime", null))); }catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentProcessorTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentProcessorTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentProcessorTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentProcessorTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC1Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentC1Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC2Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentC2Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC3Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentC3Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC1Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentC1Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC2Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentC2Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC3Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentC3Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC1Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentC1Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC2Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentC2Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC3Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentC3Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC1Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentC1Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC2Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentC2Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentC3Time from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentC3Time", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentDPCTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentDPCTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentDPCTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentDPCTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentDPCTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentDPCTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentDPCTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentDPCTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentIdleTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentIdleTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentIdleTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentIdleTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentIdleTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentIdleTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentIdleTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentIdleTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentInterruptTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentInterruptTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentInterruptTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentInterruptTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentInterruptTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentInterruptTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentInterruptTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentInterruptTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentPrivilegedTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentPrivilegedTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentPrivilegedTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentPrivilegedTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentPrivilegedTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentPrivilegedTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentPrivilegedTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentPrivilegedTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentUserTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "PercentUserTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentUserTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "PercentUserTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentUserTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "PercentUserTime", null))); } catch { WriteByte(0); }
            try { WriteByte(byte.Parse(WMI.ExecuteQuery("select PercentUserTime from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "PercentUserTime", null))); } catch { WriteByte(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C1TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "C1TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C2TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "C2TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C3TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "C3TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C1TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "C1TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C2TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "C2TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C3TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "C3TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C1TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "C1TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C2TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "C2TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C3TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "C3TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C1TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "C1TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C2TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "C2TransitionsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select C3TransitionsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "C3TransitionsPersec", null))); } catch { WriteInteger(0); }

            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCRate from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "DPCRate", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCRate from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "DPCRate", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCRate from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "DPCRate", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCRate from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "DPCRate", null))); } catch { WriteInteger(0); }

            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCsQueuedPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "DPCsQueuedPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCsQueuedPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "DPCsQueuedPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCsQueuedPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "DPCsQueuedPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select DPCsQueuedPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "DPCsQueuedPersec", null))); } catch { WriteInteger(0); }

            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select InterruptsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,0'", "InterruptsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select InterruptsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,1'", "InterruptsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select InterruptsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,2'", "InterruptsPersec", null))); } catch { WriteInteger(0); }
            try { WriteInteger(Int32.Parse(WMI.ExecuteQuery("select InterruptsPersec from Win32_PerfFormattedData_Counters_ProcessorInformation WHERE Name='0,3'", "InterruptsPersec", null))); } catch { WriteInteger(0); }

            try { WriteShort(Int16.Parse(WMI.ExecuteQuery("select CurrentClockSpeed from Win32_PerfFormattedData_Counters_ProcessorInformation", "CurrentClockSpeed", null))); } catch { WriteShort(0); }
            try { WriteShort(Int16.Parse(WMI.ExecuteQuery("select CurrentClockSpeed from Win32_PerfFormattedData_Counters_ProcessorInformation", "CurrentClockSpeed", null))); } catch { WriteShort(0); }
            try { WriteShort(Int16.Parse(WMI.ExecuteQuery("select CurrentClockSpeed from Win32_PerfFormattedData_Counters_ProcessorInformation", "CurrentClockSpeed", null))); } catch { WriteShort(0); }
            try { WriteShort(Int16.Parse(WMI.ExecuteQuery("select CurrentClockSpeed from Win32_PerfFormattedData_Counters_ProcessorInformation", "CurrentClockSpeed", null))); } catch { WriteShort(0); }

        }
    }
}