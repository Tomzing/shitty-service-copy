<?php 
    session_start();

    include("mysqliDB.php");
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
    $brukertype = $_SESSION['typebruker'];

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    header("Access-Control-Allow-Origin: *");
    error_reporting();

    $sqlFinnFag = "SELECT * FROM fag WHERE idfag = '$valgtFag'";
    $resultsFinnFag = $db->selectSQL($sqlFinnFag);

    //Hvis brukernavn ikke er satt så er man en gjest
    if(!isset($_SESSION["brukernavn"])) {
        $date = date("Y-m-d H:i:s");

        $avsenderID = 0;

        $content = htmlentities($_POST['content']);
    
        $stmt = $con->prepare("INSERT INTO chatlog (content, fagid, timestamp, avsenderID, brukertype) VALUES (?,?,?,?,?)");
        $con->set_charset("utf8");

        $stmt->bind_Param("sisss", $content,$valgtFag,$date,$avsenderID,$brukertype);

        if($stmt->execute()) {
            echo "Melding sendt";
        }

        $stmt->close();
        $con->close();

        header("Refresh:1; url=../kommentarer.php");
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
            $brukertype = "student";
            $avsenderID = $_SESSION["brukernavn"];
        }


        $content = htmlentities($_POST['content']);

        
            
        $stmt = $con->prepare("INSERT INTO chatlog (content, fagid, timestamp, avsenderID, brukertype) VALUES (?,?,?,?,?)");
        $con->set_charset("utf8");

        $stmt->bind_Param("sisss", $content,$valgtFag,$date,$avsenderID,$brukertype);

        if($stmt->execute()) {
            echo "Melding sendt";
        }

        $stmt->close();
        $con->close();

        header("Refresh:1; url=../kommentarer.php");
    }


?>