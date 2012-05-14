/**
 * 
 */
package com.karmawhore.authentication;

import android.accounts.Account;
import android.accounts.AccountAuthenticatorActivity;
import android.accounts.AccountManager;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ContentResolver;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.provider.ContactsContract;
import android.text.TextUtils;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.karmawhore.Constants;
import com.karmawhore.R;

/**
 * @author Mat
 * 
 */
public class AuthenticationActivity extends AccountAuthenticatorActivity {

	/** The Intent flag to confirm credentials. */
	public static final String PARAM_CONFIRM_CREDENTIALS = "confirmCredentials";

	/** The Intent extra to store password. */
	public static final String PARAM_PASSWORD = "password";

	/** The Intent extra to store username. */
	public static final String PARAM_USERNAME = "username";

	/** The Intent extra to store username. */
	public static final String PARAM_AUTHTOKEN_TYPE = "authtokenType";

	private AccountManager mAccountManager;

	/** Keep track of the login task so can cancel it if requested */
	private UserLoginTask mAuthTask = null;

	/** Keep track of the progress dialog so we can dismiss it */
	private ProgressDialog mProgressDialog = null;

	/**
	 * If set we are just checking that the user knows their credentials; this
	 * doesn't cause the user's password or authToken to be changed on the
	 * device.
	 */
	private Boolean mConfirmCredentials = false;

	/** for posting authentication attempts back to UI thread */
	private final Handler mHandler = new Handler();

	private TextView mMessage;

	private String mPassword;

	private EditText mPasswordEdit;

	/** Was the original caller asking for an entirely new account? */
	protected boolean mRequestNewAccount = false;

	private String mUsername;

	private EditText mUsernameEdit;

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		mAccountManager = AccountManager.get(this);
		final Intent intent = getIntent();
		mUsername = intent.getStringExtra(PARAM_USERNAME);
		mRequestNewAccount = mUsername == null;
		mConfirmCredentials = intent.getBooleanExtra(PARAM_CONFIRM_CREDENTIALS,
				false);

		setContentView(R.layout.login);

		mMessage = (TextView) findViewById(R.id.login_text_helpmessage);
		mUsernameEdit = (EditText) findViewById(R.id.login_edit_username);
		mPasswordEdit = (EditText) findViewById(R.id.login_edit_password);
		if (!TextUtils.isEmpty(mUsername))
			mUsernameEdit.setText(mUsername);
		mMessage.setText(getMessage());

	}

	@Override
	protected Dialog onCreateDialog(int id, Bundle args) {
		final ProgressDialog dialog = new ProgressDialog(this);
		return dialog;
	}

	public void handleLogin(View view) {
		if (mRequestNewAccount) {
			mUsername = mUsernameEdit.getText().toString();
		}
		mPassword = mPasswordEdit.getText().toString();
		if (TextUtils.isEmpty(mUsername) || TextUtils.isEmpty(mPassword)) {
			mMessage.setText(getMessage());
		} else {
			// Kick off the background task
			// TODO: some kinda progress
			mAuthTask = new UserLoginTask();
			mAuthTask.execute();
		}
	}

	private CharSequence getMessage() {
		// TODO Something here
		return "Error maybe";
	}

	private void finishConfirmCredentials(boolean result) {
		final Account account = new Account(mUsername, Constants.ACCOUNT_TYPE);
		mAccountManager.setPassword(account, mPassword);
		final Intent intent = new Intent();
		intent.putExtra(AccountManager.KEY_BOOLEAN_RESULT, result);
		setAccountAuthenticatorResult(intent.getExtras());
		setResult(RESULT_OK, intent);
		finish();
	}
	
	private void finishLogin(String authToken) {
		final Account account = new Account(mUsername, Constants.ACCOUNT_TYPE);
		if (mRequestNewAccount) {
			mAccountManager.addAccountExplicitly(account, mPassword, null);
			// set contacts sync for this account
			ContentResolver.setSyncAutomatically(account,
					ContactsContract.AUTHORITY, true);
		} else {
			mAccountManager.setPassword(account, mPassword);
		}
		final Intent intent = new Intent();
		intent.putExtra(AccountManager.KEY_ACCOUNT_NAME, mUsername);
		intent.putExtra(AccountManager.KEY_ACCOUNT_TYPE, Constants.ACCOUNT_TYPE);
		setAccountAuthenticatorResult(intent.getExtras());
		setResult(RESULT_OK, intent);
		finish();
	}

	

	private void onAuthenticationResult(String authToken) {
		boolean successfull = ((authToken != null) && (authToken.length() > 0));

		mAuthTask = null;

		if (successfull) {
			if (!mConfirmCredentials) {
				finishLogin(authToken);
			} else {
				finishConfirmCredentials(successfull);
			}
		} else {
			if (mRequestNewAccount) {
				// They tried to create a new account with incorrect deets
				mMessage.setText("YOU GOT IT WRONG"); // TODO: externalise
			} else {
				// They gave us the wrong details
				mMessage.setText("PLEASE GIVE CORRECT PASS THANKS BYE"); // TODO:
																	// externalise
			}
		}
	}

	private void onAuthenticationCancel() {
		mAuthTask = null;
	}

	public class UserLoginTask extends AsyncTask<Void, Void, String> {

		@Override
		protected String doInBackground(Void... params) {
			// Authenticate the user in another class
			return null;
		}

		@Override
		protected void onCancelled() {
			// Let the activity know that authentication has been cancelled for
			// now
			onAuthenticationCancel();
		}

		@Override
		protected void onPostExecute(String authToken) {
			// Let the activity know that authentication has finished
			onAuthenticationResult(authToken);
		}

	}

}