package com.c4wd.larat;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;

import com.c4wd.commands.Command;
import com.c4wd.commands.CommandContext;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import com.parse.ParsePushBroadcastReceiver;

import java.io.UnsupportedEncodingException;
import java.net.URLDecoder;
import java.util.LinkedList;
import java.util.List;

/**
 * Created by cory on 10/4/15.
 */
public class ParseReceiver extends ParsePushBroadcastReceiver {
    protected void onPushReceive(Context context, Intent intent) {
        Command.initCommands();
        JsonObject data = getDataFromIntent(intent);
        String command = null;
        List<String> arguments = new LinkedList<String>();

        try {

            command = data.get("command").getAsString();

            if(data.has("args")) {
                if (data.get("args").isJsonArray()) {
                    JsonArray args = data.getAsJsonArray("args");
                    for (JsonElement obj : args) {
                        arguments.add(obj.getAsString());
                    }
                }
            }

        } catch (Exception ex) {
            LaratException.reportException(ex);
        }

        Log.i("Command Received", command);

        CommandContext ctx = new CommandContext(context, arguments);

        Class<?> tsk = Command.getTask(command);
        try {
            AsyncTask task = (AsyncTask) tsk.newInstance();
            if (task != null) {
                try {
                    task.execute(ctx);
                } catch (Exception ex) {
                    LaratException.reportException(ex);
                }
            }
        } catch(Exception ex) {
            LaratException.reportException(ex);
        }
    }

    private JsonObject getDataFromIntent(Intent intent) {
        JsonParser parser = new JsonParser();
        JsonElement data = null;
        try {
            data = parser.parse(URLDecoder.decode(intent.getExtras().getString(ParseConstants.PARSE_DATA_KEY), "UTF-8"));
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }
        return data.getAsJsonObject();
    }
}
