package com.karmawhore.authentication;

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;

public class AuthenticationService extends Service {
	
	private Authenticator mAuthenticator;
	
	@Override
	public void onCreate() {
		mAuthenticator = new Authenticator(this);
	}

	@Override
	public void onDestroy() {
	}

	@Override
	public IBinder onBind(Intent arg0) {
		return mAuthenticator.getIBinder();
	}

}
