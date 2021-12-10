package me.jershdervis.monitorj.eventapi.events;

import me.jershdervis.monitorj.eventapi.Event;
import me.jershdervis.monitorj.server.BaseServerClient;

/**
 * Created by Josh on 19/06/2015.
 */
public class EventReceivePacket extends Event {

    private int packetID;
    private BaseServerClient client;

    public Event call(int packetID, BaseServerClient client) {
        this.packetID = packetID;
        this.client = client;
        return super.call();
    }

    public int getPacketID() {
        return this.packetID;
    }

    public BaseServerClient getClient() {
        return this.client;
    }
}
