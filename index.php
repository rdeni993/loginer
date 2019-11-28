<?php 

session_start();

/**
 * 
 * Welcome to Loginer
 * ==================
 * 
 * 22.11.2019
 * 
 * Script for control log in process. Using this script
 * developer can easily control process of
 * sigin and login... 
 * 
 * 
 */

include "inc/db_mysql.inc.php";
include "inc/loginer.inc.php";
include "inc/conf.inc.php";

// Create Loginer Object
$loginer = new Loginer($_db, $_conf);

// If table is created already
// app will redirect user to 
// Login HTML
if( $loginer->table_exists() )
{
    // redirect to page "login.php"
    header("Location: login.php ");
}
else 
{
    if($loginer->create_table())
    {
       if( $loginer->create_unique() )
       {
           echo "Tabela je kreirana.. Dodan je kljuc";
       }
    }
    else 
    {
        echo "Tabela nije kreirana";
    }
}