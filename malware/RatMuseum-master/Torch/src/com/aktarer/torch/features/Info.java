package com.aktarer.torch.features;

import org.json.JSONObject;

import android.app.ActivityManager;
import android.app.ActivityManager.MemoryInfo;
import android.content.Context;
import android.telephony.TelephonyManager;
import android.view.Display;
import android.view.WindowManager;

import com.aktarer.torch.ServerThread;

public class Info extends Base {
	public Info(Context c, ServerThread t) {
		super(c, t);
		
		run();
	}

	@Override
	public void run() {
		thread.setJSONOutput();
		
		JSONObject response = new JSONObject();
		
		try {
			JSONObject build = new JSONObject();
			build.put("id", android.os.Build.ID);
			build.put("product", android.os.Build.PRODUCT);
			build.put("device", android.os.Build.DEVICE);
			build.put("board", android.os.Build.BOARD);
			build.put("cpu_abi", android.os.Build.CPU_ABI);
			build.put("manufacturer", android.os.Build.MANUFACTURER);
			build.put("brand", android.os.Build.BRAND);
			build.put("model", android.os.Build.MODEL);
			build.put("type", android.os.Build.TYPE);
			build.put("tags", android.os.Build.TAGS);
			build.put("fingerprint", android.os.Build.FINGERPRINT);
			build.put("time", android.os.Build.TIME);
			build.put("user", android.os.Build.USER);
			build.put("host", android.os.Build.HOST);
			build.put("incremental_version", android.os.Build.VERSION.INCREMENTAL);
			build.put("release_version", android.os.Build.VERSION.RELEASE);
			build.put("sdk_version", android.os.Build.VERSION.SDK_INT);
			response.put("build", build);
			
			JSONObject environment = new JSONObject();
			environment.put("root_directory", android.os.Environment.getRootDirectory());
			environment.put("data_directory", android.os.Environment.getDataDirectory());
			environment.put("external_storage_directory", android.os.Environment.getExternalStorageDirectory());
			response.put("environment", environment);
			
			final Display dp = ((WindowManager) context.getSystemService(Context.WINDOW_SERVICE)).getDefaultDisplay(); 
			JSONObject display = new JSONObject();
			display.put("width", dp.getWidth());
			display.put("height", dp.getHeight());
			display.put("orientation", dp.getOrientation());
			display.put("rotation", dp.getRefreshRate());
			response.put("display", display);
			
			JSONObject systemSettings = new JSONObject();
			systemSettings.put("android_id", android.provider.Settings.System.ANDROID_ID);
			response.put("system_settings", systemSettings);
			
			final TelephonyManager tm = (TelephonyManager) context.getSystemService(Context.TELEPHONY_SERVICE);
			JSONObject telephony = new JSONObject();
			telephony.put("device_software_version", tm.getDeviceSoftwareVersion());
			telephony.put("device_id", tm.getDeviceId());
			telephony.put("network_operator_name", tm.getNetworkOperatorName());
			telephony.put("network_country_iso", tm.getNetworkCountryIso());
			telephony.put("network_type", tm.getNetworkType());
			telephony.put("sim_state", tm.getSimState());
			telephony.put("sim_operator_name", tm.getSimOperatorName());
			telephony.put("sim_country_iso", tm.getSimCountryIso());
			telephony.put("sim_serial_number", tm.getSimSerialNumber());
			telephony.put("subscriber_id", tm.getSubscriberId());
			telephony.put("line1_number", tm.getLine1Number());
			telephony.put("voicemail_number", tm.getVoiceMailNumber());
			telephony.put("call_state", tm.getCallState());
			telephony.put("data_activity", tm.getDataActivity());
			telephony.put("data_state", tm.getDataState());
			response.put("telephony", telephony);
			
			final ActivityManager am = (ActivityManager) context.getSystemService(Context.ACTIVITY_SERVICE);
			MemoryInfo mi = new ActivityManager.MemoryInfo();
			am.getMemoryInfo(mi);
			JSONObject activity = new JSONObject();
			activity.put("memory_class", mi.getClass());
			activity.put("total_available_memory", mi.availMem);
			activity.put("low_memory_situation", mi.lowMemory);
			activity.put("low_memory_thrshold", mi.threshold);
			response.put("activity", activity);
			
			final Runtime rt = Runtime.getRuntime();
			JSONObject runtime = new JSONObject();
			runtime.put("available_processors", rt.availableProcessors());
			runtime.put("free_memory", rt.freeMemory());
			runtime.put("total_memory", rt.totalMemory());
			runtime.put("max_memory", rt.maxMemory());
			response.put("runtime", runtime);
		} catch(Exception e) {}
		
		try {
			thread.send(response.toString());
		} catch (Exception e) {
		}
	}

}
