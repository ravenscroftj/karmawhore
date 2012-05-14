<?php

class AchievementsModel extends CI_Model{
	
	function __construct() {
		parent::__construct();
		
		$this->load->database();
		$this->load->library("Achievement");
	}
	
	/**
	 * Display all achievements in the database
	 * 
	 */
	function listAll(){
		
		$r = $this->db->query("SELECT * FROM achievements");
		
		return $r->result();
		
	}
	
	/**
	 * Find achievements that match the given name and return a list
	 * 
	 * @param unknown_type $name
	 */
	function search($name) {
		$r = $this->db->query("SELECT * FROM achievements".
				" WHERE ach_name LIKE ?", $name.'%');
		
		return $r->result();
	}
	
}

?>