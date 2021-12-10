package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.util.OSCheck;

import java.io.IOException;

/**
 * Created by Josh on 2/07/2015.
 * TODO: Needs to be tested on Mac and Linux
 */
public class SleepClientSystemTask extends PacketTask {

    public SleepClientSystemTask() {
        super(Packets.SLEEP_CLIENT_SYSTEM.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        String sleepCommand = null;
        OSCheck.OSType osType = OSCheck.getOperatingSystemType();
        switch (osType) {
            case Windows:
                Runtime.getRuntime().exec("powercfg -hibernate off");
                sleepCommand = "rundll32.exe powrprof.dll,SetSuspendState 0,1,0";
                break;
            case MacOS: //Untested
                String version = System.getProperty("os.version");
                String[] splitter = version.split(".");
                boolean cmdDetermine = Integer.parseInt(splitter[0]) > 10 && Integer.parseInt(splitter[1]) > 9;
                sleepCommand = cmdDetermine ? "pmset displaysleepnow" : "pmset sleepnow";
                break;
            case Linux: //Untested
                sleepCommand = "pm-hibernate";
                break;
        }
        if(sleepCommand != null)
            Runtime.getRuntime().exec(sleepCommand);
    }
}
