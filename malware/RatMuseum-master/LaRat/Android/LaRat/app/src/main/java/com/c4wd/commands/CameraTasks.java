package com.c4wd.commands;

import android.hardware.Camera;
import android.os.AsyncTask;
import android.view.Gravity;
import android.view.SurfaceHolder;
import android.view.SurfaceView;

import com.c4wd.camera.SurfaceViewCallback;
import com.c4wd.drawing.OverlayService;

/**
 * Created by cory on 11/25/15.
 */
public class CameraTasks {

    public static class TakePictureTask extends AsyncTask<Object, Void, Object> {

        private CommandContext context;
        private Camera camera;

        @Override
        protected Object doInBackground(Object... objects) {
            context = (CommandContext)objects[0];
            return null;
        }

        @Override
        protected void onPostExecute(Object o) {

            OverlayService overlayService = new OverlayService(context.getContext());
            overlayService.setGravity(Gravity.CENTER);
            SurfaceView surface_view = new SurfaceView(context.getContext());
            overlayService.setHeight(1);
            overlayService.setWidth(1);
            overlayService.addView(surface_view, false);
            SurfaceHolder surface_holder = surface_view.getHolder();
            surface_holder.addCallback(new SurfaceViewCallback(
                            (String) context.getArgument(0),
                            surface_holder,
                            surface_view,
                            overlayService
                    )
            );
            surface_holder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);

        }
    }

}
