package me.jershdervis.monitorj.stub.eventapi.events;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.eventapi.Event;

/**
 * Created by Josh on 19/06/2015.
 */
public class EventDisconnect extends Event {

    private BaseClient clientConnection;

    public Event call(BaseClient client) {
        this.clientConnection = client;
        return super.call();
    }

    public BaseClient getBaseClient() {
        return this.clientConnection;
    }
}
