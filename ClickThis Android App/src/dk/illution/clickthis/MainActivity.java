package dk.illution.clickthis;

import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebSettings.RenderPriority;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class MainActivity extends Activity {
	WebView mainWebView;
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
    	// The standard crap
    	super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        setUpWebView();
    }
    
    public void setUpWebView () {
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
                // do your handling codes here, which URL is the requested url
                // probably you need to open that URL rather than redirect:
                view.loadUrl(url);
                return false; // then it is not handled by default action
           }
        });
        // Clear cache
        mainWebView.clearCache(true);
        // Load the ClickThis Prototype
        mainWebView.loadUrl("http://illution.dk/ClickThisPrototype");
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
        
        // Terminates the native android app
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