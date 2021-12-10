package me.jershdervis.monitorj.eventapi.events;

import me.jershdervis.monitorj.eventapi.Event;

import java.awt.image.BufferedImage;

/**
 * Created by Josh on 25/07/2015.
 */
public class EventReceiveDesktopImage extends Event {

    private BufferedImage originalBufferedImage;

    public Event call(BufferedImage rawImage) {
        this.originalBufferedImage = rawImage;
        return super.call();
    }

    public BufferedImage getOriginalBufferedImage() {
        return this.originalBufferedImage;
    }
}
