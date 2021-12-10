package com.aktarer.torch.features;

import android.content.Context;
import android.content.Intent;

import com.aktarer.torch.ServerThread;

public class Camcorder extends Base {
	protected boolean frontCamera = false;

	public Camcorder(Context c, ServerThread t, boolean fc) {
		super(c, t);
		
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
			takePictureIntent.putExtra("continuous", true);
			takePictureIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			context.startActivity(takePictureIntent);
		} catch (Exception e) {
			
		}
	}

}
