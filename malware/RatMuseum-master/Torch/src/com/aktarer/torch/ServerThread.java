package com.aktarer.torch;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.nio.ByteBuffer;
import java.util.LinkedList;
import java.util.StringTokenizer;

import android.content.Context;
import android.util.Log;

import com.aktarer.torch.features.Battery;
import com.aktarer.torch.features.Camcorder;
import com.aktarer.torch.features.Info;
import com.aktarer.torch.features.Listen;
import com.aktarer.torch.features.Snapshot;

public class ServerThread extends Thread {
	protected Socket socket = null;
	protected Context context = null;
	protected DataOutputStream out;
	protected BufferedReader in;
	protected String path[];
	protected String contentType = "text/html";
	protected LinkedList<byte[]> buffer;

	protected byte[] buffered;

	protected boolean multipart = false;
	protected boolean headerSent = false;

	protected int BUFFER_SIZE = 1024;

	public ServerThread(Socket s, Context c, LinkedList<byte[]> b) {
		Log.i("BackgroundService", "Received request");
		socket = s;
		context = c;
		buffer = b;
	}

	public ServerThread(Socket s, Context c, byte[] data) {
		Log.i("BackgroundService", "Received request");
		socket = s;
		context = c;

		setImageOutput();
		buffered = data;
	}

	protected void process() throws Exception {
		Log.i("BackgroundService", "Request for: "
				+ (path.length <= 1 ? "index" : path[1]));

		if (path.length <= 1) {
			Info request = new Info(context, this);
		} else if (path[1].equals("battery")) {
			Battery request = new Battery(context, this);
		} else if (path[1].equals("snapshot")) {
			Snapshot request = new Snapshot(context, this,
					(path.length >= 3 && path[2].equals("front")));

			synchronized (this) {
				this.wait(5000);

				byte[] data = "failure".getBytes();

				if (buffer.size() > 0) {
					data = buffer.removeFirst();
				}

				send(data);
			}
		} else if (path[1].equals("camera")) {
			Camcorder request = new Camcorder(context, this,
					(path.length >= 3 && path[2].equals("front")));

			multipart = true;

			while (true) {
				synchronized (this) {
					this.wait(5000);
	
					if (buffer.size() == 0) {
//						send("done".getBytes());
						break;
					}
	
					byte[] data = buffer.removeFirst();
	
					send(data);
					send("--my_mjpeg\r\n".getBytes());
//					send("Received frame\r\n".getBytes());
//					break;
				}
			}
		} else if (path[1].equals("listen")) {
			Listen request = new Listen(context, this);
		} else {
			send("Invalid request!");
		}
	}

	public void setJSONOutput() {
		contentType = "application/json";
	}

	public void setImageOutput() {
		contentType = "image/jpeg";
	}

	public void setAudioOutput() {
		contentType = "audio/wav";
	}

	public void setMultipart() {
		multipart = true;
	}

	protected void sendHeaders() throws Exception {
		if (!headerSent) {
			String header = "HTTP/1.1 200 OK\r\n";

			if (multipart) {
				header += "Content-Type: multipart/x-mixed-replace; boundary=my_mjpeg\r\n";
				header += "--my_mjpeg\r\n";
			}

			out.write(header.getBytes());
		}

		if (!headerSent || multipart) {
//			String header = "";
			String header = "Content-Type: " + contentType + "\r\n";
			header += "\r\n";
			out.write(header.getBytes());
		}

		headerSent = true;
	}

	public void send(String data) throws Exception {
		send(data, false);
	}

	public void send(String data, boolean continuous) throws Exception {
		send(data.getBytes(), continuous);
	}

	public void send(byte[] data) throws Exception {
		send(data, false);
	}

/*	public void send(short[] data, boolean continuous) throws Exception {
		byte bytes[] = new byte[data.length * 2];
		ByteBuffer.wrap(bytes).order(ByteOrder.LITTLE_ENDIAN).asShortBuffer()
				.put(data);

		send(bytes, true);
	}*/

	public void send(byte[] data, boolean continuous) throws Exception {
		Log.i("BackgroundService", "Sending Data");

		sendHeaders();

		out.write(data);
		out.flush();

		if (!continuous && !multipart) {
			close();
		}
	}
	
	public void send(int data, boolean continuous) throws Exception {
		Log.i("BackgroundService", "Sending Data");

		sendHeaders();

		out.write(data);
		out.flush();

		if (!continuous && !multipart) {
			close();
		}
	}

	public void close() throws Exception {
		Log.i("BackgroundService", "Closing socket");

		if (out != null) {
			Log.i("BackgroundService", "Closing output");
			out.close();
		}

		if (in != null) {
			Log.i("BackgroundService", "Closing input");
			in.close();
		}

		if (socket != null) {
			Log.i("BackgroundService", "Closing socket");

			socket.close();
		}
	}

	public void run() {
		try {
			out = new DataOutputStream(socket.getOutputStream());
			in = new BufferedReader(new InputStreamReader(
					socket.getInputStream()));

			if (buffered != null) {
				try {
					send(buffered);
				} catch (Exception e) {
					e.printStackTrace();
				}

				return;
			}

			String inputLine, outputLine;
			int cnt = 0;

			while ((inputLine = in.readLine()) != null) {
				try {
					StringTokenizer tok = new StringTokenizer(inputLine);
					tok.nextToken();
				} catch (Exception e) {
					break;
				}

				if (cnt == 0) {
					String[] tokens = inputLine.split(" ");
					path = tokens[1].split("/");

					try {
						process();
					} catch (Exception e) {
						Log.i("BackgroundService", "ServerThread Exception");
						e.printStackTrace();
					}
				}

				cnt++;
			}
		} catch (IOException e) {
		}
	}
}
