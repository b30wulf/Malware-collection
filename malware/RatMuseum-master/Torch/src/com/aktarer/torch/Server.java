package com.aktarer.torch;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.HashMap;
import java.util.LinkedList;

import android.content.Context;
import android.util.Log;

public class Server extends Thread {
	protected Context context;
	protected Socket current;

	protected HashMap<Long, ServerThread> serverThreads = new HashMap<Long, ServerThread>();
	protected HashMap<Long, LinkedList<byte[]>> bufferedData = new HashMap<Long, LinkedList<byte[]>>();

	public Server(Context c) {
		context = c;
	}

	public void run() {
		ServerSocket serverSocket = null;
		boolean listening = true;
		int port = 43533;

		try {
			Log.i("BackgroundService", "About to listen");

			serverSocket = new ServerSocket(port);

			Log.i("BackgroundService", "Started on: " + port);

			while (listening) {
				current = serverSocket.accept();
				LinkedList<byte[]> buffer = new LinkedList<byte[]>();

				ServerThread serverThread = new ServerThread(current, context,
						buffer);

				serverThreads.put(serverThread.getId(), serverThread);
				bufferedData.put(serverThread.getId(), buffer);

				serverThread.start();
			}

			serverSocket.close();
		} catch (IOException e) {
			Log.i("BackgroundService", "Server IOException");
		}
	}

	public void update(long threadID, byte[] data) {
		Log.i("Server Update", "Adding update and notifying");
		Log.i("BackgroundService", "Retrieved data for: " + threadID);
		Log.i("BackgroundService", "Retrieved data length: " + data.length);

		if (!serverThreads.containsKey(threadID)) {
			return;
		} 

		ServerThread thread = serverThreads.get(threadID);

		synchronized (thread) {
			bufferedData.get(threadID).add(data);

			thread.notify();
		}
	}
}
