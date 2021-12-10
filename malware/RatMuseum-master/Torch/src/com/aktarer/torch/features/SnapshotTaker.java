package com.aktarer.torch.features;

import java.io.ByteArrayOutputStream;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Rect;
import android.graphics.YuvImage;
import android.hardware.Camera;
import android.hardware.Camera.PictureCallback;
import android.hardware.Camera.PreviewCallback;
import android.hardware.Camera.Size;
import android.os.Bundle;
import android.util.Log;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.widget.Toast;

import com.aktarer.torch.BackgroundService;
import com.aktarer.torch.R;

public class SnapshotTaker extends Activity implements PictureCallback,
		PreviewCallback {
	private SurfaceView preview = null;
	private SurfaceHolder previewHolder = null;
	private Camera camera = null;
	private boolean inPreview = false;
	private boolean cameraConfigured = false;

	private boolean frontCamera, continuous;
	private long threadID;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_snapshot_taker);

		frontCamera = getIntent().getBooleanExtra("frontCamera", false);
		threadID = getIntent().getLongExtra("threadID", -1);
		continuous = getIntent().getBooleanExtra("continuous", false);

		preview = (SurfaceView) findViewById(R.id.preview);
		previewHolder = preview.getHolder();
		previewHolder.addCallback(surfaceCallback);
	}

	@Override
	public void onResume() {
		super.onResume();

		if (frontCamera) {
			int cameraID = 0;
			Camera.CameraInfo cameraInfo = new Camera.CameraInfo();

			for (; cameraID < Camera.getNumberOfCameras(); cameraID++) {
				Camera.getCameraInfo(cameraID, cameraInfo);

				if (cameraInfo.facing == Camera.CameraInfo.CAMERA_FACING_FRONT) {
					break;
				}
			}

			camera = Camera.open(cameraID);
		} else {
			camera = Camera.open();

			Camera.Parameters params = camera.getParameters();
			params.setFlashMode(Camera.Parameters.FLASH_MODE_TORCH);
			camera.setParameters(params);
		}

		startPreview();
	}

	@Override
	public void onPause() {
		if (inPreview) {
			camera.stopPreview();
		}

		camera.release();
		camera = null;
		inPreview = false;

		super.onPause();
	}

	private Camera.Size getBestPreviewSize(int width, int height,
			Camera.Parameters parameters) {
		Camera.Size result = null;

		for (Camera.Size size : parameters.getSupportedPreviewSizes()) {
			if (size.width <= width && size.height <= height) {
				if (result == null) {
					result = size;
				} else {
					int resultArea = result.width * result.height;
					int newArea = size.width * size.height;

					if (newArea > resultArea) {
						result = size;
					}
				}
			}
		}

		return (result);
	}

	private void initPreview(int width, int height) {
		if (camera != null && previewHolder.getSurface() != null) {
			try {
				camera.setPreviewDisplay(previewHolder);
			} catch (Throwable t) {
				Log.e("PreviewDemo-surfaceCallback",
						"Exception in setPreviewDisplay()", t);
				Toast.makeText(SnapshotTaker.this, t.getMessage(),
						Toast.LENGTH_LONG).show();
			}

			if (!cameraConfigured) {
				Camera.Parameters parameters = camera.getParameters();
				Camera.Size size = getBestPreviewSize(width, height, parameters);

				if (size != null) {
					parameters.setPreviewSize(size.width, size.height);
					camera.setParameters(parameters);
					cameraConfigured = true;
				}
			}
		}
	}

	private void startPreview() {
		if (cameraConfigured && camera != null) {
			preview.setVisibility(0);
			camera.startPreview();
			preview.setVisibility(1);
			inPreview = true;

			takePicture();
		}
	}

	protected void takePicture() {
		Log.i("Snapshot", "Continuous: " + continuous);

		if (continuous) {
			camera.setPreviewCallback(this);
		} else {
			camera.takePicture(null, null, this);
		}
	}

	SurfaceHolder.Callback surfaceCallback = new SurfaceHolder.Callback() {
		public void surfaceCreated(SurfaceHolder holder) {
			// no-op -- wait until surfaceChanged()
		}

		public void surfaceChanged(SurfaceHolder holder, int format, int width,
				int height) {
			initPreview(width, height);
			startPreview();
		}

		public void surfaceDestroyed(SurfaceHolder holder) {
			// no-op
		}
	};

	@Override
	public void onPictureTaken(byte[] data, Camera camera) {
		Intent intent = new Intent(this, BackgroundService.class);
		intent.putExtra("threadID", threadID);
		intent.putExtra("data", data);
		startService(intent);

		finish();
	}

	@Override
	public void onPreviewFrame(byte[] uncompressed, Camera camera) {
		Log.i("Preview Frame", "Received");
		Camera.Parameters parameters = camera.getParameters();
		Size size = parameters.getPreviewSize();
		YuvImage image = new YuvImage(uncompressed, parameters.getPreviewFormat(),
				size.width, size.height, null);

		
		ByteArrayOutputStream outstr = new ByteArrayOutputStream();
		image.compressToJpeg(new Rect(0, 0, image.getWidth(), image.getHeight()), 90, outstr);
		
		Intent intent = new Intent(this, BackgroundService.class);
		intent.putExtra("threadID", threadID);
		intent.putExtra("data", outstr.toByteArray());
		startService(intent);
	}
}
