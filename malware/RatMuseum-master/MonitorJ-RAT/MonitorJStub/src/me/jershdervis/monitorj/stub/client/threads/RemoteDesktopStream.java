package me.jershdervis.monitorj.stub.client.threads;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.Packets;

import javax.imageio.IIOImage;
import javax.imageio.ImageIO;
import javax.imageio.ImageWriteParam;
import javax.imageio.ImageWriter;
import javax.imageio.stream.ImageOutputStream;
import java.awt.*;
import java.awt.image.BufferedImage;
import java.io.ByteArrayOutputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;
import java.util.Iterator;
import java.util.zip.GZIPOutputStream;

/**
 * Created by Josh on 25/06/2015.
 */
public class RemoteDesktopStream implements Runnable {

    public static volatile boolean isStreaming = false;

    private Robot robot;

    /**
     * Quality between 0.1F - 1.0F
     */
    private float imageQuality = 0.3F;

    private final Socket socket;
    private final DataOutputStream dos;

    public RemoteDesktopStream(BaseClient client) {
        this.socket = client.getServerSocketConnection();
        this.dos = client.getDataOutputStream();
        try {
            this.robot = new Robot();
        } catch (AWTException e) {
            e.printStackTrace();
        }
    }

    @Override
    public void run() {
        isStreaming = true;
        while(isStreaming) {
            try {
                this.dos.writeByte(Packets.REMOTE_DESKTOP_IMAGE.getPacketID());
                this.sendBytes();
            } catch (IOException e) {
                try {
                    socket.close();
                } catch (IOException e1) {
                    e1.printStackTrace();
                }
                e.printStackTrace();
                //Important to quit streaming if server is closed here
                this.isStreaming = false;
                break;
            }
        }
        System.out.println("Stopped Remote Desktop Stream");
    }

    private void sendBytes() throws IOException {
        BufferedImage image = this.getScreen();

        byte[] array = encodeToByteArray(image);

        System.out.println((array.length / 1024) + " kb");

        dos.writeInt(array.length);
        dos.write(array);
        dos.flush();
    }

    private byte[] encodeToByteArray(BufferedImage img) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        GZIPOutputStream bss = new GZIPOutputStream(baos);
        ImageOutputStream ios = ImageIO.createImageOutputStream(bss);

        Iterator<ImageWriter> iter = ImageIO.getImageWritersByFormatName("jpeg");
        ImageWriter writer = iter.next();
        ImageWriteParam iwp = writer.getDefaultWriteParam();
        iwp.setCompressionMode(2);
        iwp.setCompressionQuality(this.imageQuality);
        writer.setOutput(ios);
        writer.write(null, new IIOImage(img, null, null), iwp);
        writer.dispose();
        bss.close();

        baos.flush();

        return baos.toByteArray();
    }

    private BufferedImage getScreen(){
        Dimension size = Toolkit.getDefaultToolkit().getScreenSize();
        BufferedImage bi = robot.createScreenCapture(new Rectangle(size));
        return bi;
    }
}
