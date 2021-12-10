package me.jershdervis.monitorj.server;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.ui.components.RemoteChatFrame;
import me.jershdervis.monitorj.ui.components.RemoteDesktopFrame;

import java.io.*;
import java.net.Socket;

/**
 * Created by Josh on 18/06/2015.
 */
public class BaseServerClient implements Runnable {

    /**
     * Set before ping packet is sent then used to calculate client ping when received
     */
    public long lastPingTime;

    /**
     * Client Information for current thread
     */
    public String CLIENT_HWID;
    public String CLIENT_PC_NAME;
    public String CLIENT_USER_NAME;
    public String CLIENT_OS;
    public String CLIENT_IP;
    public int CLIENT_PORT;
    public long CLIENT_PING;

    /**
     * Client Interface Variables initialized in constructor
     */
    private final RemoteDesktopFrame remoteDesktopFrame;
    private final RemoteChatFrame remoteChatFrame;

    /**
     * Variables Initialized in current class constructor
     */
    private final BaseServer host;
    private final Socket clientSocketConnection;
    private final DataOutputStream outputStream;
    private final DataInputStream inputStream;

    /**
     * Initializes the client connection stream
     * Establishes class variables
     * @param host
     * @param clientSocket
     * @throws IOException
     */
    public BaseServerClient(BaseServer host, Socket clientSocket) throws IOException {
        this.host = host;

        this.remoteDesktopFrame = new RemoteDesktopFrame(this);
        this.remoteChatFrame = new RemoteChatFrame(this);

        this.clientSocketConnection = clientSocket;
        this.outputStream = new DataOutputStream(clientSocket.getOutputStream());
        this.inputStream = new DataInputStream(clientSocket.getInputStream());
    }

    @Override
    public void run() {
        //Calls EventClientConnect event
        MonitorJ.getInstance().EVENT_CLIENT_CONNECT.call(host, this);

        //While the client connection socket is open
        while(!clientSocketConnection.isClosed()) {
            try {
                int packet;
                while((packet = inputStream.readByte()) < 0) {
                    //Calls EventReceivePacket with the specified packet from client
                    MonitorJ.getInstance().EVENT_RECEIVE_PACKET.call(packet, this);
                }
            } catch (IOException e) {
                e.printStackTrace();
                break;
            }
        }

        //Calls EventClientDisconnect event
        MonitorJ.getInstance().EVENT_CLIENT_DISCONNECT.call(host, this);
    }

    /**
     * Retrieves the unique desktop window for current client
     * @return
     */
    public RemoteDesktopFrame getRemoteDesktopFrame() {
        return this.remoteDesktopFrame;
    }

    /**
     * Retrieves the unique chat window for current client
     * @return
     */
    public RemoteChatFrame getRemoteChatFrame() {
        return this.remoteChatFrame;
    }

    /**
     * Return the ServerSocket the client is connected to
     * @return
     */
    public BaseServer getClientServerHost() {
        return this.host;
    }

    /**
     * Gets the Socket Object the the current client
     * @return
     */
    public Socket getClientSocket() {
        return this.clientSocketConnection;
    }

    /**
     * Gets the DataOutputStream Object of the current client Socket object
     * @return
     */
    public DataOutputStream getDataOutputStream() {
        return this.outputStream;
    }

    /**
     * Gets the DataInputStream Object of the current client Socket object
     * @return
     */
    public DataInputStream getDataInputStream() {
        return this.inputStream;
    }
}
