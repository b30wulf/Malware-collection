package com.c4wd.larat;

import android.content.BroadcastReceiver;
import android.content.Intent;
import android.os.Binder;
import android.os.IBinder;
import android.preference.PreferenceManager;
import android.util.Log;

import com.parse.Parse;
import com.parse.ParseInstallation;

/**
 * Created by cory on 10/4/15.
 */
public class LaRatService extends android.app.Service {

    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    BroadcastReceiver LaRatBroadcastReceiver;
    private final IBinder LaRatBinder = new LocalBinder();

    public class LocalBinder extends Binder {
        LaRatService getService() {
            return LaRatService.this;
        }
    }

    @Override
    public void onCreate() {
        Parse.initialize(this, com.c4wd.larat.ParseConstants.APP_ID, ParseConstants.CLIENT_KEY);
        try {
            ParseInstallation.getCurrentInstallation().save();
        } catch (com.parse.ParseException e) {
            e.printStackTrace();
        }
        super.onCreate();
    }

    public void onStart(Intent intent, int startId) {
        super.onStart(intent, startId);

        if (!Constants.IS_SETUP) {
            Constants.setupInternals(getApplicationContext());
        }

        Log.i("com.c4wrd", "Start MyService");
        if (PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).getString("ClientID", "") == null || PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).getString("AndroidID", "").equals("")) {
            PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).edit().putString("ClientID", Constants.CLIENT_ID).commit();
        }
    }
}
