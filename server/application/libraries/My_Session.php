<?php

class MY_Session extends CI_Session {
	
	
	
	function sess_read(){
		
		$session = $this->CI->input->cookie($this->sess_cookie_name);
		
	}
	
}

?>