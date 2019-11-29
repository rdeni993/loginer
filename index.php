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

// Check for configuration errors
$loginer->configure();

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
    echo "<pre>";
    echo "Run Application.... OK\n";
    echo "Check For errors....\n";

    if( $loginer->configuration_errors() )
    {
        echo "Error occured!\n";
        $temp_err = $loginer->configuration_errors();

        echo  "<b>". sizeof($temp_err) . " error(s) occured..\n</b>";

        foreach( $temp_err as $err )
        {
            echo $err . "\n";
        }

        echo "\n";
    }
    else 
    {
        if($loginer->create_table())
        {
            if( $loginer->create_unique() )
            {
                echo "Table is created.....\n";
                echo "Unique key is added...\n";
                echo "Forms are created....\n";
            }
        }
        else 
        {
            echo "Table is not created!..\n";
            echo "Unknown error is occured..\n";
        }
    }

}