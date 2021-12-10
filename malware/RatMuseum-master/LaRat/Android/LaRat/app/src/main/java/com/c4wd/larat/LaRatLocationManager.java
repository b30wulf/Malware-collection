package com.c4wd.larat;

import android.content.Context;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;

/**
 * Created by cory on 10/6/15.
 */
public class LaRatLocationManager {
    public static long LOCATION_REQUEST_INTERVAL = 30 * 60 * 1000;   //DEFAULT TO 30 MINUTES

    public static android.location.Location LAST_LOCATION;
    private static LocationManager locManager;

    private static void updateWithNewLocation(android.location.Location location) {
        if (location != null) {
            LAST_LOCATION = location;
        }
    }

    private static final android.location.LocationListener mLocationListener = new LocationListener() {
        public void onLocationChanged(android.location.Location location) {
            updateWithNewLocation(location);
        }

        public void onProviderDisabled(String provider) {
            updateWithNewLocation(null);
        }

        public void onProviderEnabled(String provider) {
        }

        public void onStatusChanged(String provider, int status, Bundle extras) {
        }
    };

    public static void startLocationService(Context context) {
        locManager = (LocationManager) context.getSystemService(Context.LOCATION_SERVICE);

        try {
            locManager.removeUpdates(mLocationListener);    //if it's enabled, disable it
        } catch (IllegalArgumentException ex) {}

        locManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, LOCATION_REQUEST_INTERVAL, 1, mLocationListener);
        android.location.Location location = locManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
    }
}
