<?php 

/**
 * 
 * Set Up Your Database
 * Connection.. 
 * 
 */

// Database HOST
$db_host = "localhost"; // default: localhost

// Database USER
$db_user = "rdeni";

// Database PASSWORD
$db_pass = "rdeni993";

// Database NAME
$db_name = "loginer";

/**
 * 
 * Create DatabaseObject
 * 
 */

$_db = new Mysqli( $db_host, $db_user, $db_pass, $db_name );