package me.jershdervis.monitorj.server;

import java.io.IOException;

/**
 * Created by Josh on 19/06/2015.
 */
public abstract class PacketTask {

    /**
     * The value of the packet identifier to be sent to client
     */
    private final int packetID;

    /**
     * The packet identifiers value is set in this constructor
     * @param packetID
     */
    public PacketTask(int packetID) {
        this.packetID = packetID;
    }

    /**
     * This method is called when a packet is received with the packetID's value
     * @param client
     * @throws IOException
     */
    public abstract void run(BaseServerClient client) throws IOException;

    /**
     * Returns this classes packetID
     * @return
     */
    public int getPacketID() {
        return this.packetID;
    }
}
