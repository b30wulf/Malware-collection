package me.jershdervis.monitorj.server.packets;

import me.jershdervis.monitorj.MonitorJ;
import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.PacketTask;
import me.jershdervis.monitorj.server.Packets;

import javax.imageio.ImageIO;
import javax.swing.*;
import java.awt.*;
import java.awt.image.BufferedImage;
import java.io.*;
import java.util.zip.GZIPInputStream;

/**
 * Created by Josh on 22/06/2015.
 */
public class RemoteDesktopImage extends PacketTask {

    private JLabel displayLabel = null;

    public RemoteDesktopImage() {
        super(Packets.REMOTE_DESKTOP_IMAGE.getPacketID());
    }

    @Override
    public void run(BaseServerClient client) throws IOException {
        this.displayLabel = client.getRemoteDesktopFrame().jLabel1;

        DataInputStream dis = client.getDataInputStream();

        int length = dis.readInt();

        byte[] array = new byte[length];

        int read = 0;
        while (read < length) {
            int j = dis.read(array, read, length - read);
            if (j < 0) {
                throw new EOFException();
            }
            read += j;
        }

        BufferedImage bufferedImage = ImageIO.read(new GZIPInputStream(new ByteArrayInputStream(array)));

        //Call event, used for scaling remote control clicks
        MonitorJ.getInstance().EVENT_RECEIVE_DESKTOP_IMAGE.call(bufferedImage);

        Image img = bufferedImage.getScaledInstance(displayLabel.getWidth(), displayLabel.getHeight(), Image.SCALE_FAST);

        this.displayLabel.setIcon(new ImageIcon(img));
    }
}
