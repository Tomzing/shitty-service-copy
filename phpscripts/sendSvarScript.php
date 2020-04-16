<?php 
    session_start();


    //Hvis fag pin ikke er satt, send personen tilbake
    //if (!isset($_SESSION['gittPin'])) {
    //    header('Location: gjestInputFagPin.php');
    //    exit();
    //}

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

    $valgtFag = $_SESSION['valgtFag'];
    $sendeAnonymt = $_POST['sendAnonymt'];
    $idchatlog = $_SESSION['chatid'];

    $brukertype = $_SESSION['typebruker'];


    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    header("Access-Control-Allow-Origin: *");
    //error_reporting();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'fag';
$DATABASE_PASS = 'pfmrtszv7855z0AR';
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
define( 'DB_USER', 'fag' ); // set database user
define( 'DB_PASS', 'pfmrtszv7855z0AR' ); // set database password
define( 'DB_NAME', 'virusnet' ); // set database name
define( 'DISPLAY_DEBUG', false ); //display db errors?

    $sqlFinnFag = "SELECT * FROM fag WHERE idfag = '$valgtFag'";
    $resultsFinnFag = $db->selectSQL($sqlFinnFag);
mysqli_close($con);

    //Hvis brukernavn ikke er satt så er man en gjest
    if(!isset($_SESSION["brukernavn"])) {
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'chatlog';
        $DATABASE_PASS = 'vJ4V04Jd91j8ggLB';
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
        define( 'DB_USER', 'chatlog' ); // set database user
        define( 'DB_PASS', 'vJ4V04Jd91j8ggLB' ); // set database password
        define( 'DB_NAME', 'virusnet' ); // set database name
        define( 'DISPLAY_DEBUG', false ); //display db errors?
        $date = date("Y-m-d H:i:s");

        $avsenderID = 0;

        $content = htmlentities($_POST['content']);
    
        $stmt = $con->prepare("INSERT INTO svar (idchatlog, content, timestamp, avsenderID, fagid, brukertype) VALUES (?,?,?,?,?,?)");
        $con->set_charset("utf8");

        $stmt->bind_Param("isssis", $idchatlog, $content, $date, $avsenderID, $valgtFag, $brukertype);

        if($stmt->execute()) {
            echo "Melding sendt";
        }

        $stmt->close();
        $con->close();

        //header("Location: ../svar.php?inputPin=".$inputPin."&chatid=".$idchatlog.);
        header("Refresh:1; url=../svar.php");

    }
    else {
        $date = date("Y-m-d H:i:s");

        if($sendeAnonymt === "sendAnonymt") {
            $brukertype = "gjest";
            $avsenderID = $_SESSION["brukernavn"];
        }
        else if($brukertype === "foreleser") {
            $avsenderID = $_SESSION["brukernavn"];
            $brukertype = $_SESSION["brukernavn"];
        }
        else {
            $avsenderID = $_SESSION["brukernavn"];
            $brukertype = "student";
        }
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'svar';
        $DATABASE_PASS = 'I86Q1GIrP8DW0vLL';
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
        define( 'DB_USER', 'svar' ); // set database user
        define( 'DB_PASS', 'I86Q1GIrP8DW0vLL' ); // set database password
        define( 'DB_NAME', 'virusnet' ); // set database name
        define( 'DISPLAY_DEBUG', false ); //display db errors?


        $content = htmlentities($_POST['content']);
    
        $stmt = $con->prepare("INSERT INTO svar (idchatlog, content, timestamp, avsenderID, fagid, brukertype) VALUES (?,?,?,?,?,?)");
        $con->set_charset("utf8");

        $stmt->bind_Param("isssis", $idchatlog, $content, $date, $avsenderID, $valgtFag, $brukertype);

        if($stmt->execute()) {
            echo "Melding sendt";
        }

        $stmt->close();
        $con->close();

        //header("Location: ../svar.php?inputPin=".$inputPin."&chatid=".$idchatlog.);
        header("Refresh:1; url=../svar.php");

    }


?>