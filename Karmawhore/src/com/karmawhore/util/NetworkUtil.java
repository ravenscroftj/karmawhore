package com.karmawhore.util;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.params.ConnManagerParams;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

import android.util.Log;

public final class NetworkUtil {
	/** POST parameter name for the user's account name */
    public static final String PARAM_USERNAME = "username";
    /** POST parameter name for the user's password */
    public static final String PARAM_PASSWORD = "password";
    /** POST parameter name for the user's authentication token */
    public static final String PARAM_AUTH_TOKEN = "authtoken";
    /** POST parameter name for the client's last-known sync state */
    public static final String PARAM_SYNC_STATE = "syncstate";
    /** POST parameter name for the sending client-edited contact info */
    public static final String PARAM_CONTACTS_DATA = "contacts";
    /** Timeout (in ms) we specify for each http request */
    public static final int HTTP_REQUEST_TIMEOUT_MS = 30 * 1000;
    /** Base URL for the v2 Sample Sync Service */
    public static final String BASE_URL = "http://alpha.roarc.co.uk/karmawhore/index.php";
    /** URI for authentication service */
    public static final String AUTH_URI = BASE_URL + "/auth/login/1";
    /** URI for sync service */
    public static final String SYNC_CONTACTS_URI = BASE_URL + "/sync";
    /** Tag for logging */
	private static final String TAG = "Networking";
    
    private NetworkUtil(){
    }
    
    public static HttpClient getHttpClient() {
    	HttpClient httpClient = new DefaultHttpClient();
    	final HttpParams params = httpClient.getParams();
    	HttpConnectionParams.setConnectionTimeout(params, HTTP_REQUEST_TIMEOUT_MS);
    	HttpConnectionParams.setSoTimeout(params, HTTP_REQUEST_TIMEOUT_MS);
    	ConnManagerParams.setTimeout(params, HTTP_REQUEST_TIMEOUT_MS);
    	return httpClient;
    }
	
    public static String authenticate(String username, String password){
    	final HttpResponse resp;
    	final ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
    	params.add(new BasicNameValuePair(PARAM_USERNAME, username));
    	params.add(new BasicNameValuePair(PARAM_PASSWORD, password));
    	final HttpEntity entity;
    	try{
    		entity = new UrlEncodedFormEntity(params);
    	} catch (final UnsupportedEncodingException e) {
    		throw new IllegalStateException(e);
    	}
    	final HttpPost post = new HttpPost(AUTH_URI);
    	Log.d(TAG, "Server: " + AUTH_URI);
    	post.addHeader(entity.getContentType());
    	post.setEntity(entity);
    	try {
    		Log.d(TAG, "Attempting to authenticate " + username + "...");
    		resp = getHttpClient().execute(post);
    		String authToken = null;
    		if(resp.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
    			InputStream istream = (resp.getEntity() != null) ? resp.getEntity().getContent() : null;
    			if (istream != null) {
    				BufferedReader ireader = new BufferedReader(new InputStreamReader(istream));
    				authToken = ireader.readLine().trim();
    			}
    		}
    		Log.d(TAG, "Server says: " + resp.getStatusLine().getReasonPhrase());
    		if ((authToken != null) && (authToken.length() > 0)){
    			Log.d(TAG, "Server gives: \""+authToken+"\"");
    			return authToken;
    		}else {
    			Log.d(TAG, "Server gives nothing :(");
    			return null;
    		}
    	} catch (final IOException e) {
    		e.printStackTrace();
    		return null;
    	} finally {
    	}
    }
}
