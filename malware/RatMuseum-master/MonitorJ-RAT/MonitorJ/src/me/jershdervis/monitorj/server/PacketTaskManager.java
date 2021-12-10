package me.jershdervis.monitorj.server;

import me.jershdervis.monitorj.eventapi.EventManager;
import me.jershdervis.monitorj.eventapi.EventTarget;
import me.jershdervis.monitorj.eventapi.events.EventReceivePacket;
import me.jershdervis.monitorj.server.packets.*;

import java.io.IOException;
import java.util.ArrayList;

/**
 * Created by Josh on 19/06/2015.
 */
public class PacketTaskManager {

    /**
     * Stores all packet task reaction classes
     */
    private ArrayList<PacketTask> packetTasks = new ArrayList<PacketTask>();

    /**
     * Initiated in the constructor this is used to return this classes public access.
     */
    private static PacketTaskManager instance;

    /**
     * The PacketTaskManager instance variable is initialized
     * Register this class to listen to incoming events
     * All packet task reactions are loaded to the packetTasks ArrayList
     */
    public PacketTaskManager() {
        instance = this;
        EventManager.register(this);
        this.addPacketTask(new PingTask());
        this.addPacketTask(new RemoteChatMessage());
        this.addPacketTask(new RemoteDesktopImage());
        this.addPacketTask(new RemoteMicSample());
    }

    /**
     * Returns the initiated class object of PacketTaskManager
     * @return
     */
    public static PacketTaskManager getInstance() {
        return instance;
    }

    /**
     * This method is run when a recognized packet is received from the client connection
     * This method will find a PacketTask class to execute its run method with the matched
     * packetID of an incoming Packet
     * @param event
     */
    @EventTarget
    public void onReceivePacket(EventReceivePacket event) {
        for(PacketTask task : packetTasks) {
            if(event.getPacketID() == task.getPacketID()) {
                BaseServerClient client = event.getClient();
                try {
                    task.run(client);
                    client.getDataOutputStream().flush();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    /**
     * Adds a class that extends PacketTask to the packetTasks ArrayList
     * @param packetTask
     */
    public void addPacketTask(PacketTask packetTask) {
        this.packetTasks.add(packetTask);
    }
}
