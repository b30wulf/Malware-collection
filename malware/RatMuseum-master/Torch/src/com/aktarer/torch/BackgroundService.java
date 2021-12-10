package com.aktarer.torch;

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;
import android.util.Log;

public class BackgroundService extends Service {
	protected Server server = null;

	@Override
	public IBinder onBind(Intent intent) {
		return null;
	}

	@Override
	public void onCreate() {
		Log.i("BackgroundService", "Created");

		server = new Server(getApplicationContext());
		server.start();
	}

	@Override
	public int onStartCommand(Intent intent, int flags, int startId) {
		Log.i("BackgroundService", "StartCommanded");

		if (intent != null && intent.hasExtra("threadID") && intent.hasExtra("data")) {
			Log.i("BackgroundService", "Time to send blocked data.");
			
			server.update(intent.getLongExtra("threadID", -1), intent.getByteArrayExtra("data"));
		}

		return START_STICKY;
	}

}
