package me.jershdervis.monitorj.ui;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.Packets;

import java.io.IOException;
import java.util.ArrayList;

/**
 * Created by Josh on 26/07/2015.
 */
public class ShutdownHook extends Thread {

    @Override
    public void run() {
        ArrayList<BaseServerClient> connections = MonitorJ.getInstance().getServerManager().getAllClients();
        for(BaseServerClient client : connections) {
            try {
                client.getDataOutputStream().writeByte(Packets.DISCONNECT_CLIENT.getPacketID());
            } catch (IOException e) {
                e.printStackTrace();
            } finally {
                try {
                    client.getDataOutputStream().close();
                    client.getDataInputStream().close();
                    client.getClientSocket().close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }
}
