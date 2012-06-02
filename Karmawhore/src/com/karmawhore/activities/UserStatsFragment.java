package com.karmawhore.activities;

import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.karmawhore.R;

public class UserStatsFragment extends Fragment {

	@Override
	public void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		return inflater.inflate(R.layout.stats, container, false);
	}

	@Override
	public void onPause() {
		// TODO Auto-generated method stub
		super.onPause();
	}

}
