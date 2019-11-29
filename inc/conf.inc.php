<?php 

/**
 * 
 * Configuration File
 * ==================
 * 
 */

$_conf = array();

// Table Name
$_conf['db_table_name'] = "";

// Database Table Data
// Set Up this table data
// before first using this 
// plugin...
//
//  key [ type, length ]
//
$_conf['db_table_data'] = [];

// One of your field must me 
// unique ( username, nickname, email, .... )
$_conf['db_table_unique'] = "";

// Choose which data to hash
// This data will be stored as
// crypted string... 
// Add only data u need to 
// be crypted...
$_conf['db_table_sens'] = [
];

// ALLOW EMPTY FIELDS
// Its important to have 
// all data saved inside database... 
// DEFAULT: false
$_conf['db_table_allow_empty'] = false;

// IMPORTANT THING
// Choose Security Alghoritm
// Available Alghoritms
// 1) PASSWORD_DEFAULT
// 2) PASSWORD_BCRYPT
// 3) PASSWORD_ARGON2I
// 4) PASSWORD_ARGON2ID 
// 5) PASSWORD_ARGON2_DEFAULT_MEMORY_COST
// 6) PASSWORD_ARGON2_DEFAULT_TIME_COST
// 7) PASSWORD_ARGON2_DEFAULT_THREADS
$_conf['password_algorithm'] = PASSWORD_BCRYPT;

// Password HASH Be Carefull and create powerfull
// Salt. This will keep your data maximum safe.. 
// NOTICE: This cannot be change after you start
// application once..
$_conf['password_salt'] = "vGA1O9wmRjrwAVXD98HNOgsNpDczlqm3Jq7KnEd1rVAGv3Fykk1a";

// LOGIN DETAILS
// This will create combo box
// with fields to enter data user
// need to provide to register on 
// system... 
// NOTICE: Fields must be same as fields inside
// above array..... 
$_conf['login_fields'] = [

];

// REDIRECT PAGE
// After we finish loading select page where 
// we need to landing...
$_conf['login_land_page'] = '';