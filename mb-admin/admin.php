<?php
session_start();
/**
 * 
 * Load application depedencies
 * then start MedBugs admin
 * 
 */

require_once( dirname( __DIR__ ) . '/mb-includes/mb-config.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-user.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-bug.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-mysql.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-app.php' );

if ( logged_in() ) {
	// renew session_start due to user activity
	verify_session( $_SESSION['MedBugs_user_id'], $_SESSION['MedBugs_session_id'] );
	
	mb_admin();
} else {
	$login_url = 'http://' . $_SERVER["SERVER_NAME"] . '/mb-login/';
		
	header( "Location: $login_url" );
}
?>