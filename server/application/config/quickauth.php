<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Configuration file for quickauth module
 * 
 * This file contains configuration values for the quickauth user authentication
 * module.
 * 
 */

/***
 * This is the controller that the user is redirected to by default 
 * after logging in to the system.
 * 
 */
$config['login_redirect_url'] = "admin/index";

/***
 * Map of tables in terms of their expected name => actual name
 * 
 */
$config['tables'] = array (
		'users' => 'users',
		'groups' => 'groups',
		'group_memberships' => 'group_memberships'
);

/**
 * Map of actions to the names of the controllers users should be sent to
 * 
 * For example login - when the user is required to login to view the content
 * can be mapped to authentication/login - the view that controls the login 
 * process.
 */
$config['redirects'] = array(
		'login' => "authentication/login",
		'successful_login' => "authentication/welcome",
		''
);

$config['locale'] = array (
		'invalid_login_credentials' => 'Invalid username/password combination, please try again.',
		'succesful_registration' => '',
		'logged_out' => '',
		'guest_name' => '',
		'failed_restrict' => '',
		'failed_restrict_nologin' => '',
		'failed_password_reset' => '',
		'successful_password_reset' => ''
	);;
?>