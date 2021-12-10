package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.client.threads.RemoteDesktopStream;

import javax.swing.*;
import java.awt.*;
import java.awt.image.BufferedImage;
import java.io.IOException;

/**
 * Created by Josh on 22/06/2015.
 */
public class RemoteDesktopStartTask extends PacketTask {

    public static volatile Thread remoteDesktopStream;

    public RemoteDesktopStartTask() {
        super(Packets.REMOTE_DESKTOP_START.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        (remoteDesktopStream = new Thread(new RemoteDesktopStream(client))).start();
    }
}
