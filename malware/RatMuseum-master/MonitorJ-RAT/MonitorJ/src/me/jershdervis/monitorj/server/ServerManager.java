package me.jershdervis.monitorj.server;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.eventapi.EventManager;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.io.IOException;
import java.util.ArrayList;

/**
 * Created by Josh on 18/06/2015.
 */
public class ServerManager {

    /**
     * Initialized in current class constructor
     * Used to give public access of current class
     */
    public static ServerManager instance;

    /**
     * Stores all hosted BaseServers
     */
    public ArrayList<BaseServer> servers = new ArrayList<BaseServer>();

    /**
     * Stores all BaseServerClient connections
     */
    public volatile ArrayList<BaseServerClient> allClients = new ArrayList<BaseServerClient>();

    /**
     * Initializes class instance
     */
    public ServerManager() {
        if(instance == null)
            instance = this;
    }

    /**
     * Opens ServerSocket on client port
     * @param port
     * @throws IOException
     */
    public void listenOnPort(int port) throws IOException {
        BaseServer toAdd = new BaseServer(port);
        EventManager.register(toAdd);
        this.servers.add(toAdd);
        new Thread(toAdd).start();
    }

    /**
     * Closes all connections to client on hosted port and closes ServerSocket
     * @param port
     */
    public void closeServerOnPort(int port) {
        BaseServer closingServer = this.getBaseServerByPort(port);
        if(closingServer != null && !closingServer.getServerSocket().isClosed()) {
            try {
                //Disconnect all clients, so the client knows the server has been closed.
                for(BaseServerClient client : closingServer.getClientList()) {
                    client.getDataOutputStream().close();
                    client.getDataInputStream().close();
                    client.getClientSocket().close();
                    ((DefaultTableModel) MonitorJ.getInstance().getUi().clientListTable.getModel()).removeRow(getRowByClient(client));
                }
                closingServer.getServerSocket().close();
                this.servers.remove(closingServer);
                EventManager.unregister(closingServer);
            } catch (IOException e) {
                e.printStackTrace();
                System.out.println("Unable to close server.");
            }
        }
    }

    /**
     * Finds and returns BaseServer by hosted port
     * @param port
     * @return
     */
    public BaseServer getBaseServerByPort(int port) {
        for(BaseServer s : servers) {
            if(s.getServerSocket().getLocalPort() == port)
                return s;
        }
        return null;
    }

    /**
     * Finds and returns the row a BaseServerClient is placed in
     * @param client
     * @return
     */
    public int getRowByClient(BaseServerClient client) {
        JTable table = MonitorJ.getInstance().getUi().clientListTable;
        for(int curRow = 0; curRow < table.getModel().getRowCount(); curRow++) {
            if(table.getModel().getValueAt(curRow, 1) == client.CLIENT_HWID) {
                return curRow;
            }
        }
        return -1;
    }

    /**
     * Finds and returns BaseServerClient by desired JTable row
     * @param row
     * @return
     */
    public BaseServerClient getClientByRow(int row) {
        JTable table = MonitorJ.getInstance().getUi().clientListTable;
        for(BaseServerClient client : ServerManager.instance.allClients) {
            if(table.getModel().getValueAt(row, 1) == client.CLIENT_HWID) {
                return client;
            }
        }
        return null;
    }

    /**
     * Finds and returns BaseServerClient of the current select row
     * @return
     */
    public BaseServerClient getClientBySelectedRow() {
        JTable table = MonitorJ.getInstance().getUi().clientListTable;
        for(BaseServerClient client : ServerManager.instance.allClients) {
            if(table.getModel().getValueAt(table.getSelectedRow(), 1) == client.CLIENT_HWID) {
                return client;
            }
        }
        return null;
    }

    /**
     * Returns an ArrayList of all selected BaseServerClient objects on JTable
     * @return
     */
    public ArrayList<BaseServerClient> getAllSelectedClients() {
        ArrayList<BaseServerClient> tempClientList = new ArrayList<>();
        JTable table = MonitorJ.getInstance().getUi().clientListTable;
        int[] selectedRows = table.getSelectedRows();
        for(int currentRow : selectedRows) {
            BaseServerClient currentClient = this.getClientByRow(currentRow);
            tempClientList.add(currentClient);
        }
        return tempClientList;
    }

    /**
     * Returns an ArrayList of all BaseServerClient connections
     * @return
     */
    public ArrayList<BaseServerClient> getAllClients() {
        return this.allClients;
    }
}
