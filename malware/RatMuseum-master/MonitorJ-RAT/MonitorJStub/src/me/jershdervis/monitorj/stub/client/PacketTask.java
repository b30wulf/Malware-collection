package me.jershdervis.monitorj.stub.client;

import java.io.IOException;

/**
 * Created by Josh on 19/06/2015.
 */
public abstract class PacketTask {

    /**
     * Stores the Packets ID
     */
    private final int packetID;

    public PacketTask(int packetID) {
        this.packetID = packetID;
    }

    /**
     * Called when a request with the Tasks Packet ID is received from server
     * @param client
     * @throws IOException
     */
    public abstract void run(BaseClient client) throws IOException;

    /**
     * Gets the Packet ID
     * @return
     */
    public int getPacketID() {
        return this.packetID;
    }
}
