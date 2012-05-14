<?php

class DashboardModel extends CI_Model {
	
	/**
	 * Return an AchieveUser object for the active user account
	 */
	function getMyProfile(){
		$this->load->library("AchieveUser");
	}
	
	/**
	 * Select the user's top friends to find out what they've been up to
	 */
	function listFriends(){
		
		$me = $this->quickauth->user();
		
		$sql = "SELECT id,username,firstname,lastname FROM `users` ".
		"WHERE id in (SELECT friend_id FROM user_friends WHERE".
		" friend_id IN (SELECT user_id FROM user_friends WHERE friend_id=? ))";
		
		$r = $this->db->query($sql, $me->id);
		
		$this->load->library("AchieveUser");
		
		$users = array();
		
		foreach($r->result() as $user_row) {
			$user = new AchieveUser();
			$user->user_id = $user_row->id;
			$user->username = $user_row->username;
			$user->firstname = $user_row->firstname;
			$user->lastname = $user_row->lastname;
			
			$users[] = $user;
		}
		
		return $users;
	}
	
	/**
	 * Send friend invite to the person with the given friend ID
	 * 
	 * @param integer $friendID <p>The ID of the person to try and friend</p>
	 */
	function addFriend($friendID) {
		
	}
	
	/**
	 * Remove a friend from the person's list of friends
	 * 
	 * @param integer $friendID
	 */
	function removeFriend($friendID) {
		
	}
}

?>