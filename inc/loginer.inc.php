<?php 

/**
 * 
 * Loginer v1.0 
 * 
 * ============
 * 
 * This is place where magic actually 
 * happend..
 * 
 * 
 */

class Loginer 
{
    /** DB Instance */
    public $_db_instance = false;

    /** Configuration */
    public $_config = false;

    /** Temp */
    public $_temp = [];

    /** UserDataArray */
    public $_user_data = [];

    /** Pulic Signup Error */
    public $_signup_error = false;

    /** Configuration Errors */
    public $_conf_errors = [];

    #####################

    /** Construct App  */
    public function __construct( /** DB INSTANCE */ Mysqli $_db, /** CONF */ $_conf )
    {
        /** Check Database Connection */
        if( $_db->connect_errno )
        {
            die("Database Connection Failed");
        }
        else 
        {
            /**
             * 
             * Connection is established
             * we can continue working... 
             *
             */

            $this->_db_instance = $_db;
            $this->_config = $_conf;
             
        }
    }

    /** Table Exists */
    public function table_exists()
    {
        // Set Query
        $q = "SHOW TABLES LIKE '" . $this->_config['db_table_name'] . "'";

        // Return Result Of Searching For Tables
        return $this->_db_instance->query($q)->num_rows;
    }

    /** Check for security */
    public function configure()
    {

        // Check did table name is created..
        if( !isset($this->_config['db_table_name']) || $this->_config['db_table_name'] == "" )
        {
            $str_err_temp = "You must enter table name!";
            array_push($this->_conf_errors, $str_err_temp);
        }

        // First check is array filled..
        // Array must have at least 2 fields... 
        if( sizeof($this->_config['db_table_data']) < 2 )
        {
            $str_err_temp = "You must have at least 2 fields in your database...";
            array_push($this->_conf_errors, $str_err_temp);
        } 

        // Now Check for Unique
        if( !array_key_exists( $this->_config['db_table_unique'], $this->_config['db_table_data'] ) )
        {
            $str_err_temp = "Unique key is not part of your application!";
            array_push($this->_conf_errors, $str_err_temp);
        }

        // Check for sens data 
        foreach( $this->_config['db_table_sens'] as $k )
        {
            if( !array_key_exists( $k, $this->_config['db_table_data'] ) )
            {
                $str_err_temp = "Sensitive key " . $k . " is not part of your application!";
                array_push($this->_conf_errors, $str_err_temp);
            }
        }

        // Check for sens data 
        foreach( $this->_config['login_fields'] as $k )
        {
            if( !array_key_exists( $k, $this->_config['db_table_data'] ) )
            {
                $str_err_temp = "Login Field " . $k . " is not part of your application!";
                array_push($this->_conf_errors, $str_err_temp);
            }
        }

        // Check did user setup login fields
        if( empty($this->_config['login_fields']) )
        {
            $str_err_temp = "Login Form is not set. Plase add Login Fields in configuration file..";
            array_push($this->_conf_errors, $str_err_temp);
        }

        // Check did user setup lading page
        if( empty($this->_config['login_land_page']) )
        {
            $str_err_temp = "YOU MUST ENTER LANDING PAGE.";
            array_push($this->_conf_errors, $str_err_temp);
        }

        
        // Check did user setup lading page
        if( empty($this->_config['password_algorithm']) )
        {
            $str_err_temp = "PASSWORD HASH ALGORITHM IS NOT SETUP";
            array_push($this->_conf_errors, $str_err_temp);
        }

    }

    /** Create Table  */
    public function create_table()
    {
        // Create Query For Creating Table in 
        // Database.. ( User Select his/her database name )
        $q  = "CREATE TABLE " . $this->_config['db_table_name'] . " ";
        $q .= "(";

        // Create Table Coloumns
        // This will create coloumns with 
        // coloumn type ( varchar, int, .... )
        // Create Standard ID
        $q .= "user_id INT(11) AUTO_INCREMENT PRIMARY KEY,";

        // Add Fields From Config file
        foreach( $this->_config['db_table_data'] as $k => $v )
        {

            // Check did user use simple types for database
            // coloumns.. 
            if( in_array( $v[0], [ "int", "varchar", "text", "float", "double", "char" ] ) && is_numeric( $v[1] ) )
            {
                $q .= $k . " " . strtoupper( $v[0] ) . "(" . $v[1] . "),";
            }
            else 
            {
                return false;
            }
        }

        $q = rtrim($q, ",");

        $q .= ")";

        // Create Table
        return $this->_db_instance->query($q);
    }

    /**
     * 
     * Prevent user to duplicate
     * data in database...
     * 
     */
    public function create_unique()
    {
        /** Create Query */
        $q = "CREATE UNIQUE INDEX loginner_index ON " . $this->_config['db_table_name'] . " (" . $this->_config['db_table_unique'] . ")";
        
        /** echo   */
        return $this->_db_instance->query($q);
    }

    /**
     * 
     * Create Signup Form
     * 
     */
    public function create_signup_form()
    {
        /**
         * 
         * Get All data from
         * configuration array
         * 
         */
        foreach( $this->_config['db_table_data'] as $k => $v )
        {
            if( !in_array( $k, $this->_config['db_table_sens'] ) )
            {
                echo "<label>" . str_replace("_", " ", ucfirst($k)) . "</label><br />";
                echo "<input type='text' name='_lg_" . $k . "' /><br />";
            }
            else
            {   
                echo "<label>" . str_replace("_", " ", ucfirst($k)) . "</label><br />";
                echo "<input type='password' name='_lg_" . $k . "' /><br />";
            } 

        }
    }
    
    /**
     * 
     * Sanitize User Input
     * 
     */
    public function sanitize( /** Post Object */ $_post_data )
    {        
        foreach( $_post_data as $k => $v )
        {
            // Create Temp Var
            $temp_var ='';

            // Create Temp K
            $temp_k = str_replace( '_lg_', '', $k );

            // Prevent Any Unusual inputs
            $temp_var = htmlentities( $v, ENT_QUOTES, 'UTF-8' );

            // Prevent Empty Fields
            if( !$this->_config['db_table_allow_empty'] )
            {
                if( !$temp_var )
                {
                    $this->_signup_error = "Please do not leave any field blank!";
                }
            }

            // Save to Temp
            $this->_temp[$temp_k] = $temp_var;
        }
    }

    /**
     * 
     * Save To Database
     * 
     */
    public function save()
    {

        // For Saving we need to get all
        // fields 
        $tb_fields = "";

        // ... and all values
        $tb_values = [];

        // Number of fields
        $query_fields = "";

        // types
        $param_types = "";

        // If any signup error
        // ocurred before
        // we start this 
        // app will breake;
        if( $this->_signup_error )
        {
            return false;
        }

        foreach( $this->_config['db_table_data'] as $k => $v )
        {

            // Add to fields
            $tb_fields .= ( $k . ", " );

            // add to values
            if( in_array( $v[0], array("varchar", "text", "string") ) )
            {   
                // This is string
                // Wee need to add '
                // $tb_values .= ( "'" . $this->_temp[$k] . "', " );
                array_push($tb_values, $this->_temp[$k]);

                // Create Joker sign
                $query_fields .= "?,";

                // Create Param
                $param_types .= "s";

            }
            else 
            {
                // This is for numeric types
                //$tb_values .= ( $this->_temp[$k] . ", " );
                array_push($tb_values, $this->_temp[$k]);

                // Add joker sign
                $query_fields .= "?,";

                // Add Param types
                $param_types .= "i";
            }

        }

        // Remove ,
        $tb_fields = rtrim($tb_fields, ", ");
        //$tb_values = rtrim($tb_values, ", ");
        $query_fields = rtrim($query_fields, ",");

        // Create query
        $q = "INSERT INTO " . $this->_config['db_table_name'] . " (" . $tb_fields . ") VALUES(" . $query_fields . ")";

        // Make query
        $stmt = $this->_db_instance->prepare($q);

        // Bind Params
        $stmt->bind_param($param_types, ...$tb_values);

        // Execute
        $stmt->execute();

        if( !$stmt->affected_rows || $stmt->affected_rows < 0 )
        {
            $this->_signup_error = "User Already is registered!";
        }

        // Close
        $stmt->close();

    }

    /**
     * 
     * Hash Important Data
     * All Settings are provided in 
     * configuration file.
     * 
     */
    public function hash_sensitive()
    {
        // Sensitive data are stored
        // in config..
        foreach( $this->_config['db_table_sens'] as $k )
        {
            $this->_temp[$k] = password_hash( ( $this->_config['password_salt'] . $this->_temp[$k] ), $this->_config['password_algorithm'] );
        }
    }

    /**
     * 
     * Save Data to Database
     * 
     */
    public function signup(/** Post Object */ $_post_data)
    {

        // Sanitize Input
        $this->sanitize($_post_data);

        // Hash Important data
        $this->hash_sensitive();

        // Save
        $this->save();

    }

    /**
     * 
     *  Create Login Form 
     * 
     */
    public function create_login_form()
    {
        /**
         * 
         * Get All data from
         * configuration array
         * 
         */
        foreach( $this->_config['db_table_data'] as $k => $v )
        {
             if( in_array( $k, $this->_config['login_fields'] ) )
             {
                if( !in_array( $k, $this->_config['db_table_sens'] ) )
                {
                    echo "<label>" . str_replace("_", " ", ucfirst($k)) . "</label><br />";
                    echo "<input type='text' name='_lg_" . $k . "' /><br />";
                }
                else
                {   
                    echo "<label>" . str_replace("_", " ", ucfirst($k)) . "</label><br />";
                    echo "<input type='password' name='_lg_" . $k . "' /><br />";
                }
            }
        }
    }

    /**
     * 
     * Login To System
     * 
     */
    public function login( /** Post Object */ $_post_data )
    {
    
        /** Important array */
        $sensitive_data = [];

        /** Real Array */
        $real_array = [];

        /** Usual data */
        $usual_data = [];

        /** Query String */
        $query_fields = "";

        /** QueryParam String */
        $query_param_string ="";

        /** Param String */
        $param_types = [];

        /** Entered values */
        $entered_sens_values = [];

        /** Entered values */
        $entered_usual_values = [];

        /** Guessed  */
        $guessed = 0;

        /** Get Sensitive Data */
        foreach( $_post_data as $k => $p )
        {
           // temp k without _lg_
           $temp_k = str_replace("_lg_", "", $k);

           /** Check is post data valid form field */
           if( in_array( $temp_k, $this->_config['login_fields'] ) )
           {
                // ADD to real array
                $real_array[$temp_k] = $p; 

                // Separate
                if( in_array( $temp_k, $this->_config['db_table_sens'] ) )
                {
                    // Add KEY
                    array_push( $sensitive_data, $temp_k );

                    // Add VALUE
                    array_push( $entered_sens_values, $p );
                }
                else 
                {
                    // ADD KEY
                    array_push( $usual_data, $temp_k );

                    // ADD VALUES
                    array_push( $entered_usual_values, $p );
                }
           }
           
        }

        /** Fields */
        $fields = count( $sensitive_data );

        /** Lets Create Query from array */
        foreach( $usual_data as $k )
        {
            /** Lets Make Query */
            if( in_array( $this->_config['db_table_data'][$k][0], array("varchar", "text", "string") ) )
            {
                // Add to WHERE query
                $query_fields .= ( $k . "= ? AND " );

                // Add Query Param
                array_push( $param_types, "s" );
                
                // Query PAram String
                $query_param_string .= "s";
            }
            else 
            { 
                // Add to WHERE query
                $query_fields .= ( $k . "= ? AND " );

                // Add Query Param
                array_push( $param_types, "i" );
                
                // Query PAram String
                $query_param_string .= "i";
            }
        }

        /** Remove Last AND */
        $query_fields = rtrim($query_fields, "AND ");

        /** Create Query */
        $q = "SELECT * FROM " . $this->_config['db_table_name'] . " WHERE (" . $query_fields . ") ";

        /** Preapre Query */
        $stmt = $this->_db_instance->prepare( $q );

        /** Bind Params */
        $stmt->bind_param( $query_param_string, ...$entered_usual_values );

        /** Execute */
        $stmt->execute();

        /** Return Result */
        $mysqli_result = $stmt->get_result();

        /** Close */
        $stmt->close();

        /** If such a user exists in db */
        /** Get His data... */
        if( $mysqli_result->num_rows )
        {
            // List All users who match
            // params... It will be probably
            // only one....
            while( $res = $mysqli_result->fetch_assoc() )
            {
                // User temp array
                $user_temp = $res;
                // Lets check all sensitive data
                foreach( $sensitive_data as $sens )
                {
                    if( password_verify( ($this->_config['password_salt'] . $real_array[$sens] ), $res[$sens] ) )
                    {
                        $guessed++;
                    }
                }

                // If guessed combination 
                // is equal to sensitive fields user is logged in
                if( $guessed == $fields )
                {
                    // Create User array
                    foreach( $user_temp as $k => $v )
                    {
                        if( in_array( $k, $this->_config['db_table_sens'] ) )
                        {
                            continue;
                        }
                        else 
                        {
                            $this->_user_data[$k] = $v;
                        }
                    }

                    // Escape method and 
                    // return to app....
                    return true;
                }
            }

            // User is not logged in
            return false;
        }
        else 
        {
            // User is not logged in
        }

    }

    /** 
     * 
     * Return Data From Loged user
     * 
     */
    public function get_user_data()
    {
        return $this->_user_data;
    }

    /**
     * 
     * Redirect to real locaion
     * 
     */
    public function open_landing_page()
    {
        return header("Location: " . $this->_config['login_land_page'] );
    }

    /**
     * 
     * Login Error
     * 
     */
    public function login_error()
    {
        return header("Location: " . $_SERVER['PHP_SELF']);
    }

    /**
     * 
     * Signup Errors
     * 
     */
    public function signup_errors()
    {
        return $this->_signup_error;
    }
    
    /**
     * 
     * Prevent Double
     * Loging
     * 
     */
    public function prevent_double_loging()
    {
        if( @$_SESSION['loginer_token'] )
        {
            header("Location: " . $this->_config['login_land_page'] );
        }
        else 
        {
            return false;
        }
    }

    /**
     * 
     * Public static method
     * check for session...
     * 
     */
    public static function logged_in()
    {
        return ( @$_SESSION['loginer_token'] ) ? true : false;
    }

    /**
     * 
     * Configuration errors
     * 
     */
    public function configuration_errors()
    {
        return $this->_conf_errors;
    }

}