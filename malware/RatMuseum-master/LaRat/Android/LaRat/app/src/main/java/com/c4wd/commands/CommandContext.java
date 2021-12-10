package com.c4wd.commands;

import android.content.Context;

import java.util.List;

/**
 * Created by cory on 10/4/15.
 */
public class CommandContext {

    private Context context;
    private List<String> arguments;

    public CommandContext(Context context, List<String> arguments) {
        this.context = context;
        this.arguments = arguments;
    }

    public Context getContext() {
        return this.context;
    }

    public List<String> getArguments() {
        return this.arguments;
    }

    public Object getArgument(int index) {
        try {
            return this.arguments.get(index);
        } catch (IndexOutOfBoundsException ex) {
            return null;
        }
    }
}
