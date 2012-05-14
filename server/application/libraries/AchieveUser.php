<?php

class AchieveUser implements JsonSerializable{
	
	var $user_id;
	var $username;
	var $firstname;
	var $lastname;
	var $karma;
	
	/**
	 * Return the user's karma score aggregated into a single number
	 */
	function getKarma(){
		
	}
	
	/**
	 * List the user's friends
	 * 
	 * @return AchieveUser[]
	 */
	function getFriends(){
		
	}
	
	/**
	 * List a set of badges
	 */
	function getBadges(){
		
	}
	
	/**
	 * 
	 * @param Achievement $achievement
	 */
	function addAchievement( $achievement ) {
		
	}
	
	function jsonSerialize(){
		$vars = get_object_vars($this);
		$vars['vagina'] = "yes";
		
		return $vars;
	}
}

?>