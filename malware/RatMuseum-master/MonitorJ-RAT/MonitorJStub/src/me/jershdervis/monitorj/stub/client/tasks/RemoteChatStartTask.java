package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.MonitorJStub;
import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.ui.RemoteChatWindow;

import java.io.IOException;

/**
 * Created by Josh on 20/06/2015.
 */
public class RemoteChatStartTask extends PacketTask {

    public RemoteChatStartTask() {
        super(Packets.REMOTE_CHAT_START.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        //Display JFrame for RemoteChat window.
        RemoteChatWindow chatWindow = MonitorJStub.getInstance().getChatWindow();
        chatWindow.setAlwaysOnTop(true);
        chatWindow.setLocationRelativeTo(null);
        chatWindow.setVisible(true);
    }
}
