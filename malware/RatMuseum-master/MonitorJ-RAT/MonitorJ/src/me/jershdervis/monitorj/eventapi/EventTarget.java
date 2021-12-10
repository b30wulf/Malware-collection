package me.jershdervis.monitorj.eventapi;

import java.lang.annotation.*;

@Target(ElementType.METHOD)
@Retention(RetentionPolicy.RUNTIME)
public @interface EventTarget {

    byte value() default Priority.MEDIUM;
}
