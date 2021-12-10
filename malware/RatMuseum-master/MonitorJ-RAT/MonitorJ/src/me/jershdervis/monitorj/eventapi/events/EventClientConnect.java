package me.jershdervis.monitorj.eventapi.events;

import me.jershdervis.monitorj.eventapi.Event;
import me.jershdervis.monitorj.server.BaseServer;
import me.jershdervis.monitorj.server.BaseServerClient;

/**
 * Created by Josh on 18/06/2015.
 */
public final class EventClientConnect extends Event {

    private BaseServer host;
    private BaseServerClient client;

    public Event call(BaseServer host, BaseServerClient client) {
        this.host = host;
        this.client = client;
        return super.call();
    }

    public BaseServer getClientServer() {
        return this.host;
    }

    public BaseServerClient getClient() {
        return this.client;
    }
}
