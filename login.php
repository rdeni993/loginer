<?php 
    // SESSION 
    session_start();

    // Include important data
    include "inc/db_mysql.inc.php";
    include "inc/loginer.inc.php";
    include "inc/conf.inc.php";

    // Create Loginer OBJ
    $loginer = new Loginer(/* MySQL DB Instance */ $_db, /**/ $_conf);

    // Prevent Any double Loging
    $loginer->prevent_double_loging();

    // Control Signup process
    if( @isset($_POST['_lg_form_confirm']) )
    {
        if($loginer->login(/** Pass POST array to object */ $_POST))
        {
            // get data from user database
            $user_data = $loginer->get_user_data();

            // Set Session
            foreach( $user_data as $k => $v)
            {
                // Add to session 
                $_SESSION[ "_lg_" . $k] = $v;
            }

            // Session token
            $_SESSION['loginer_token'] = sha1(time());

            // Start Application
            $loginer->open_landing_page();

        }
        else 
        {
            $_SESSION['loginer_error'] = true;
        }  
    }

?>
        <!doctype html>
        <html>
        <head>
            <!-- Title -->
            <title>Loginner Sign Up Script</title>
            <!-- Style -->
            <style>
            body, html 
            {
                background-color: #5164b5;
            }
            .login-box 
            {
                width: 400px!important;
                height: auto;
                margin: auto;
                margin-top: 50px;
                background: #fff;
                border: 1px solid #c1c1c2;
                border-radius: 10px;
                box-shadow: 2px 2px 2px #888;
                padding: 20px;
                font-family: sans-serif;
                font-weight: tiny;
            }
            .login-box label
            {
                margin-left: 50px;
                font-size: 0.8em;
                color: #696b70;
            }
            .login-box input 
            {
                width: 300px;
                margin:0  50px;
                height: 30px;
                border-radius: 5px;
                margin-bottom: 12px;
            }
            .login-box .register-text
            {
                font-size: .8em;
                color: #696b70;
                margin-left: 50px;
            }
            .login-box .error
            {
                font-size: .8em;
                color: #696b70;
                margin-left: 50px;
                color: #f56342;
            }
            </style>
            <!-- JS -->
        </head>
        <body>
            <div class="login-box">
                <h1>Login</h1>
                <hr />
                <form class="loginer_signup_form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="_lg_form_confirm" value="1" />
                    <?php 
                    
                        // Create Input Elements
                        $loginer->create_login_form();
                    
                    ?>
                    <br />
                    <input type="submit" name="_lg_submit_button" id="_lg_submit_button" value="Submit Form" />
                    <br />
                    <p class="register-text">If you don't have account please <a href="sign_up.php">Register Here</a></p>
                    <?php if( @$_SESSION['loginer_error'] ): ?>
                    <p class="error">Login Data are not Correct!</p>
                    <?php endif; ?>
                </form>
            </div>
        </body>
        </html>
