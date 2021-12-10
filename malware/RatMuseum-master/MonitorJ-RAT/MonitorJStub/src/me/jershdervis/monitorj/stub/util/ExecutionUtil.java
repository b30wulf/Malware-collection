package me.jershdervis.monitorj.stub.util;

import java.io.File;
import java.io.IOException;
import java.net.URISyntaxException;
import java.util.ArrayList;

/**
 * Created by Josh on 21/06/2015.
 */
public class ExecutionUtil {

    public static void executeJarFile(File file) throws URISyntaxException, IOException {
          /* Build command: java -jar application.jar */
        final ArrayList<String> command = new ArrayList<String>();
        command.add(ClientSystemUtil.getJavaBinDir());
        command.add("-jar");
        command.add(file.getAbsolutePath());

        final ProcessBuilder builder = new ProcessBuilder(command);
        builder.start();
    }
}
