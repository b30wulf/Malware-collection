package me.jershdervis.monitorj.server;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.eventapi.EventTarget;
import me.jershdervis.monitorj.eventapi.events.EventClientConnect;
import me.jershdervis.monitorj.eventapi.events.EventClientDisconnect;
import me.jershdervis.monitorj.ui.components.Toaster;
import me.jershdervis.monitorj.util.ResourceLoader;

import javax.net.ServerSocketFactory;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.*;
import java.util.ArrayList;

/**
 * Created by Josh on 18/06/2015.
 */
public class BaseServer implements Runnable {

    /**
     * Stores an ArrayList of all BaseServerClient connections
     */
    private ArrayList<BaseServerClient> clientList = ServerManager.instance.allClients;

    /**
     * ServerSocket object which is initialized in the class constructor
     */
    private final ServerSocket serverSocket;

    /**
     * Initializes the serverSocket variable and binds the ServerSocket to port
     * @param port
     * @throws IOException
     */
    public BaseServer(int port) throws IOException {
        this.serverSocket = new ServerSocket(port);
    }

    /**
     * Listens to incoming client connections while the socket is open
     * When a client connection is received a new Thread is allocated to this
     * client and ran through the BaseServerClient class where the rest of
     * communication between client and server is handled.
     */
    @Override
    public void run() {
        try {
            while (!this.serverSocket.isClosed())
                new Thread(new BaseServerClient(this, this.serverSocket.accept())).start();
        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            if(this.serverSocket != null && !this.serverSocket.isClosed()) {
                try {
                    this.serverSocket.close();
                    System.out.println("Closed Socket Server");
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }
    }

    /**
     * This method is ran when the EventClientConnect is called
     * This is called when a client connects to the server from
     * within the BaseServerClient class thread
     * This method adds new clients to the clientList ArrayList
     * it also calculates a clients ping when they connect
     * @param event
     */
    @EventTarget
    public void onClientConnect(EventClientConnect event) {
        if(event.getClientServer() == this) {
            System.out.println("NEW CLIENT CONNECTION ON PORT: " + event.getClientServer().getServerSocket().getLocalPort());
            this.clientList.add(event.getClient());
            event.getClient().lastPingTime = System.currentTimeMillis();
            try {
                event.getClient().getDataOutputStream().writeByte(Packets.PING.getPacketID());
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    /**
     * This method is ran when the EventClientDisconnect is called
     * This is called when a client disconnects from the server within
     * the BaseServerClient class thread
     * @param event
     */
    @EventTarget
    public void onClientDisconnect(EventClientDisconnect event) {
        if(event.getClientServer() == this) {
            System.out.println("LOST CLIENT CONNECTION ON PORT: " + event.getClientServer().getServerSocket().getLocalPort());
            this.clientList.remove(event.getClient());
            ((DefaultTableModel) MonitorJ.getInstance().getUi().clientListTable.getModel()).removeRow(ServerManager.instance.getRowByClient(event.getClient()));
            Toaster toaster = new Toaster();
            toaster.setToasterMessageFont(new Font("Verdana", Font.PLAIN, 14));
            toaster.setToasterHeight(46);
            toaster.showToaster(
                    ResourceLoader.CLIENT_DISCONNECT,
                    "Lost Connection:\n"
                            + event.getClient().CLIENT_PC_NAME + ":" + event.getClient().CLIENT_USER_NAME);
        }
    }

    /**
     * Gets ArrayList of BaseServerClient objects
     * @return
     */
    public ArrayList<BaseServerClient> getClientList() {
        return this.clientList;
    }

    /**
     * Gets the ServerSocket Object
     * @return
     */
    public ServerSocket getServerSocket() {
        return this.serverSocket;
    }
}
