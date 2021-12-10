package com.aktarer.torch.features;

import java.nio.ByteBuffer;

import android.content.Context;
import android.media.AudioFormat;
import android.media.AudioRecord;
import android.media.MediaRecorder;
import android.util.Log;

import com.aktarer.torch.ServerThread;

public class Listen extends Base {
	public static final int SAMPLE_RATE = 16000;
	private AudioRecord mRecorder;
	private short[] mBuffer;

	public Listen(Context c, ServerThread t) {
		super(c, t);

		run();
	}

	protected void sendWaveHeaders() throws Exception {
		// WAVE header
		// see http://ccrma.stanford.edu/courses/422/projects/WaveFormat/
		writeString("RIFF"); // chunk id
		writeInt(36); // chunk size, write 36 for streaming
		writeString("WAVE"); // format
		writeString("fmt "); // subchunk 1 id
		writeInt(16); // subchunk 1 size
		writeShort((short) 1); // audio format (1 = PCM)
		writeShort((short) 1); // number of channels
		writeInt(SAMPLE_RATE); // sample rate
		writeInt(SAMPLE_RATE * 2); // byte rate
		writeShort((short) 2); // block align
		writeShort((short) 16); // bits per sample
		writeString("data"); // subchunk 2 id
		writeInt(0); // subchunk 2 size, write 0 for streaming
	}

	@Override
	public void run() {
		thread.setAudioOutput();

		int bufferSize = AudioRecord.getMinBufferSize(SAMPLE_RATE,
				AudioFormat.CHANNEL_IN_MONO, AudioFormat.ENCODING_PCM_16BIT);
		mBuffer = new short[bufferSize];
		mRecorder = new AudioRecord(MediaRecorder.AudioSource.MIC, SAMPLE_RATE,
				AudioFormat.CHANNEL_IN_MONO, AudioFormat.ENCODING_PCM_16BIT,
				bufferSize);

		mRecorder.startRecording();

		try {
			sendWaveHeaders();

			while (true) {
				int readSize = mRecorder.read(mBuffer, 0, mBuffer.length);
				
				ByteBuffer rawBytes = ByteBuffer.allocate(mBuffer.length);
				
				for (int i = 0; i < mBuffer.length / 2; i++) {
					short s = mBuffer[i];
					rawBytes.put((byte) (s & 0xff));
					rawBytes.put((byte) ((s >> 8) & 0xff));
				}

				thread.send(rawBytes.array(), true);
			}
		} catch (Exception e) {
		}
	}

	private void writeInt(final int value) throws Exception {
		write(value >> 0);
		write(value >> 8);
		write(value >> 16);
		write(value >> 24);
	}

	private void writeShort(final short value) throws Exception {
		write(value >> 0);
		write(value >> 8);
	}

	private void writeString(final String value) throws Exception {
		for (int i = 0; i < value.length(); i++) {
			write(value.charAt(i));
		}
	}

	protected void write(int value) throws Exception {
		thread.send(value, true);
	}

}
