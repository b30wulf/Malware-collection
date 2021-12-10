package me.jershdervis.monitorj.stub.eventapi;

import java.lang.reflect.Method;

/**
 * @author DarkMagician6
 */
public class MethodData {

    public final Object source;
    public final Method target;
    public final byte priority;

    MethodData(Object source, Method target, byte priority) {
        this.source = source;
        this.target = target;
        this.priority = priority;
    }
	
}
