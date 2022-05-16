<?php
    session_start();
    $_SESSION['gittPinn'] = $_GET['inputPin'];

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    include("mysqliDB.php");
    header("Access-Control-Allow-Origin: *");
    //error_reporting();

    header("Refresh:0; url=../kommentarer.php");
?>