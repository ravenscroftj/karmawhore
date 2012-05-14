package com.karmawhore.activities;

import android.accounts.AccountManager;
import android.app.Activity;
import android.os.Bundle;

import com.karmawhore.Constants;
import com.karmawhore.R;

public class MainActivity extends Activity {
	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		if (!isSignedIn()) {
			signIn();
		}
		
		setContentView(R.layout.main);

		
	}

	private void signIn() {
		AccountManager accountManager = AccountManager.get(getApplicationContext());
		
		accountManager.addAccount(
				Constants.ACCOUNT_TYPE, Constants.AUTHTOKEN_TYPE, null,
				null, this, null, null);
	}

	private boolean isSignedIn() {
		// TODO Auto-generated method stub
		return false;
	}
}