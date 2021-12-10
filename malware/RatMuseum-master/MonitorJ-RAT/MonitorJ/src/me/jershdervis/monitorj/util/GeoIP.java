package me.jershdervis.monitorj.util;

import com.maxmind.geoip.LookupService;
import me.jershdervis.monitorj.MonitorJ;

import javax.imageio.ImageIO;
import javax.swing.*;
import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URISyntaxException;
import java.net.URL;

/**
 * Created by Josh on 26/06/2015.
 */
public class GeoIP {

    public static String HOST_EXTERNAL_IP;

    static {
        try {
            HOST_EXTERNAL_IP = new BufferedReader(new InputStreamReader(new URL("http://checkip.amazonaws.com/").openStream())).readLine();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private LookupService lookupService;

    public GeoIP() {
        try {
            this.lookupService = new LookupService(new File(MonitorJ.class.getResource("/GeoIP.dat").toURI()), LookupService.GEOIP_MEMORY_CACHE);
        } catch (URISyntaxException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public String getCountryCode(String ipAddress) throws IOException {
        return this.lookupService.getCountry(ipAddress).getCode().toLowerCase();
    }

    public String getCountryName(String ipAddress) {
        return this.lookupService.getCountry(ipAddress).getName();
    }

    public ImageIcon getCodeFlag(String countryCode) throws IOException {
        return new ImageIcon(ImageIO.read(ClassLoader.getSystemResource("me/jershdervis/monitorj/resources/flags/" + countryCode + ".png")));
    }
}
