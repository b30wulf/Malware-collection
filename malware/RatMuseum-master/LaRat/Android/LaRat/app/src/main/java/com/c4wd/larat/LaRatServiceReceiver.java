package com.c4wd.larat;

/**
 * Created by cory on 10/4/15.
 */

import android.app.ActivityManager;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

public class LaRatServiceReceiver extends BroadcastReceiver {

    public static Context mContext;

    @Override
    public void onReceive(final Context context, Intent intent)
    {
        mContext = context;
        String intentString = intent.getAction();
        Log.d("Received intent", intentString);

        if(intentString.equals(Intent.ACTION_BOOT_COMPLETED) | intentString.equals(Intent.ACTION_SCREEN_OFF) | intentString.equals(Intent.ACTION_EXTERNAL_APPLICATIONS_AVAILABLE))
        {
            if(!IsServiceRunning(mContext))
            {
                mContext.startService(new Intent(mContext, LaRatService.class));
            }
        }
    }

    public static boolean IsServiceRunning(Context cx) {
        ActivityManager manager = (ActivityManager) cx.getSystemService(Context.ACTIVITY_SERVICE);
        for (ActivityManager.RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {
            if (LaRatService.class.getName().equals(service.service.getClassName())) {
                return true;
            }
        }
        return false;
    }
}
