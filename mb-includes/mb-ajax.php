<?php
require_once( dirname( __DIR__ ) . '/mb-includes/mb-config.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-mysql.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-app.php' );

/**
 * Public Ajax interface
 * 
 */

if ( $_POST['action'] ) {
	$action = $_POST['action'];
	
	switch ( $action ) {
		case 'new_bug':
			create_bug( $_POST['title'], $_POST['description'], $_POST['email'], $_POST['ip'] );
			break;
		
		case 'resolve_bug':
			resolve_bug( $_POST['bug_id'] );
			break;
			
		case 'update_sidebar':
			update_sidebar( $_POST['sidebar_content'] );
			break;
			
		case 'vote_bug_up':
			vote_bug_up( $_POST['bug_id'] );
			break;
			
		case 'current':
			set_bug_as_current( $_POST['bug_id'] );
			break;
			
		case 'new_user':
			create_user( $_POST['email'] );
			break;
			
		case 'change_user_password':
			echo change_user_password( $_POST['user_id'], $_POST['old_password'], $_POST['new_password'] );
			break;
			
		case 'delete_user':
			delete_user( $_POST['user_id'] );
			break;
		
		default:
			echo 'Error: you must set an action for this operation!';
			break;
	}
}
?>