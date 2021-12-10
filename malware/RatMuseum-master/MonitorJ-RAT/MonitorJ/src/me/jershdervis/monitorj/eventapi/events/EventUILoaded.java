package me.jershdervis.monitorj.eventapi.events;

import me.jershdervis.monitorj.eventapi.Event;
import me.jershdervis.monitorj.ui.UserInterface;

/**
 * Created by Josh on 21/07/2015.
 */
public class EventUILoaded extends Event {

    private UserInterface ui;

    public Event call(UserInterface userInterface) {
        this.ui = userInterface;
        return super.call();
    }

    public UserInterface getUi() {
        return this.ui;
    }
}
