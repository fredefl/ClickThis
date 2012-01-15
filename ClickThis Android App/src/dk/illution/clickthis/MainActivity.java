package dk.illution.clickthis;

import java.io.File;

import android.app.Activity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebBackForwardList;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

public class MainActivity extends Activity {
	WebView mainWebView;
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
    	// The standard crap
    	super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        setUpWebView(savedInstanceState);
    }
    
    @Override
    protected void onSaveInstanceState(Bundle outState)
    {
      final WebBackForwardList list = mainWebView.saveState(outState);
      File mThumbnailDir = getDir("thumbnails", 0);
      if (list != null)
      {
        final File f = new File(mThumbnailDir, mainWebView.hashCode() + "_pic.save");
        if (mainWebView.savePicture(outState, f))
           outState.putString("currentPicture", f.getPath());
      }
    }
    
    public void setUpWebView (Bundle savedInstanceState) {
    	// Find the WebView element
        mainWebView = (WebView) findViewById(R.id.mainWebView);
        
        // Make the DOM storage persistent
        mainWebView.getSettings().setDatabaseEnabled(true);
        mainWebView.getSettings().setDatabasePath("/data/data/dk.illution.clickthis/databases/");
       
        
        // Enable JavaScript and DOM storage (for example localStorage)
        mainWebView.getSettings()
        	.setJavaScriptEnabled(true);
        mainWebView.getSettings()
        	.setDomStorageEnabled(true);

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
        if (savedInstanceState != null)
        {
        	if(mainWebView.getUrl() == null) {
		          final WebBackForwardList list = mainWebView.restoreState(savedInstanceState);
		          if (list == null)
		            return;
		
		          if (savedInstanceState.containsKey("currentPicture"))
		          {
		             final File f = new File(savedInstanceState.getString("currentPicture"));
		             mainWebView.restorePicture(savedInstanceState, f);
		             f.delete();
		          }
        	} else {
        		mainWebView.loadUrl("http://illution.dk/ClickThisPrototype/home.html");
        	}
        } else {
        	mainWebView.loadUrl("http://illution.dk/ClickThisPrototype/home.html");
        }
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
   
   protected void clearnotification () {
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
        
        // Terminates the native android app, FROM JAVASCRIPT!!!
        public void terminateApp () {
        	
        	MainActivity.this.moveTaskToBack(true);
        	
        }
        
         
        public void startSeries () {
        	sendNotification("ClickThis","A Series Is Open");
        }
        
        public void test () {
        	
        	Context context = getApplicationContext();
        	CharSequence text = "Hello toast!";
        	int duration = Toast.LENGTH_SHORT;

        	Toast toast = Toast.makeText(context, text, duration);
        	toast.show();
        }
    }

}