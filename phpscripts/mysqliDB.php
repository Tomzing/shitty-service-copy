<?php
    session_start();

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '1337hackermangruppe09';
    $DATABASE_NAME = 'virusnet';
    // Try and connect using the info above.
    $conMysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    $conMysqli->set_charset("utf8");
    if (mysqli_connect_errno() ) {
        // If there is an error with the connection, stop the script and display the error.
        die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $con = mysqli_connect("localhost", "root", "1337hackermangruppe09", "virusnet");
    define( 'DB_HOST', 'localhost' ); // set database host
    define( 'DB_USER', 'root' ); // set database user
    define( 'DB_PASS', '1337hackermangruppe09' ); // set database password
    define( 'DB_NAME', 'virusnet' ); // set database name
    define( 'DISPLAY_DEBUG', false ); //display db errors?

    function lagCon($user, $password) {
        define('DB_HOST', 'localhost');
        define('DB_USER', $user);
        define('DB_PASS', $password);
        define('DB_NAME', 'virusnet');
        define('DISPLAY_DEBUG', false);

        $con = mysqli_connect("localhost",$user,$password,"virusnet");

        return $con;
    }

    require_once( 'db.php' );

    //Initiate the class
    $db = new DB();

    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    return $ip;
    }
?>