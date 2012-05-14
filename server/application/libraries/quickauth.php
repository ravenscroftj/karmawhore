<?php
/**
 * @name QuickAuth
 * @author Dave Blencowe
 * @author_url http://www.daveblencowe.com
 * @version 2.5
 * @license Free for use and modification, without credit given
 *
 * Quickauth authentication library for Codeigniter. Quickauth aims to provide
 * basic features with a minimal of front end content so that it's easy to drop
 * in to an application and customize to your needs
 */
class Quickauth
{

	var $ci;
	var $_tables = array (
		'users' => 'users',
		'groups' => 'groups',
		'group_memberships' => 'group_memberships'
	);
	
	var $redirects = array();
	
	var $locale = array (
		'invalid_login_credentials' => '',
		'succesful_registration' => 'Your account was registered successfully!',
		'logged_out' => '',
		'guest_name' => '',
		'failed_restrict' => '',
		'failed_restrict_nologin' => '',
		'failed_password_reset' => '',
		'successful_password_reset' => ''
	);
	
	var $_email_view = "authentication/email";
	
	var $system_email = "me@example.com";

	function __construct()
	{
		$this->ci =& get_instance();
		
		//load the quickauth config and get default values
		$this->ci->config->load("quickauth", true);
		
		$this->_tables = $this->ci->config->item('tables','quickauth');
		$this->locale = $this->ci->config->item('locale', 'quickauth');
		$this->redirects = $this->ci->config->item('redirects', 'quickauth');

		//set up the login redirect
		$this->ci->db_session->set_flashdata("REDIRECT_TARGET",
		$this->redirects['successful_login']);
	}

	/**
	 * Log a user in using the supplied username and password combination
	 *
	 * @param <string> Supplied Username
	 * @param <string> Supplied Password
	 * @return <bool> True for a succesful login, False for no login + error
	 */
	function login($username, $password)
	{
		$this->ci->db->where('username', $username);
		$this->ci->db->where('password', $this->encrypt($password));
		
		$q = $this->ci->db->get($this->_tables['users']);
		if ($q->num_rows() > 0) {
			$a = $q->row_array();
			$db_session_data = array (
				'userid' => $a['id']
			);

			$this->ci->db_session->set_userdata($db_session_data);
			return true;
		} else {
			ui_set_error($this->locale['invalid_login_credentials']);
			return false;
		}
	}

	/**
	 * Register a user. Data should be supplied in an array format using the
	 * structure specified below
	 *
	 * @param <array> Array, structured as follows: array('username', 'password'
	 * , 'firstname', 'lastname');
	 * @return <bool> true
	 */
	function register($data)
	{
		$data['password'] = $this->encrypt($data['password']);
		$type = $data['type'];
		unset($data['type']);

		$this->ci->db->insert($this->_tables['users'], $data);
		$id = $this->ci->db->insert_id();

		foreach ($type as $var) {
			$array = array (
				'userid' => $id,
				'groupid' => $var
			);

			$this->ci->db->insert($this->_tables['group_memberships'], $array);
		}

		ui_set_message($this->locale['succesful_registration']);
		return true;
	}

	/**
	 * Log a user out by destroying their db_session, then set a ui message and
	 * return
	 *
	 * @return <bool> True, to symbolise a succesful logout. Plus ui_set_message
	 */
	function logout()
	{
		$this->ci->db_session->destroy();
		ui_set_message($this->locale['logged_out']);
		return true;
	}

	/**
	 * Reset a users password and email them the new password. Remember to set the $email_view
	 * variable and build a functioning view. To show the password in the email use echo $pass;
	 * WARNING: This function expects the users username to be in email format
	 * 
	 * @param <string> The users username
	 * @return <string> The new password
	 */
	function recover_password($user)
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";

	    srand((double)microtime()*1000000);
	    $i = 0;
	    $pass = '';
	    while ($i <= 12) {
	        $num = rand() % 33;
	        $tmp = substr($chars, $num, 1);
	        $pass = $pass . $tmp;
	        $i++;
	    }
	    
	    $newpass = $pass;
	    $encrypted_pass = $this->encrypt($pass);
	    
	    // Update the users password
	    $this->ci->db->where('username', $user);
	    $q = $this->ci->db->get($this->_tables['users']);
	    if ($q->num_rows() > 0) {
	    	$user_id = $q->row_array()->id;
	    	$user = $this->user($user_id);
	    	
	    	$this->ci->db->where('id', $user_id);
	    	$this->ci->db->set('password', $encrypted_pass);
	    	$this->ci->db->update($this->_tables['users']);
	    	
	    	// Email the users
	    	$this->ci->load->library('email');
	    	$this->ci->email->to($user['username'], $user['firstname']." ".$user['lastname']);
	    	$this->ci->email->from($this->system_email);
	    	$this->ci->email->subject('Password Recovery');
	    	$data['pass'] = $pass;
	    	$message = $this->load->view($this->_email_view, $data, TRUE);
	    	$this->ci->email->message($message);
	    	$this->ci->email->send();
	    	ui_set_message($this->locale['successful_password_reset']);
	    	return $pass;
	    } else {
	    	ui_set_error($this->locale['failed_password_reset']);
	    }
		return false;
	}

	/**
	 * Get the data on a user from the user table. Also parse their full name in
	 * to $data['name'] for convinience
	 *
	 * @param <int>   The individual users id. If blank will be for current user
	 * @return <array> Data for the user, or guest if not logged in
	 */
	function user($id = null)
	{
		if ($id == null) $id = $this->ci->db_session->userdata('userid');

		// If the user is not signed in then assign them guest credentials and
		// return
		if (!$this->logged_in()) {
			$data->username = "guest";
			$data->name = $this->locale['guest_name'];
			return $data;
		}

		// Get the specified users credentials from the users table and return
		// them
		$this->ci->db->where('id', $id);
		$q = $this->ci->db->get($this->_tables['users']);
		$data = $q->row();
		$data->name = $data->firstname." ".$data->lastname;

		return $data;
	}
	
	/**
	 * Set a user attribute in the database - overwrites if necessary
	 * 
	 * @param string $id <p>The ID of the user to get the attribute for. 
	 * If null, this value is filled using the db_session userdata. </p>
	 * @param string $key <p>The name of the attribute being set</p>
	 * @param string $value <p>The value of the attribute</p>
	 * @return boolean <p>Returns true if the parameter was set ok</p>
	 */
	function set_user_attribute($id = null, $key, $value){
		//get the ID of the user
		if ($id == null) $id = $this->ci->db_session->userdata('userid');

		$data = array("attr_value" => $value);
		
		$this->ci->db->where('user_id', $id, 'attr_key', $key);
		
		$q = $this->db->get('user_attrs');
		
		if($q->num_rows() > 0){
			return $this->db->update("user_attrs", $data);
		}else{
			return $this->db->insert("user_attrs", $data);
		}
	}
	
	/**
	 * Get an attribute value associated with a given user
	 * 
	 * @param string $id <p>The ID of the user to get the attribute for. 
	 * If null, this value is filled using the db_session userdata. </p>
	 * @param string $key <p>The name of the attribute to get</p>
	 * @return string $value <p>Returns the value of the attribute or 
	 * null if not set</p>
	 */
	function get_user_attribute($id = null, $key){
		//get the ID of the user
		if ($id == null) $id = $this->ci->db_session->userdata('userid');
	}

	/**
	 * Check to see if a user is logged in. If not then don't return anything
	 *
	 * @return <bool> Return True if user is logged in, else return nothing
	 */
	function logged_in()
	{
		$id = $this->ci->db_session->userdata('userid');
		if ($id) {
			return true;
		}
	}

	/**
	 * Restrict a controller to a user group, logged in users, or exclude the
	 * function from an existing restriction
	 *
	 * @param <String> The name of a group from the group table, E.g: "admin"
	 * @return Returns no usable values but uses ui_set_error on failed auth
	 */
	function restrict($group = null)
	{
		/* if the argument value is false the page should not be restricted,
	 	* Useful for excluding functions from controllers restricted at a construct
	 	* level
		*/
		if ($group == "false") return;

		/* Anything past here requires at least some form of login so redirect
	 	* if user is not logged in. If $group is null will only allow logged
	 	* in users to access the page
		*/
		if (!$this->logged_in()) {
			ui_set_error($this->locale['failed_restrict_nologin']);
			redirect($this->redirects['login']);
		}

		$userid = $this->ci->db_session->userdata('userid');
		$this->ci->db->where('userid', $userid);
		$q = $this->ci->db->get($this->_tables['group_memberships']);
		$groups = $q->result_array();
		foreach ($groups as $grp) {
			$this->ci->db->where('id', $grp['groupid']);
			$q = $this->ci->db->get($this->_tables['groups']);
			$var = $q->row_array();
			
			
			$user_groups[] = $var['title'];
		}

		if (!in_array($group, $user_groups)) {
			ui_set_error($this->locale['failed_restrict']);
			die();
		}

		return;
	}

	/**
	 * Encrypt a string (usually a password) ready for use within the library
	 * Uses SHA1 encryption against the Codeigniter encryption key
	 *
	 * @param <string> The string to be encrypted
	 * @return <string> The encrypted hash
	 */
	function encrypt($string)
	{
		$key = $this->ci->config->item('encryption_key');
		if (empty($key)) show_error('You must set the encryption key in your config file for Quickauth to function');
		$string = sha1($string.$key);
		return $string;
	}

	/**
	 * Return an array of groups that a user is a member of
	 *
	 * @param <int> A valid UserId
	 * @return <array> A list of group names
	 */
	function get_groups($id)
	{
		$this->ci->db->where('userid', $id);
		$q = $this->ci->db->get($this->_tables['group_memberships']);
		$rst = $q->result_array();
		$groups = array();
		foreach ($rst as $k=>$v) {
			$this->ci->db->where('id', $v['groupid']);
			$q = $this->ci->db->get($this->_tables['groups']);
			$r = $q->row_array();
			$groups[] = $r['title'];
		}
		return $groups;
	}

	/**
	 * Add a group to the database
	 *
	 * @param <string> Group Name
	 * @return <int> Group ID
	 */
	function create_group($title)
	{
		$data['title'] = $title;
		if (!$this->group_exists($title)) {
			$this->ci->db->insert($this->_tables['groups'], $data);
			return $this->ci->db->insert_id();
		} else {
			return $this->get_group_id($title);
		}
		
	}

	/**
	 * Check if a group exists in the system
	 *
	 * @param <string> Group name
	 * @return <bool> Will return true if the group exists
	 */
	function group_exists($title)
	{
		$this->ci->db->where('title', $title);
		$q = $this->ci->db->get($this->_tables['groups']);
		if ($q->num_rows() > 0) {
			return true;
		}
	}

	/**
	 * Get the unique identifier for a group
	 *
	 * @param <string> Group name
	 * @return <int> Group ID
	 */
	function get_group_id($title)
	{
		$this->ci->db->where('title', $title);
		$qry = $this->ci->db->get($this->_tables['groups']);
		return $qry->row()->id;
	}
}
