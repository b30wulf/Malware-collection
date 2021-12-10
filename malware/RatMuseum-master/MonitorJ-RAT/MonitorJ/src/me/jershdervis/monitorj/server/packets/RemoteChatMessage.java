package me.jershdervis.monitorj.server.packets;

import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.PacketTask;
import me.jershdervis.monitorj.server.Packets;
import me.jershdervis.monitorj.ui.components.RemoteChatFrame;

import java.io.IOException;

/**
 * Created by Josh on 20/06/2015.
 * TODO: FIX COMPATIBILITY WITH REMOTE DESKTOP - IMPORTANT
 */
public class RemoteChatMessage extends PacketTask {

    public RemoteChatMessage() {
        super(Packets.REMOTE_CHAT_MESSAGE.getPacketID());
    }

    @Override
    public void run(BaseServerClient client) throws IOException {
        String receivedMessage = client.getDataInputStream().readUTF();
        RemoteChatFrame chatFrame = client.getRemoteChatFrame();
        chatFrame.chatBoxModel.addElement(client.CLIENT_USER_NAME + ": " + receivedMessage);
        chatFrame.chatBoxList.ensureIndexIsVisible(chatFrame.chatBoxModel.getSize() - 1);
    }
}
