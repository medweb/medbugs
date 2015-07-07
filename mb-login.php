<?php
session_start();
/**
 * 
 * Login page for the administration section. 
 *
 */

require_once( dirname( __FILE__ ) . '/mb-includes/mb-config.php' );
require_once( dirname( __FILE__ ) . '/mb-includes/mb-mysql.php' );
require_once( dirname( __FILE__ ) . '/mb-includes/mb-app.php' );

$error = false;

if ( $_GET['logout'] ) {
	if ( $_GET['logout'] == 'true' ) {
		end_session( $_SESSION['MedBugs_user_id'], $_SESSION['MedBugs_session_id'] );
	}
}

if ( '' != $_SESSION['MedBugs_user_id'] && '' != $_SESSION['MedBugs_session_id'] ) {
	global $db;
	
	$user_id = $_SESSION['MedBugs_user_id'];
	$session_id = $_SESSION['MedBugs_session_id'];
	
	if ( verify_session( $user_id, $session_id ) ) {
		$admin_url = 'http://' . $_SERVER["SERVER_NAME"] . '/mb-admin/admin.php';
		
		header( "Location: $admin_url" );
	}	
}

if ( $_POST['username'] && $_POST['password'] ) {
	global $db;
	
	$username = sanitize( $_POST['username'] );
	$password = sanitize( $_POST['password'] );
	
	if ( is_user( $username, $password ) ) {
		start_session( $username );
		
		$admin_url = 'http://' . $_SERVER["SERVER_NAME"] . '/mb-admin/admin.php';
		
		header( "Location: $admin_url" );
	} else {
		$error = true;
	}
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title>MedBugs Login</title>
    
    <meta name="viewport" content="initial-scale=1 maximum-scale=1">
    <meta name="robots" content="noindex,nofollow">
    
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?php template_directory(); ?>/css/style.css">
    
    <!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script type="text/javascript" src="http://use.typekit.com/sgo1evb.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>
<body class="login-page">

<section class="login-form">
	<h2>Welcome to MedBugs</h2>
	
	<?php
	if ( $error ) {
		echo '<span class="login-failed">Sorry, your username of password was not correct.</span>';
	}
	?>
	
	<form name="login" action="<?php echo '/mb-login/'; ?>" method="post">
		<fieldset class="text" id="email-address">
			<div class="left">
				<label for="username">Username</label>
			</div>
			
			<div class="right">
				<input class="text" type="text" name="username">
			</div>
		</fieldset>
		
		<fieldset class="text" id="bug-name">
			<div class="left">
				<label for="password">Password</label>
			</div>
			
			<div class="right">
				<input class="text" type="password" name="password">
			</div>
		</fieldset>
		
		<a class="back-to-medbugs" href="/">&larr; Back to MedBugs</a>
		
		<input type="submit" class="btn login" id="login" value="Log In">
	</form>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='js/jquery-1.7.1.min.js'>\x3C/script>")</script>
<script src="<?php template_directory(); ?>/js/jquery.easing.1.3.js"></script>
<script src="<?php template_directory(); ?>/js/jquery.color.js"></script>
<script src="<?php template_directory(); ?>/js/engine.js"></script>
</body>
</html>