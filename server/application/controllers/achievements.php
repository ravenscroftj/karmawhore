<?php

class Achievements extends CI_Controller{
	
	function __construct() {
		parent::__construct();
		$this->load->model("AchievementsModel");
	}
	
	function index(){
		
		
		
	}	
	
	function listall() {

		$actions = $this->AchievementsModel->listAll();
		
		print json_encode($actions);
	}
	
	
	function search($name="") {
		
		$actions = $this->AchievementsModel->search($name);
		
		print json_encode($actions);
	}
	
}

?>