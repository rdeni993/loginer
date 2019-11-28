<?php 

session_start();

/**
 * 
 * At the end logout from
 * page... 
 * 
 * Because we use session at all the time
 * we need to destroy sessions at the end..
 * 
 */

session_destroy();

/**
 * 
 * Go To Login Page
 * 
 */

header("Location: login.php");