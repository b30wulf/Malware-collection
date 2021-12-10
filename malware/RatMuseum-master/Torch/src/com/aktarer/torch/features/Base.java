package com.aktarer.torch.features;

import android.content.Context;

import com.aktarer.torch.ServerThread;

public abstract class Base {
	protected Context context;
	protected ServerThread thread;

	public Base(Context c, ServerThread t) {
		context = c;
		thread = t;
	}
	
	public abstract void run();
}
