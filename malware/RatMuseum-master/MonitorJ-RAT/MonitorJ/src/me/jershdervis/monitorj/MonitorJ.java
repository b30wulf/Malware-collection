package me.jershdervis.monitorj;

import com.alee.laf.WebLookAndFeel;
import me.jershdervis.monitorj.eventapi.events.*;
import me.jershdervis.monitorj.server.PacketTaskManager;
import me.jershdervis.monitorj.server.ServerManager;
import me.jershdervis.monitorj.ui.UserInterface;
import me.jershdervis.monitorj.util.FileManager;
import me.jershdervis.monitorj.util.GeoIP;

import javax.swing.*;

/**
 * Created by Josh on 18/06/2015.
 */
public class MonitorJ {

    /**
     * Initialized within this classes constructor
     */
    private static MonitorJ instance;

    /**
     * Program Event initialization
     */
    public final EventUILoaded EVENT_UI_LOADED = new EventUILoaded();
    public final EventClientConnect EVENT_CLIENT_CONNECT = new EventClientConnect();
    public final EventClientDisconnect EVENT_CLIENT_DISCONNECT = new EventClientDisconnect();
    public final EventReceivePacket EVENT_RECEIVE_PACKET = new EventReceivePacket();
    public final EventReceiveDesktopImage EVENT_RECEIVE_DESKTOP_IMAGE = new EventReceiveDesktopImage();

    /**
     * Initialized within this classes constructor
     */
    private final PacketTaskManager packetTaskManager;
    private final ServerManager serverManager;
    private final FileManager fileManager;
    private final UserInterface ui;
    private final GeoIP geoIP;

    /**
     * Initializes:
     * - MonitorJ instance
     * - PacketTaskManager packetTaskManager
     * - ServerManager serverManager
     * - UserInterface ui
     */
    public MonitorJ() {
        instance = this;

        this.packetTaskManager = new PacketTaskManager();
        this.serverManager = new ServerManager();
        this.fileManager = new FileManager();
        this.geoIP = new GeoIP();

        try {
            javax.swing.UIManager.setLookAndFeel(WebLookAndFeel.class.getCanonicalName());
        } catch (ClassNotFoundException | InstantiationException | IllegalAccessException | UnsupportedLookAndFeelException e) {
            try {
                UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
            } catch (ClassNotFoundException | InstantiationException | IllegalAccessException | UnsupportedLookAndFeelException ex) {
                ex.printStackTrace();
            }
        }

        //Create UserInterface:
        this.ui = new UserInterface();
        java.awt.EventQueue.invokeLater(() -> {
            ui.setLocationRelativeTo(null);
            ui.setVisible(true);
        });
        //
    }

    /**
     * Gets the current classes instance
     * @return
     */
    public static MonitorJ getInstance() {
        return instance;
    }

    /**
     * Gets the ServerManager class
     * @return
     */
    public ServerManager getServerManager() {
        return this.serverManager;
    }

    /**
     * Gets the PacketTaskManager class
     * @return
     */
    public PacketTaskManager getPacketTaskManager() {
        return this.packetTaskManager;
    }

    /**
     * Gets the FileManager class
     * @return
     */
    public FileManager getFileManager() {
        return this.fileManager;
    }

    /**
     * Returns the UserInterface class
     * @return
     */
    public UserInterface getUi() {
        return this.ui;
    }

    /**
     * Returns the GeoIP class
     * @return
     */
    public GeoIP getGeoIP() {
        return this.geoIP;
    }
}
