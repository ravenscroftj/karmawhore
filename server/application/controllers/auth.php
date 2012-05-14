<?php

/**
 * Authentication controller for logging in/out registering etc
 * @author james
 *
 */
class Auth extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper("ui");
		$this->load->library("session");
		$this->load->library("quickauth");
	}
	
	function index(){
		
		$this->load->view("auth");
	}
	
	function register($json=false){
		$_POST['type'] = 1;
		
		$this->load->library("form_validation");
		
		$result = $this->quickauth->register($_POST);
		
		if($json){
			print json_encode(array("success" => $result));
		
		}else{
			header("Location: ".site_url("auth"));
			exit;
		}
	}
	
	function login($json=false){
		
		$result = $this->quickauth->login($_POST['username'], 
				$_POST['password']);
		
		if($json)
			print json_encode(array("success" => $result));
		
		
		else if($result) {
			header("Location: ".site_url("dashboard"));
			exit;
		}else{
			header("Location: ".site_url("auth"));
			exit;
		}
	}
	
}

?>