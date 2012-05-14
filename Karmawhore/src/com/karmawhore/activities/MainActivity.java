package com.karmawhore.activities;

import android.app.Activity;
import android.os.Bundle;

import com.karmawhore.R;

public class MainActivity extends Activity {
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        
        if(!isSignedIn()){
        	signIn();
        }
    }

	private void signIn() {
		// TODO Auto-generated method stub
	}

	private boolean isSignedIn() {
		// TODO Auto-generated method stub
		return false;
	}
}