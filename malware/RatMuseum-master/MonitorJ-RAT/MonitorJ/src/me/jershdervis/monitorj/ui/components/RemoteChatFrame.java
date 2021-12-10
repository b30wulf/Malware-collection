package me.jershdervis.monitorj.ui.components;

import me.jershdervis.monitorj.eventapi.EventManager;
import me.jershdervis.monitorj.eventapi.EventTarget;
import me.jershdervis.monitorj.eventapi.events.EventClientDisconnect;
import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.Packets;
import me.jershdervis.monitorj.util.ResourceLoader;

import javax.swing.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.IOException;

/**
 * Created by Josh on 20/06/2015.
 */
public class RemoteChatFrame extends javax.swing.JFrame {

    private final BaseServerClient client;

    private javax.swing.JButton btnSend;
    public javax.swing.JList chatBoxList;
    public javax.swing.DefaultListModel<String> chatBoxModel = new DefaultListModel<String>();
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JTextField messageField;

    public RemoteChatFrame(BaseServerClient client) {
        EventManager.register(this);
        this.client = client;
        this.initComponents();
        setIconImage(ResourceLoader.CLIENT_TOOLS_REMOTE_CHAT.getImage());
        setResizable(false);
        setLocationRelativeTo(null);
    }

    @EventTarget
    public void onClientDisconnect(EventClientDisconnect event) {
        if(event.getClient() == client)
            this.setVisible(false);
    }

    private void initComponents() {

        btnSend = new javax.swing.JButton();
        messageField = new javax.swing.JTextField();
        jScrollPane1 = new javax.swing.JScrollPane();
        chatBoxList = new javax.swing.JList(chatBoxModel);

        setDefaultCloseOperation(WindowConstants.HIDE_ON_CLOSE);

        addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                try {
                    client.getDataOutputStream().writeByte(Packets.REMOTE_CHAT_STOP.getPacketID());
                } catch (IOException e1) {
                    e1.printStackTrace();
                }
            }
        });

        btnSend.setText("Send");
        btnSend.addActionListener(evt -> this.sendMessage(client));

        messageField.addActionListener(e -> this.sendMessage(client));

        jScrollPane1.setViewportView(chatBoxList);

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
                layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addGroup(layout.createSequentialGroup()
                                .addContainerGap()
                                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                        .addComponent(jScrollPane1)
                                        .addGroup(layout.createSequentialGroup()
                                                .addComponent(messageField, javax.swing.GroupLayout.PREFERRED_SIZE, 305, javax.swing.GroupLayout.PREFERRED_SIZE)
                                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                                .addComponent(btnSend, javax.swing.GroupLayout.PREFERRED_SIZE, 69, javax.swing.GroupLayout.PREFERRED_SIZE)
                                                .addGap(0, 0, Short.MAX_VALUE)))
                                .addContainerGap())
        );
        layout.setVerticalGroup(
                layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                        .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, layout.createSequentialGroup()
                                .addContainerGap()
                                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 249, Short.MAX_VALUE)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                                        .addComponent(btnSend)
                                        .addComponent(messageField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                                .addContainerGap())
        );

        pack();
    }

    private void sendMessage(BaseServerClient client) {
        try {
            client.getDataOutputStream().writeByte(Packets.REMOTE_CHAT_MESSAGE.getPacketID());
            client.getDataOutputStream().writeUTF(messageField.getText());
            this.chatBoxModel.addElement("Admin: " + messageField.getText());
            this.chatBoxList.ensureIndexIsVisible(chatBoxModel.getSize() - 1);
            this.messageField.setText("");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
