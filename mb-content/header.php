<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title>MedBugs</title>
    
    <meta name="viewport" content="initial-scale=1 maximum-scale=1">
    <meta name="robots" content="noindex,nofollow">
    
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?php template_directory(); ?>/css/style.css">
    
    <!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script type="text/javascript" src="http://use.typekit.com/sgo1evb.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<?php mb_header(); ?>
</head>
<body>
	
<header>
	<div class="interior content-width">
		<a href="http://bugs.med.ucf.edu"><h1>MedBugs</h1></a>
		
		<?php
		if ( logged_in() && on_admin_page() ) {
			?>
			<ul class="navigation">
				<li><a href="/mb-login/?logout=true">Log Out</a></li>
			</ul>
			<?php
		} else if ( logged_in() && !on_admin_page() ) {
			?>
			<ul class="navigation">
				<li><a href="/mb-login">Admin Dashboard</a></li>
			</ul>
			<?php
		} else {
			?>
			<ul class="navigation">
				<li><a href="/mb-login">Admin Login</a></li>
			</ul>
			<?php
		}
		?>
	</div>
</header>