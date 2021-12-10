package com.c4wd.larat;

import com.loopj.android.http.RequestParams;

/**
 * Created by cory on 11/24/15.
 */
public class LaratException {

    public static void reportException(Exception ex) {
        RequestParams params = new RequestParams();
        params.put("command", "addMessage");
        params.put("client_id", Constants.CLIENT_ID);
        params.put("message_type", "EXECUTION_ERROR");
        params.put("message", ex.getMessage());
        RestClient.post("client_command.php", params);
        ex.printStackTrace();
    }

}
