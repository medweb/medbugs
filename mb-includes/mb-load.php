<?php
/**
 * Load application depedencies
 * then start MedBugs
 */

require_once( dirname( __DIR__ ) . '/mb-includes/mb-config.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-user.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-bug.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-mysql.php' );
require_once( dirname( __DIR__ ) . '/mb-includes/mb-app.php' );

mb();
?>