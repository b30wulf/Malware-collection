package com.c4wd.larat;

/**
 * Created by cory on 10/4/15.
 */
import android.util.Log;

import com.loopj.android.http.*;

import java.nio.charset.Charset;

import cz.msebera.android.httpclient.Header;

public class RestClient {
    private static final String BASE_URL = "http://c4wd.com/larat/";

    private static AsyncHttpClient client = new AsyncHttpClient();

    public static void get(String url, RequestParams params) {
        client.get(getAbsoluteUrl(url), params, RestClient.ResponseHandler);
    }

    public static void post(String url, RequestParams params) {
        client.post(getAbsoluteUrl(url), params, RestClient.ResponseHandler);
    }

    public static void get(String url, RequestParams params, AsyncHttpResponseHandler responseHandler) {
        client.get(getAbsoluteUrl(url), params, responseHandler);
    }

    public static void post(String url, RequestParams params, AsyncHttpResponseHandler responseHandler) {
        client.post(getAbsoluteUrl(url), params, responseHandler);
    }

    private static String getAbsoluteUrl(String relativeUrl) {
        return BASE_URL + relativeUrl;
    }

    public static AsyncHttpResponseHandler ResponseHandler = new AsyncHttpResponseHandler() {

        @Override
        public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
            String response = new String(responseBody, Charset.defaultCharset());
            Log.d("LARAT_RESPONSE", response);
        }

        @Override
        public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {

        }
    };
}