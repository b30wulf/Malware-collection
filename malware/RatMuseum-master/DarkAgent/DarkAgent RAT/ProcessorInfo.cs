using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Threading;
using DarkAgent_RAT.src.Events;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_RAT
{
    public partial class ProcessorInfo : DevComponents.DotNetBar.Office2007Form
    {
        public ProcessorInfo()
        {
            InitializeComponent();
        }

        private Thread thread;
        private bool booly = true;

        public string RemoteIP;
        public string ClientName;
        public int ClientID;

        #region Speficic Events

        public void onGetCpuInfo(object o, CpuInfoEventArgs e)
        {
            try
            {
                if (e.RemoteIP != RemoteIP)
                    return;

                ProgressBar1.Value = e.cpuInfo.Cpu1Usage;
                Label74.Text = e.cpuInfo.Cpu1Usage + "%";

                ProgressBar2.Value = e.cpuInfo.Cpu2Usage;
                Label75.Text = e.cpuInfo.Cpu2Usage + "%";

                ProgressBar3.Value = e.cpuInfo.Cpu3Usage;
                Label76.Text = e.cpuInfo.Cpu3Usage + "%";

                ProgressBar4.Value = e.cpuInfo.Cpu4Usage;
                Label77.Text = e.cpuInfo.Cpu4Usage + "%";

                ProgressBar5.Value = e.cpuInfo.Cpu1C1;
                ProgressBar6.Value = e.cpuInfo.Cpu1C2;
                ProgressBar7.Value = e.cpuInfo.Cpu1C3;

                ProgressBar8.Value = e.cpuInfo.Cpu2C1;
                ProgressBar9.Value = e.cpuInfo.Cpu2C2;
                ProgressBar10.Value = e.cpuInfo.Cpu2C3;

                ProgressBar11.Value = e.cpuInfo.Cpu3C1;
                ProgressBar12.Value = e.cpuInfo.Cpu3C2;
                ProgressBar13.Value = e.cpuInfo.Cpu3C3;

                ProgressBar14.Value = e.cpuInfo.Cpu4C1;
                ProgressBar15.Value = e.cpuInfo.Cpu4C2;
                ProgressBar16.Value = e.cpuInfo.Cpu4C3;

                ProgressBar17.Value = e.cpuInfo.Cpu1DPC;
                ProgressBar18.Value = e.cpuInfo.Cpu2DPC;
                ProgressBar19.Value = e.cpuInfo.Cpu3DPC;
                ProgressBar20.Value = e.cpuInfo.Cpu4DPC;

                ProgressBar21.Value = e.cpuInfo.Cpu1Idle;
                ProgressBar22.Value = e.cpuInfo.Cpu2Idle;
                ProgressBar23.Value = e.cpuInfo.Cpu3Idle;
                ProgressBar24.Value = e.cpuInfo.Cpu4Idle;

                ProgressBar25.Value = e.cpuInfo.Cpu1Interrupt;
                ProgressBar26.Value = e.cpuInfo.Cpu2Interrupt;
                ProgressBar27.Value = e.cpuInfo.Cpu3Interrupt;
                ProgressBar28.Value = e.cpuInfo.Cpu4Interrupt;

                ProgressBar29.Value = e.cpuInfo.Cpu1Privileged;
                ProgressBar30.Value = e.cpuInfo.Cpu2Privileged;
                ProgressBar31.Value = e.cpuInfo.Cpu3Privileged;
                ProgressBar32.Value = e.cpuInfo.Cpu4Privileged;

                ProgressBar33.Value = e.cpuInfo.Cpu1User;
                ProgressBar34.Value = e.cpuInfo.Cpu2User;
                ProgressBar35.Value = e.cpuInfo.Cpu3User;
                ProgressBar36.Value = e.cpuInfo.Cpu4User;


                if (ProgressBar37.Maximum < e.cpuInfo.CPU1TransitionC1)
                    ProgressBar37.Maximum = e.cpuInfo.CPU1TransitionC1;

                if (ProgressBar38.Maximum < e.cpuInfo.CPU1TransitionC2)
                    ProgressBar38.Maximum = e.cpuInfo.CPU1TransitionC2;

                if (ProgressBar39.Maximum < e.cpuInfo.CPU1TransitionC3)
                    ProgressBar39.Maximum = e.cpuInfo.CPU1TransitionC3;

                if (ProgressBar40.Maximum < e.cpuInfo.CPU2TransitionC1)
                    ProgressBar40.Maximum = e.cpuInfo.CPU2TransitionC1;

                if (ProgressBar41.Maximum < e.cpuInfo.CPU2TransitionC2)
                    ProgressBar41.Maximum = e.cpuInfo.CPU2TransitionC2;

                if (ProgressBar42.Maximum < e.cpuInfo.CPU2TransitionC3)
                    ProgressBar42.Maximum = e.cpuInfo.CPU2TransitionC3;

                if (ProgressBar43.Maximum < e.cpuInfo.CPU3TransitionC1)
                    ProgressBar43.Maximum = e.cpuInfo.CPU3TransitionC1;

                if (ProgressBar44.Maximum < e.cpuInfo.CPU3TransitionC2)
                    ProgressBar44.Maximum = e.cpuInfo.CPU3TransitionC2;

                if (ProgressBar45.Maximum < e.cpuInfo.CPU3TransitionC3)
                    ProgressBar45.Maximum = e.cpuInfo.CPU3TransitionC3;

                if (ProgressBar46.Maximum < e.cpuInfo.CPU4TransitionC1)
                    ProgressBar46.Maximum = e.cpuInfo.CPU4TransitionC1;

                if (ProgressBar47.Maximum < e.cpuInfo.CPU4TransitionC2)
                    ProgressBar47.Maximum = e.cpuInfo.CPU4TransitionC2;

                if (ProgressBar48.Maximum < e.cpuInfo.CPU4TransitionC3)
                    ProgressBar48.Maximum = e.cpuInfo.CPU4TransitionC3;




                ProgressBar37.Value = e.cpuInfo.CPU1TransitionC1;
                ProgressBar38.Value = e.cpuInfo.CPU1TransitionC2;
                ProgressBar39.Value = e.cpuInfo.CPU1TransitionC3;

                ProgressBar40.Value = e.cpuInfo.CPU2TransitionC1;
                ProgressBar41.Value = e.cpuInfo.CPU2TransitionC2;
                ProgressBar42.Value = e.cpuInfo.CPU2TransitionC3;

                ProgressBar43.Value = e.cpuInfo.CPU3TransitionC1;
                ProgressBar44.Value = e.cpuInfo.CPU3TransitionC2;
                ProgressBar45.Value = e.cpuInfo.CPU3TransitionC3;

                ProgressBar46.Value = e.cpuInfo.CPU4TransitionC1;
                ProgressBar47.Value = e.cpuInfo.CPU4TransitionC2;
                ProgressBar48.Value = e.cpuInfo.CPU4TransitionC3;

                if (ProgressBar49.Maximum < e.cpuInfo.CPU1DpcRate)
                    ProgressBar49.Maximum = e.cpuInfo.CPU1DpcRate;

                if (ProgressBar50.Maximum < e.cpuInfo.CPU2DpcRate)
                    ProgressBar50.Maximum = e.cpuInfo.CPU2DpcRate;

                if (ProgressBar51.Maximum < e.cpuInfo.CPU3DpcRate)
                    ProgressBar51.Maximum = e.cpuInfo.CPU3DpcRate;

                if (ProgressBar52.Maximum < e.cpuInfo.CPU4DpcRate)
                    ProgressBar52.Maximum = e.cpuInfo.CPU4DpcRate;


                if (ProgressBar53.Maximum < e.cpuInfo.CPU1DpcQueued)
                    ProgressBar53.Maximum = e.cpuInfo.CPU1DpcQueued;

                if (ProgressBar54.Maximum < e.cpuInfo.CPU2DpcQueued)
                    ProgressBar54.Maximum = e.cpuInfo.CPU2DpcQueued;

                if (ProgressBar55.Maximum < e.cpuInfo.CPU3DpcQueued)
                    ProgressBar55.Maximum = e.cpuInfo.CPU3DpcQueued;

                if (ProgressBar56.Maximum < e.cpuInfo.CPU4DpcQueued)
                    ProgressBar56.Maximum = e.cpuInfo.CPU4DpcQueued;




                ProgressBar49.Value = e.cpuInfo.CPU1DpcRate;
                ProgressBar50.Value = e.cpuInfo.CPU2DpcRate;
                ProgressBar51.Value = e.cpuInfo.CPU3DpcRate;
                ProgressBar52.Value = e.cpuInfo.CPU4DpcRate;

                ProgressBar53.Value = e.cpuInfo.CPU1DpcQueued;
                ProgressBar54.Value = e.cpuInfo.CPU2DpcQueued;
                ProgressBar55.Value = e.cpuInfo.CPU3DpcQueued;
                ProgressBar56.Value = e.cpuInfo.CPU4DpcQueued;

                Label15.Text = "Interrupts /sec: " + e.cpuInfo.CPU1InterruptsSec;
                Label27.Text = "Interrupts /sec: " + e.cpuInfo.CPU2InterruptsSec;
                Label45.Text = "Interrupts /sec: " + e.cpuInfo.CPU3InterruptsSec;
                Label63.Text = "Interrupts /sec: " + e.cpuInfo.CPU4InterruptsSec;
                Label16.Text = "Frequency: " + e.cpuInfo.Cpu1Frequency + " mhz";
                Label28.Text = "Frequency: " + e.cpuInfo.Cpu2Frequency + " mhz";
                Label46.Text = "Frequency: " + e.cpuInfo.Cpu3Frequency + " mhz";
                Label64.Text = "Frequency: " + e.cpuInfo.Cpu4Frequency + " mhz";
            }
            catch { }
        }

        public void onClientDisconnect(object o, ClientDisconnectEventArgs e)
        {
            if (RemoteIP == e.RemoteIP)
            {
                MessageBox.Show(ClientName + " has been disconnected from the server");
                this.Close();
            }
        }

        #endregion

        #region Other

        private void RefreshCpu()
        {
            do
            {
                //When this is sended too fast the client will send many querys to WMI
                //Result will be like 10-50% cpu usage
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetCpuInfo(RatClientProcessor.Instance._clientList[ClientID]));
                Thread.Sleep(5000);
            }while (booly);
            
        }

        #endregion

        #region Form Events

        private void ProcessorInfo_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - Processor Information [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "]";
            thread = new Thread(new ThreadStart(RefreshCpu));
            thread.Start();

            CpuInfoEvent.CpuInfo += new CpuInfoHandler(onGetCpuInfo);
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);
        }

        private void ProcessorInfo_FormClosing(object sender, FormClosingEventArgs e)
        {
            booly = false;
            CpuInfoEvent.CpuInfo -= new CpuInfoHandler(onGetCpuInfo);
            ClientDisconnectEvent.ClientDisconnect -= new ClientDisconnectHandler(onClientDisconnect);
        }

        #endregion
    }
}
