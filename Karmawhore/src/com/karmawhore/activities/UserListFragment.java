package com.karmawhore.activities;

import android.app.ListFragment;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.provider.ContactsContract;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.SimpleCursorAdapter;

import com.karmawhore.R;

public class UserListFragment extends ListFragment {

	@Override
	public void onCreate(Bundle savedInstanceState) {
		Cursor cursor = getContacts();
        String[] fields = new String[] {
                ContactsContract.Data.DISPLAY_NAME
        };
        SimpleCursorAdapter adapter = new SimpleCursorAdapter (getActivity(), R.layout.stats, cursor, fields,new int[] { R.id.stats_user_text});
        setListAdapter(adapter);
        
		super.onCreate(savedInstanceState);
	}
	
	private Cursor getContacts()
    {
		//TODO: Work out how to get contacts for karmawhore user
        return null;
        
    }
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		return super.onCreateView(inflater, container, savedInstanceState);
	}

	@Override
	public void onPause() {
		// TODO Auto-generated method stub
		super.onPause();
	}

}
