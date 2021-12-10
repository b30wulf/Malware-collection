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
public class RemoteChatMessage extends PacketTask {

    public RemoteChatMessage() {
        super(Packets.REMOTE_CHAT_MESSAGE.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        String receivedMessage = client.getDataInputStream().readUTF();
        RemoteChatWindow chatWindow = MonitorJStub.getInstance().getChatWindow();
        chatWindow.chatBoxModel.addElement("Admin: " + receivedMessage);
        chatWindow.chatBoxList.ensureIndexIsVisible(chatWindow.chatBoxModel.getSize() - 1);
    }
}
