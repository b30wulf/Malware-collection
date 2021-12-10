package me.jershdervis.monitorj.server.packets;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.PacketTask;
import me.jershdervis.monitorj.server.Packets;
import me.jershdervis.monitorj.ui.components.Toaster;
import me.jershdervis.monitorj.util.GeoIP;
import me.jershdervis.monitorj.util.ResourceLoader;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.io.IOException;

/**
 * Created by Josh on 19/06/2015.
 */
public class PingTask extends PacketTask {

    public PingTask() {
        super(Packets.PING.getPacketID());
    }

    @Override
    public void run(BaseServerClient client) throws IOException {
        long ping = System.currentTimeMillis() - client.lastPingTime;

        client.CLIENT_HWID = client.getDataInputStream().readUTF();
        client.CLIENT_PC_NAME = client.getDataInputStream().readUTF();
        client.CLIENT_USER_NAME = client.getDataInputStream().readUTF();
        client.CLIENT_OS = client.getDataInputStream().readUTF();
        client.CLIENT_IP = client.getClientSocket().getInetAddress().getHostAddress();
        client.CLIENT_PORT = client.getClientServerHost().getServerSocket().getLocalPort();
        client.CLIENT_PING = ping;

        String ip = client.CLIENT_IP.equals("127.0.0.1") ? GeoIP.HOST_EXTERNAL_IP : client.CLIENT_IP;
        String countryCode = MonitorJ.getInstance().getGeoIP().getCountryCode(ip);
        String countryName = MonitorJ.getInstance().getGeoIP().getCountryName(ip);
        JLabel countryLabel = new JLabel(countryName); //Set name and icon here then get the icon in ClientTableCellRenderer
        countryLabel.setIcon(MonitorJ.getInstance().getGeoIP().getCodeFlag(countryCode));
        Object[] row = new Object[] {
                countryLabel,
                client.CLIENT_HWID,
                client.CLIENT_PC_NAME,
                client.CLIENT_USER_NAME,
                client.CLIENT_OS,
                client.CLIENT_IP,
                client.CLIENT_PORT,
                client.CLIENT_PING
        };
        ((DefaultTableModel) MonitorJ.getInstance().getUi().clientListTable.getModel()).addRow(row);
        Toaster toaster = new Toaster();
        toaster.setToasterMessageFont(new Font("Verdana", Font.PLAIN, 14));
        toaster.setToasterHeight(46);
        toaster.showToaster(
                ResourceLoader.CLIENT_CONNECT,
                "New Connection:\n"
                        + client.CLIENT_PC_NAME + ":" + client.CLIENT_USER_NAME);

    }

    /**
     * Checks if a BaseServerClient is already on JTable
     * @param client
     * @return
     */
    private boolean isClientOnTable(BaseServerClient client) {
        DefaultTableModel tableModel = ((DefaultTableModel) MonitorJ.getInstance().getUi().clientListTable.getModel());
        for(int i = 0; i < tableModel.getRowCount(); i++)
            return tableModel.getValueAt(i, 1).toString().equals(client.CLIENT_HWID);
        return false;
    }
}
