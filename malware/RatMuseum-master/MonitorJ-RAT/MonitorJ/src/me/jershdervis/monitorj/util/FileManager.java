package me.jershdervis.monitorj.util;

import me.jershdervis.monitorj.eventapi.EventManager;
import me.jershdervis.monitorj.eventapi.EventTarget;
import me.jershdervis.monitorj.eventapi.events.EventUILoaded;
import me.jershdervis.monitorj.server.ServerManager;
import org.jdom2.Document;
import org.jdom2.Element;
import org.jdom2.JDOMException;
import org.jdom2.input.SAXBuilder;
import org.jdom2.output.Format;
import org.jdom2.output.XMLOutputter;

import javax.swing.table.DefaultTableModel;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.List;

/**
 * Created by Josh on 20/07/2015.
 */
public class FileManager {

    private final File settingsFile = new File("settings.xml");
    private Document socketsDocument;

    public FileManager() {
        EventManager.register(this);
        Element socketsElement = new Element("sockets");
        this.socketsDocument = new Document(socketsElement);
    }

    /**
     * Adds the formatted socket value to the XML file
     * @param name
     * @param port
     * @param desc
     */
    public void saveSocketValue(String name, int port, String desc) {
        Element newSocket = new Element("socket");
        newSocket.addContent(new Element("name").setText(name));
        newSocket.addContent(new Element("port").setText(String.valueOf(port)));
        newSocket.addContent(new Element("desc").setText(desc));
        this.socketsDocument.getRootElement().addContent(newSocket);
        this.saveXmlFile(socketsDocument, settingsFile);
    }

    /**
     * Removes the Socket element by port
     * TODO: FIX
     * @param port
     */
    public void removeSocketValue(int port) {
        SAXBuilder saxBuilder = new SAXBuilder();
        try {
            // converted file to document object
            Document document = saxBuilder.build(this.settingsFile);

            Element rootNode = document.getRootElement();

            List<Element> socketList = rootNode.getChildren("socket");

            for(Element element : socketList) {
                if(port == Integer.parseInt(element.getChildText("port"))) {
                    this.socketsDocument.getRootElement().removeContent(element);
                }
            }

            this.saveXmlFile(this.socketsDocument, this.settingsFile);
        } catch (JDOMException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void saveXmlFile(Document doc, File xmlFile) {
        try {
            XMLOutputter xmlOutput = new XMLOutputter();
            xmlOutput.setFormat(Format.getPrettyFormat());
            xmlOutput.output(doc, new FileWriter(xmlFile));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Loads Server Socket Listeners when program is initialized
     * @param event
     */
    @EventTarget
    public void onUILoaded(EventUILoaded event) {
        if(this.settingsFile.exists()) {
            SAXBuilder saxBuilder = new SAXBuilder();
            try {
                // converted file to document object
                this.socketsDocument = saxBuilder.build(this.settingsFile);

                Element rootNode = this.socketsDocument.getRootElement();

                List<Element> socketList = rootNode.getChildren("socket");

                for (Element element : socketList) {
                    String name = element.getChildText("name");
                    int port = Integer.parseInt(element.getChildText("port"));
                    String desc = element.getChildText("desc");

                    ServerManager.instance.listenOnPort(port);

                    DefaultTableModel model = (DefaultTableModel) event.getUi().socketTable.getModel();
                    model.addRow(new Object[]{name, String.valueOf(port), desc});
                }
            } catch (JDOMException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

}
