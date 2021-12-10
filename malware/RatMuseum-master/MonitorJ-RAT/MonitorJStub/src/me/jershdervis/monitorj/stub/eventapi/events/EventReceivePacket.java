package me.jershdervis.monitorj.stub.eventapi.events;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.eventapi.Event;

import java.io.DataInputStream;
import java.io.DataOutputStream;

/**
 * Created by Josh on 19/06/2015.
 */
public class EventReceivePacket extends Event {

    private int packetID;
    private BaseClient client;

    public Event call(int packetID, BaseClient client) {
        this.packetID = packetID;
        this.client = client;
        return super.call();
    }

    public int getPacketID() {
        return this.packetID;
    }

    public BaseClient getClient() {
        return this.client;
    }
}
