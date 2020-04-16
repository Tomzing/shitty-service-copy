<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'reportedposts';
$DATABASE_PASS = 'RYFKIfUpN8qvrk0r';
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
define( 'DB_USER', 'reportedposts' ); // set database user
define( 'DB_PASS', 'RYFKIfUpN8qvrk0r' ); // set database password
define( 'DB_NAME', 'virusnet' ); // set database name
define( 'DISPLAY_DEBUG', false ); //display db errors?


    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LdUcegUAAAAADuXCiNmRXaU1rMQD4xdRjdmi7TO';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if ($recaptcha->score >= 0.5) {
        echo 'Du feilet captchaen din dustemikkel';
        die;
    }

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
