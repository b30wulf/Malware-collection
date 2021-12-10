package me.jershdervis.monitorj.stub.client;

import me.jershdervis.monitorj.stub.MonitorJStub;

import java.io.*;
import java.net.*;

/**
 * Created by Josh on 18/06/2015.
 */
public class BaseClient implements Runnable {

    private final long RECONNECT_DELAY = 10000L;

    private Socket serverSocketConnection;
    private DataOutputStream dataOutputStream;
    private DataInputStream dataInputStream;

    private final String address;
    private final int port;

    public BaseClient(String address, int port) throws IOException {
        this.address = address;
        this.port = port;
    }

    @Override
    public void run() {
        reconnect:
        while (true) {
            /**
             * Establish connection to server
             * If fails it will wait the delayed time and rerun through here
             */
            try {
                this.serverSocketConnection = this.connect(this.address, this.port);
                this.dataOutputStream = new DataOutputStream(this.serverSocketConnection.getOutputStream());
                this.dataInputStream = new DataInputStream(this.serverSocketConnection.getInputStream());
                System.out.println("Connection Success!");
            } catch (IOException e) {
                e.printStackTrace();

                this.delayReconnection();
                continue reconnect;
            }

            /**
             * Connection has been established successfully
             * Call the Connect Event
             */
            MonitorJStub.getInstance().EVENT_CONNECT.call(this);

            /**
             * While the current socket isn't closed
             */
            while (!this.serverSocketConnection.isClosed()) {
                int packet;
                try {
                    while((packet = this.dataInputStream.readByte()) < 0)
                        MonitorJStub.getInstance().EVENT_RECEIVE_PACKET.call(packet, this);
                } catch (IOException e) {
                    e.printStackTrace();
                }

                /**
                 * Calls the disconnect event as well as starting the reconnection process.
                 */
                MonitorJStub.getInstance().EVENT_DISCONNECT.call(this);
                this.delayReconnection();
                continue reconnect;
            }

            this.delayReconnection();
            continue reconnect; //Retry connection
        }
    }

    /**
     * Resolves the compiled stubs ip.
     * @param address
     * @return
     * @throws MalformedURLException
     * @throws UnknownHostException
     */
    private String addressToIp(String address) throws MalformedURLException, UnknownHostException {
        boolean containsProtocol = address.toLowerCase().contains("http://");

        String externalIp = containsProtocol ?
                InetAddress.getByName(new URL(this.address).getHost()).getHostAddress() :
                InetAddress.getByName(new URL("http://" + this.address).getHost()).getHostAddress();

        try {
            String myExternalIp = new BufferedReader(new InputStreamReader(
                    new URL("http://checkip.amazonaws.com/").openStream())).readLine();
            if(externalIp.equals(myExternalIp))
                return "127.0.0.1";
        } catch (IOException e) {

        }
        return externalIp;
    }

    /**
     * Used to delay client reconnection
     */
    private void delayReconnection() {
        try {
            Thread.sleep(this.RECONNECT_DELAY);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }

    /**
     * Create Socket connection. Returns the socket that was created.
     * @param address
     * @param port
     * @return
     * @throws IOException
     */
    private Socket connect(String address, int port) throws IOException {
        address = this.addressToIp(address);
        System.out.println("Attempting to connect to " + address + ":" + port);
        return new Socket(address, port);
    }

    /**
     * Gets the Socket Object the the current server
     * @return
     */
    public Socket getServerSocketConnection() {
        return this.serverSocketConnection;
    }

    /**
     * Gets the DataOutputStream Object of the current server
     * @return
     */
    public DataOutputStream getDataOutputStream() {
        return this.dataOutputStream;
    }

    /**
     * Gets the DataInputStream Object of the current server
     * @return
     */
    public DataInputStream getDataInputStream() {
        return this.dataInputStream;
    }
}
