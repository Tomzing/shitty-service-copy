<?php
session_start();

include("mysqliDB.php");
//Hvis fag pin ikke er satt, send personen tilbake
//if (!isset($_SESSION['gittPin'])) {
//    header('Location: gjestInputFagPin.php');
//    exit();
//}
$idChatLog = $_SESSION['chatId'];
$comment = $_POST['content'];
$isSvar = $_SESSION['isSvar'];

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
header("Access-Control-Allow-Origin: *");
//error_reporting();


    $stmt = $con->prepare("INSERT INTO reportedposts (idChatlog, comment, isSvar) VALUES (?,?,?)");
    $con->set_charset("utf8");

    $stmt->bind_Param("iss", $idChatLog,$comment, $isSvar);

    if($stmt->execute()) {
        echo "Melding sendt";
    }

    $stmt->close();
    $con->close();


    //header("Refresh:1; url=../index.php");



?>
