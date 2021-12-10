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
public class RemoteChatStopTask extends PacketTask {

    public RemoteChatStopTask() {
        super(Packets.REMOTE_CHAT_STOP.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        //Hide the RemoteChat JFrame
        RemoteChatWindow chatWindow = MonitorJStub.getInstance().getChatWindow();
        chatWindow.setAlwaysOnTop(false);
        chatWindow.setVisible(false);
    }
}
