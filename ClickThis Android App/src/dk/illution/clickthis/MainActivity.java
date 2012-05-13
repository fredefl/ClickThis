package dk.illution.clickthis;

import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebSettings.RenderPriority;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.net.http.*;
import android.webkit.*;
import android.widget.ProgressBar;

public class MainActivity extends Activity {
	WebView mainWebView;
	ProgressBar progressBar;
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
    	super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        setUpWebView();
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.menu, menu);
        return true;
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
    	if(item.getItemId() == R.id.refresh) {
    		mainWebView.reload();
    	}
    	if(item.getItemId() == R.id.clearCache) {
    		mainWebView.clearCache(true);
    	}
        return true;
    }
    
    public void setUpWebView () {
    	progressBar = (ProgressBar) findViewById(R.id.progressbar);
    	progressBar.setProgress(0);
    	progressBar.setVisibility(View.VISIBLE);
    	// Find the WebView element
        mainWebView = (WebView) findViewById(R.id.mainWebView);
        mainWebView.loadUrl("http://illution.dk/ClickThisPrototype"); 
        
        // Get settings once
        WebSettings settings = mainWebView.getSettings();
        // Set some settings
        settings.setDatabaseEnabled(true);
        settings.setDatabasePath("/data/data/dk.illution.clickthis/databases/");
        settings.setJavaScriptEnabled(true);
        settings.setDomStorageEnabled(true);
        settings.setPluginsEnabled(true);
        settings.setSupportZoom(false);
        settings.setRenderPriority(RenderPriority.HIGH);

        // Make the the scroll bar more beautiful
        mainWebView.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);
        
        // Add the JavaScript interface
        mainWebView.addJavascriptInterface(new JavaScriptInterface(this), "ClickThisApp");
        
        // Handle redirects so it won't open in the built in browser
        mainWebView.setWebViewClient(new WebViewClient() {
            public boolean shouldOverrideUrlLoading(WebView view, String url){
            	// Load the URL
                view.loadUrl(url);
                // Disable default action
                return false; 
           }
            public void onReceivedSslError (WebView view, SslErrorHandler handler, SslError error) {
            	Log.d("ClickThis", "We've got an SSL error. Android " + android.os.Build.VERSION.SDK_INT + " O " + error.getCertificate().getIssuedBy().getOName());
            	if (android.os.Build.VERSION.SDK_INT < 14 && error.getCertificate().getIssuedBy().getOName().equals("StartCom Ltd.")) {
            		handler.proceed();
            	} else {
            		handler.cancel();
            	}
            	
        	}
        });
        
        mainWebView.setWebChromeClient(new WebChromeClient() {
        	 public void onProgressChanged(WebView view, int progress) {
        		progressBar.setProgress(progress);
                if(progress == 100) {
                	progressBar.setVisibility(View.GONE);
                }
             }
       });
        
        // Clear cache
        mainWebView.clearCache(true);
        // Load the ClickThis Prototype
        mainWebView.loadUrl("https://illution.dk/ClickThisPrototype/home.html");
    }
    

    
    protected void sendNotification (String title, String message) {
 	   String ns = Context.NOTIFICATION_SERVICE;
 	   NotificationManager mNotificationManager = (NotificationManager) getSystemService(ns);

 	   int icon = R.drawable.icon;
 	   CharSequence tickerText = message;
 	   long when = System.currentTimeMillis();

 	   Notification notification = new Notification(icon, tickerText, when);

 	   Context context = getApplicationContext();
 	   CharSequence contentTitle = title;
 	   CharSequence contentText = message;
 	   Intent notificationIntent = new Intent(this, MainActivity.class);
 	   PendingIntent contentIntent = PendingIntent.getActivity(this, 0, notificationIntent, 0);

 	   notification.flags = Notification.FLAG_ONGOING_EVENT;
 	   notification.setLatestEventInfo(context, contentTitle, contentText, contentIntent);
 	   mNotificationManager.notify(1, notification);
 	}
   
   protected void clearNotification () {
 	  String ns = Context.NOTIFICATION_SERVICE;
       NotificationManager mNotificationManager = (NotificationManager) getSystemService(ns);
       mNotificationManager.cancelAll();
 	}
    
    // The JavaScript interface
    public class JavaScriptInterface {
        Context mContext;

        /** Instantiate the interface and set the context */
        JavaScriptInterface(Context c) {
            mContext = c;
        }
        
        // Terminates the native Android application
        public void terminateApp () {
        	MainActivity.this.moveTaskToBack(true);
        }
        
        public void startSeries () {
        	sendNotification("ClickThis","A Series Is Open");
        }
        
        public void endSeries () {
        	clearNotification();
        }
    }

}