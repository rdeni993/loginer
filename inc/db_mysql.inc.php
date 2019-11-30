<?php 

/**
 * 
 * Set Up Your Database
 * Connection.. 
 * 
 */

// Database HOST
$db_host = ""; // default: localhost

// Database USER
$db_user = "";

// Database PASSWORD
$db_pass = "";

// Database NAME
$db_name = "";

/**
 * 
 * Create DatabaseObject
 * 
 */

$_db = new Mysqli( $db_host, $db_user, $db_pass, $db_name );