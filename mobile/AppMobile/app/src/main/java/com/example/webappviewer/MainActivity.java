package com.example.webappviewer;

import android.app.DownloadManager;
import android.content.Context;
import android.content.Intent;
import android.media.MediaScannerConnection;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.view.View;
import android.webkit.CookieManager;
import android.webkit.DownloadListener;
import android.webkit.URLUtil;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class MainActivity extends AppCompatActivity {
    private WebView webView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getSupportActionBar().hide();
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webview);

        webView.requestFocus(View.FOCUS_DOWN);
        webView.setFocusable(true);
        webView.setFocusableInTouchMode(true);
        
        String baseURL = "add your adresse herْ";//@ip:8080 or your https//....


        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true); // Activer JavaScript

        CookieManager.getInstance().setAcceptCookie(true);  /// gestoin des cookies

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            webView.getSettings().setMixedContentMode(WebSettings.MIXED_CONTENT_ALWAYS_ALLOW);
        }
        webView.setDownloadListener((url, userAgent, contentDisposition, mimetype, contentLength) -> {
            // We delay the manual download to ensure cookies are available
            new Handler(Looper.getMainLooper()).postDelayed(() -> {
                String cookie = CookieManager.getInstance().getCookie(url);
                if (cookie != null) {
                    String fileName = URLUtil.guessFileName(url, contentDisposition, mimetype);

                    Log.e("-----------------------___>","filemane============ "+fileName);
                    downloadWithCookie(getApplicationContext(), url, cookie, fileName);
                } else {
                    Toast.makeText(getApplicationContext(), "No session cookie found.", Toast.LENGTH_SHORT).show();
                }
            }, 200); // Delay slightly to ensure WebView finishes navigation
        });



        webView.setWebViewClient(new WebViewClient()); // Reste dans l'app
        webView.loadUrl(baseURL); // Remplace par ton URL


    }

    public void downloadWithCookie(Context context, String fileUrl, String cookie, String fileName) {
        new Thread(() -> {
            try {
                URL url = new URL(fileUrl);
                HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                connection.setRequestProperty("Cookie", cookie);
                connection.setRequestProperty("User-Agent", "Mozilla/5.0");
                connection.connect();

                if (connection.getResponseCode() != HttpURLConnection.HTTP_OK) {
                    Log.e("Download", "Failed: " + connection.getResponseCode());
                    return;
                }

                File path = new File(Environment.getExternalStoragePublicDirectory(
                        Environment.DIRECTORY_DOWNLOADS), fileName);
                InputStream input = connection.getInputStream();
                FileOutputStream output = new FileOutputStream(path);

                byte[] buffer = new byte[4096];
                int bytesRead;
                while ((bytesRead = input.read(buffer)) != -1) {
                    output.write(buffer, 0, bytesRead);
                }

                output.close();
                input.close();

                MediaScannerConnection.scanFile(context,
                        new String[]{path.getAbsolutePath()}, null, null);

                new Handler(Looper.getMainLooper()).post(() ->
                        Toast.makeText(context, "Download complete!", Toast.LENGTH_SHORT).show());

            } catch (Exception e) {
                Log.e("Download Error", e.getMessage(), e);
                new Handler(Looper.getMainLooper()).post(() ->
                        Toast.makeText(context, "Download failed", Toast.LENGTH_SHORT).show());
            }
        }).start();
    }

    @Override
    public void onBackPressed() {
        if (webView.canGoBack())
            webView.goBack();
        else
            super.onBackPressed();
    }
}

