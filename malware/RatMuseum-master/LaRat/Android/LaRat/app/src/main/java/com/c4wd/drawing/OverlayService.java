package com.c4wd.drawing;

import android.content.Context;
import android.graphics.PixelFormat;
import android.view.Gravity;
import android.view.View;
import android.view.WindowManager;

import com.c4wd.larat.LaratException;

import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;

/**
 * Created by cory on 10/8/15.
 */
public class OverlayService {

    private static List<View> enabled_views = new LinkedList<View>();
    private Context context;
    private  WindowManager.LayoutParams layoutParams;
    private WindowManager windowManager;
    private static final int LayoutParamFlags = WindowManager.LayoutParams.FLAG_NOT_FOCUSABLE
            | WindowManager.LayoutParams.FLAG_NOT_TOUCH_MODAL
            | WindowManager.LayoutParams.FLAG_NOT_TOUCHABLE;

    public void createWindowManager() {
        this.windowManager =  (WindowManager) this.context.getSystemService(Context.WINDOW_SERVICE);
    }

    public OverlayService(Context context) {
        this.context = context;
        WindowManager.LayoutParams params = new WindowManager.LayoutParams(
                WindowManager.LayoutParams.WRAP_CONTENT,
                WindowManager.LayoutParams.WRAP_CONTENT,
                WindowManager.LayoutParams.TYPE_PHONE,
                LayoutParamFlags,
                PixelFormat.TRANSLUCENT);

        params.gravity = Gravity.FILL;
        params.x = 0;
        params.y = 0;
        this.layoutParams = params;
        createWindowManager();
    }

    public OverlayService(Context context, WindowManager.LayoutParams params) {
        this.context = context;
        this.layoutParams = params;
        createWindowManager();
    }

    public WindowManager getWindowManager() {
        return windowManager;
    }

    public void addView(View view) {
        addView(view, true);
    }

    public void addView(View view, boolean add_to_list) {
        view.setLayerType(View.LAYER_TYPE_SOFTWARE, null);
        windowManager.addView(view, this.layoutParams);
        if(add_to_list) {
            enabled_views.add(view);
        }
    }

    public boolean removeView(int index) {
        if (index < enabled_views.size()) {
            View view = enabled_views.get(index);
            try {
                this.windowManager.removeView(view);
                enabled_views.remove(index);
                return true;
            } catch (Exception ex) {
                LaratException.reportException(ex);
                return false;
            }
        }
        return false;
    }

    public boolean removeAllViews() {
        try {
            for (View view : enabled_views) {
                this.windowManager.removeViewImmediate(view);
            }
            this.enabled_views.clear();
            return true;
        } catch (Exception ex) {
            LaratException.reportException(ex);
            return false;
        }
    }

    public void setX(int x) {
        layoutParams.x = x;
    }

    public void setY(int y) {
        layoutParams.y = y;
    }

    public void setHeight(int height) {
        this.layoutParams.height = height;
    }

    public void setWidth(int width) {
        this.layoutParams.width = width;
    }

    public void setGravity(int gravity) {
        this.layoutParams.gravity = gravity;
    }
}
