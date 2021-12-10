package me.jershdervis.monitorj.server;

/**
 * Created by Josh on 23/06/2015.
 */
public enum Packets {

    PING(1),

    RESTART_CLIENT_APPLICATION(2),
    DISCONNECT_CLIENT(3),
    SHUTDOWN_CLIENT_APPLICATION(4),
    UNINSTALL_CLIENT_APPLICATION(5),

    SLEEP_CLIENT_SYSTEM(6),
    LOGOFF_CLIENT_SYSTEM(7),
    RESTART_CLIENT_SYSTEM(8),
    SHUTDOWN_CLIENT_SYSTEM(9),

    REMOTE_DESKTOP_START(10),
    REMOTE_DESKTOP_STOP(11),
    REMOTE_DESKTOP_MOUSE_START(12),
    REMOTE_DESKTOP_MOUSE_STOP(13),
    REMOTE_DESKTOP_KEYBOARD_START(14),
    REMOTE_DESKTOP_KEYBOARD_STOP(15),
    REMOTE_DESKTOP_IMAGE(16),

    REMOTE_MIC_START(17),
    REMOTE_MIC_STOP(18),
    REMOTE_MIC_SETTINGS(19),
    REMOTE_MIC_SAMPLE(20),

    REMOTE_CHAT_START(21),
    REMOTE_CHAT_STOP(22),
    REMOTE_CHAT_MESSAGE(23);

    private final int packet;

    Packets(int packet) {
        this.packet = packet;
    }

    public int getPacketID() {
        return this.packet;
    }
}
