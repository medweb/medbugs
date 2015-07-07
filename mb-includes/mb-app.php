<?php
/**
 * 
 * Core MedBugs functionality
 * 
 */

/** 
 * Get public parts
 * 
 */
function get_header() {
	require_once( dirname( __DIR__ ) . '/mb-content/header.php' );
}

function get_footer() {
	require_once( dirname( __DIR__ ) . '/mb-content/footer.php' );
}

function get_sidebar() {
	require_once( dirname( __DIR__ ) . '/mb-content/sidebar.php' );
}

function get_admin_sidebar() {
	require_once( dirname( __DIR__ ) . '/mb-admin/sidebar.php' );
}

function mb_header() {
	session_start();
}

function mb_footer() {
	?>
	<script>
		var mb_localized = {
			ajaxURL : '<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/mb-includes/mb-ajax.php'; ?>',
			userIP : '<?php echo $_SERVER['REMOTE_ADDR']; ?>'
		}
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='js/jquery-1.7.1.min.js'>\x3C/script>")</script>
	<script src="<?php template_directory(); ?>/js/jquery.easing.1.3.js"></script>
	<script src="<?php template_directory(); ?>/js/jquery.color.js"></script>
	<script src="<?php template_directory(); ?>/js/engine.js"></script>
	<?php
	if ( current_user() ) echo '<script src="/mb-admin/js/admin-engine.js"></script>' . "\n";
}

/**
 * Create new user
 * 
 * @PARAM email: new user's email address
 */
function create_user( $email ) {
	global $db;
	
	$username = explode( '@', $email);
	$username = $username[0];
	
	// create temporary password
	$password = substr( sha1( base64_encode( time() * rand( 11111111, 99999999 ) ) ), 0, 11 );
	
	// send the new user their login information
	$subject = 'Welcome to MedBugs!';
	$message = 'A new account has been created for you at http://bugs.med.ucf.edu/mb-login/.' . "\n";
	$message .= 'Please log in using the temporary password provided, then update to a permanent password.' . "\n";
	$message .= '_______________________________________________' . "\n\n";
	$message .= 'Username: ' . $username . "\n";
	$message .= 'Temporary Password: ' . $password . "\n\n";
	$headers = 'From: BugMaster <bugmaster@bugs.med.ucf.edu>' . "\r\n";
	
	mail( $email, $subject, $message, $headers );
	
	return $db->create_user( $username, $email, $password );
}

/**
 * Change user password
 * 
 */
function change_user_password( $user_id, $oldPassword, $newPassword ) {
	global $db;
	
	return $db->change_user_password( $user_id, $oldPassword, $newPassword );
}

/**
 * Delete user
 * 
 */
function delete_user( $user_id ) {
	global $db;
	
	return $db->delete_user( $user_id );
}

/**
 * Get all users
 * 
 * Optional parameters:
 * @PARAM order: ASC or DESC (default: DESC)
 * @PARAM order_by: user_id, username, or email (default: user_id)
 * 
 */
function get_users( $args = null ) {
	global $db;
	
	return $db->get_users( $args );
}

function current_user() {
	global $db;
	
	return $db->current_user();
}

function the_sidebar_content() {
	global $db;
	
	echo $db->the_sidebar_content();
}

function update_sidebar( $html_string ) {
	global $db;
	
	echo $db->update_sidebar( $html_string );
}

function template_directory() {
	echo '/mb-content';
}

/**
 * Sanitize input
 * 
 */
function sanitize( $string ) {
	global $db;
	
	return $db->sanitize( $string );
}

/**
 * System user alerts
 * 
 */

function new_bug_alert( $args = null ) {
	global $db;
	
	// $args are an array
	$bug_title = $args[1];
	$bug_description = $args[2];
	$submitter_email = $args[3];
	$submit_date = $args[4];
	
	$submit_date = explode( '-', $submit_date );
	$year = $submit_date[0];
	$monthNum = $submit_date[1];
	$dayNum = $submit_date[2];
	$monthStr = $submit_date[3];
	$dayStr = $submit_date[4];
			
	$submit_date = $dayStr . ', ' . $monthStr . ' ' . $dayNum . ', ' . $year;
	
	/* FIXME
	 * should loop through all admins,
	 * check if they have the "alert me about new bugs" option checked,
	 * and add them to the $to variable
	 * 
	$users = $db->get_users();
	
	$to = '';
	
	foreach ( $users as $user ) {
		$to .= $user->email . ', ';
	}
	
	$to = rtrim( $to, ', ' );
	*/
	
	$subject = 'New Bug!';
	$message = 'A new bug has been found. Here\'s some information about it:' . "\n";
	$message .= '_______________________________________________' . "\n\n";
	$message .= 'Bug name: ' . $bug_title . "\n";
	$message .= 'About this bug: ' . $bug_description . "\n\n";
	$message .= 'Submitted by <' . $submitter_email . '> on ' . $submit_date . "\n\n"; 
	$headers = 'From: BugMaster <bugmaster@bugs.med.ucf.edu>' . "\r\n";
	
	mail( 'medweb@ucf.edu', $subject, $message, $headers );
}
 
function alert_admin( $event = null ) {
	if ( null != $event ) {
		switch ( $event ) {
			case 'new_bug':
				if ( 5 == func_num_args() ) new_bug_alert( func_get_args() );
				break;
			
			default:
				null;
				break;
		}
	}
}

/** 
 * Create new bug
 * 
 */
function create_bug( $title, $description, $email, $IP ) {
	global $db;
	
	date_default_timezone_set( 'America/New_York' );
	$date = date( 'Y-m-d-F-l' ); // e.g. 2011-11-30-November-Wednesday
	
	alert_admin( 'new_bug', $title, $description, $email, $date );
	
	return $db->create_bug( $title, $description, $email, $IP, $date );
}

/**
 * Vote bug up
 * 
 */
function vote_bug_up( $bug_id ) {
	global $db;
	
	echo $db->vote_bug_up( $bug_id );
}

/**
 * Resolve bug
 * 
 */
function resolve_bug( $bug_id ) {
	global $db;
	
	return $db->resolve_bug( $bug_id );
}

/**
 * Set bug as currently being worked on
 * 
 */
function set_bug_as_current( $bug_id ) {
	global $db;
	
	return $db->set_bug_as_current( $bug_id );
}

/** 
 * Get all bugs 
 * 
 * Optional parameters:
 * @PARAM order: ASC or DESC (default: DESC)
 * @PARAM order_by: bug_id, title, description, email, date, votes, status, current, or an array with a primary and secondary sort column (default: bud_id)
 * @PARAM status: active or resolved (default: active)
 * 
 */
function get_bugs( $args = null ) {
	global $db;
	
	return $db->get_bugs( $args );
}

/** 
 * Initialize the MedBugs public side
 * 
 */
function mb() {
	require_once( dirname( __DIR__ ) . '/mb-content/page.php' );
}

/** 
 * Initialize the MedBugs admin side
 * 
 */
function mb_admin() {
	require_once( dirname( __DIR__ ) . '/mb-admin/index.php' );
}

/**
 * Verify that logged in user is on an admin page
 * 
 */
function on_admin_page() {
	$uri = strtolower( $_SERVER["REQUEST_URI"] );
	
	if ( false != strpos( $uri, 'admin' ) ) {
		return true;
	}
	
	return false;
}

/**
 * Verify if login credentials are met
 * 
 */
function is_user( $username, $password ) {
	global $db;
	
	return $db->is_user( $username, $password );
}

/** 
 * Check if user is logged in 
 * 
 */
function logged_in() {
	if ( '' != $_SESSION['MedBugs_user_id'] ) {
		return true;
	}
	
	return false;
}

/**
 * Start user session
 * 
 */
function start_session( $username ) {
	global $db;
	
	return $db->start_session( $username );
}

/**
 * Verify session
 * 
 */
function verify_session($user_id, $session_id) {
	global $db;
	
	return $db->verify_session( $user_id, $session_id );
}

/** 
 * End user session 
 * 
 */
function end_session( $user_id, $session_id ) {
	global $db;
	
	return $db->end_session( $user_id, $session_id );
}
?>