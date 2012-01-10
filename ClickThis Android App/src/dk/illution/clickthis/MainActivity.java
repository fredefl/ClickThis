package dk.illution.clickthis;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.view.View;
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
        setUpWebView();
    }
    
    public void setUpWebView () {
    	// Find the WebView element
        mainWebView = (WebView) findViewById(R.id.mainWebView);
        
        // Make the DOM storage persistent
        mainWebView.getSettings().setDatabaseEnabled(true);
        mainWebView.getSettings().setDatabasePath("/data/data/clickthis/databases");
       
        
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
        mainWebView.loadUrl("http://illution.dk/ClickThisPrototype/home.html");
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
        	//MainActivity.this.moveTaskToBack(true);
        	Toast.makeText(mContext, "hehe", Toast.LENGTH_SHORT).show();
        }
    }

}