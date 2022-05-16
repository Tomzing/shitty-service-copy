<?php
session_start();



function lagCon($user, $password) {
    $conMysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    $conMysqli->set_charset("utf8");
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