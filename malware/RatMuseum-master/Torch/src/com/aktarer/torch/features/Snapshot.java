package com.aktarer.torch.features;

import android.content.Context;
import android.content.Intent;
import android.util.Log;

import com.aktarer.torch.ServerThread;

public class Snapshot extends Base {
	protected boolean frontCamera = false;
	
	public Snapshot(Context c, ServerThread t, boolean fc) {
		super(c, t);
		
		Log.i("Snapshot", "Front camera: " + fc);
		
		frontCamera = fc;
		
		run();
	}

	@Override
	public void run() {
		thread.setImageOutput();
		
		try {
			Intent takePictureIntent = new Intent(context, SnapshotTaker.class);
			takePictureIntent.putExtra("threadID", thread.getId());
			takePictureIntent.putExtra("frontCamera", frontCamera);
			takePictureIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			context.startActivity(takePictureIntent);
		} catch (Exception e) {
			
		}
	}
}
