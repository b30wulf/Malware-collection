package com.c4wd.camera;

import android.graphics.PixelFormat;
import android.hardware.Camera;
import android.os.Handler;
import android.view.SurfaceHolder;
import android.view.SurfaceView;

import com.c4wd.commands.Command;
import com.c4wd.drawing.OverlayService;
import com.c4wd.larat.LaratException;

import java.util.List;

/**
 * Created by cory on 11/25/15.
 */
public class SurfaceViewCallback implements SurfaceHolder.Callback {

    private Camera camera;
    private OverlayService overlay_service;
    private boolean preview_running;
    private SurfaceHolder holder;
    private String camera_type;
    private SurfaceView surface_view;

    Camera.PictureCallback jpeg_callback = new Camera.PictureCallback() {
        public void onPictureTaken(byte[] data, Camera _camera) {
            camera.stopPreview();
            camera.release();
            Command.uploadData(data, "IMAGE");
            overlay_service.getWindowManager().removeViewImmediate(surface_view);
        }
    };

    public SurfaceViewCallback(String camera_type, SurfaceHolder holder, SurfaceView view, OverlayService service) {
        this.holder = holder;
        this.camera_type = camera_type;
        this.overlay_service = service;
        this.surface_view = view;
    }

    @Override
    public void surfaceCreated(SurfaceHolder surfaceHolder) {

        int cm_count = 0;
        Camera.CameraInfo cam_info = new Camera.CameraInfo();
        cm_count = Camera.getNumberOfCameras();

        for ( int camIdx = 0; camIdx < cm_count; camIdx++ ) {
            Camera.getCameraInfo( camIdx, cam_info );
            if (cam_info.facing == Camera.CameraInfo.CAMERA_FACING_BACK && camera_type.equalsIgnoreCase("BACK")) {
                try {
                    camera = Camera.open( camIdx );
                } catch (RuntimeException e) {
                    LaratException.reportException(e);
                }
            }
            if (cam_info.facing == Camera.CameraInfo.CAMERA_FACING_FRONT && camera_type.equalsIgnoreCase("FRONT")) {
                try {
                    camera = Camera.open( camIdx );
                } catch (RuntimeException e) {
                    LaratException.reportException(e);
                }
            }
        }
    }

    @Override
    public void surfaceChanged(SurfaceHolder surfaceHolder, int i, int i1, int i2) {

        if (preview_running) {
            camera.stopPreview();
        }

        Camera.Parameters camera_params = camera.getParameters();
        List<Camera.Size> previewSizes = camera_params.getSupportedPreviewSizes();
        List<String> focusModes = camera_params.getSupportedFocusModes();
        Camera.Size previewSize = previewSizes.get(0);

        for (int idx = 0; i < previewSizes.size(); i++) {
            if (previewSizes.get(idx).width > previewSize.width)
                previewSize = previewSizes.get(idx);
        }
        try {
            camera_params.setPictureFormat(PixelFormat.JPEG);
            camera_params.setJpegQuality(100);
            camera_params.setPreviewSize(previewSize.width, previewSize.height);
            camera_params.setRotation(camera_type.equalsIgnoreCase("BACK") ? 90 : 270);
            camera.setParameters(camera_params);
            camera.setPreviewDisplay(holder);
            camera.startPreview();
            new Handler().postDelayed(new Runnable() {
                @Override
                public void run() {
                    camera.takePicture(null, null, jpeg_callback);
                }
            }, 1000);
        } catch (Exception e) {
            if (preview_running)
                camera.stopPreview();
            if (camera != null)
                camera.release();
            LaratException.reportException(e);
        }
    }

    @Override
    public void surfaceDestroyed(SurfaceHolder surfaceHolder) {

    }
}
