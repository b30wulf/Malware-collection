package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;

import java.io.IOException;

/**
 * Created by Josh on 21/06/2015.
 */
public class DisconnectClientTask extends PacketTask {

    public DisconnectClientTask() {
        super(Packets.DISCONNECT_CLIENT.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        client.getServerSocketConnection().close();
        //Connection will automatically re-establish itself in the BaseClient class Thread.
    }
}
