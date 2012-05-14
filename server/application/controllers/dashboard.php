<?php

class Dashboard extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		
		$this->load->helper("url");
		$this->load->library("quickauth");
		
		if(!$this->quickauth->logged_in()){
			header(site_url("auth/login/"));
		}
		
	}
	
	function index(){
		

		
	}
	
	/**
	 * By default, get user's friends
	 * 
	 * @param unknown_type $friendID
	 * @param unknown_type $json
	 */
	function friends(){
		
		switch($_SERVER['REQUEST_METHOD']){
			
			case 'POST':
				//add new friend
				break;
			
			case 'DELETE':
				//remove a friend
				break;
				
			default:
				//list all friends
				$this->load->model("dashboardmodel");
				
				$friends = $this->dashboardmodel->listFriends();
				
				print json_encode($friends);
				break;
		}
		
	}
}

?>