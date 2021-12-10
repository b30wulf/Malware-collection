package com.aktarer.torch.features;

import org.json.JSONObject;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.BatteryManager;

import com.aktarer.torch.ServerThread;

public class Battery extends Base {
	public Battery(Context c, ServerThread t) {
		super(c, t);
		
		run();
	}

	public void run() {
		thread.setJSONOutput();
		
		context.registerReceiver(new BroadcastReceiver() {
			@Override
			public void onReceive(Context context, Intent intent) {
				if (intent != null) {
					int level = intent.getIntExtra("level", -1);

					JSONObject response = new JSONObject();

					try {
						response.put("level", level);
						response.put("temperature", intent.getIntExtra(
								BatteryManager.EXTRA_TEMPERATURE, 0));
						response.put("volatege", intent.getIntExtra(
								BatteryManager.EXTRA_VOLTAGE, 0));
						response.put("status", intent.getIntExtra(
								BatteryManager.EXTRA_STATUS, 0));
						response.put("pluggedIn", intent.getIntExtra(
								BatteryManager.EXTRA_PLUGGED, 0));

						context.unregisterReceiver(this);
					} catch (Exception e) {
					}

					try {
						thread.send(response.toString());
					} catch (Exception e) {
					}
				}
			}
		}, new IntentFilter(Intent.ACTION_BATTERY_CHANGED));
	}
}
