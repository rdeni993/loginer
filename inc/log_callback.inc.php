<?php 

/**
 * 
 * This is Class where developer can
 * develop their own portion of code. 
 * 
 * You can send email to new registered users 
 * for example or do anything you like becuase
 * using this class you will have access to 
 * all details user put in form... 
 * 
 */

class Log_Callback 
{
    // Keep Loginer Object
    private $_loginer;

    // Constructor 
    // =====
    // Take Loginer THIS pointer
    // as param..
    public function __construct(/** Loginer Class */ Loginer $loginer)
    {
        $this->_loginer = $loginer;
    }

    // CALLBACK
    // ========
    // This is place where you can add
    // Whatever you want.... 

    // If you want to have access to data
    // that user is entered in form you can access
    // them trough
    //
    // $this->_loginer->_temp[];
    //
    // This array keep all data user have enter
    //
    // If you try to access configuration file
    // you can do it trough
    //
    // $this->_loginer->_config[];
    //
    // If you want to access database you can do it trough
    //
    // $this->_db_instance;

    public function callback()
    {

    }
    
}

?>