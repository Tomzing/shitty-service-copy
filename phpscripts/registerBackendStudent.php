<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    error_reporting();



$uppercase = preg_match('@[A-Z]@', $_POST['passord']);
$lowercase = preg_match('@[a-z]@', $_POST['passord']);
$number    = preg_match('@[0-9]@', $_POST['passord']);
$specialChars = preg_match('@[^\w]@', $_POST['passord']);

if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['passord']) < 8) {
    die("Ditt passord møter ikke kravene for passord");
}else{
    echo 'Strong password.';
}

    include("db.php");

    $epost = $_POST["epost"];
    $passord = $_POST["passord"];
    $brukernavn = $_POST["brukernavn"];
    $kull = $_POST["kull"];


    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'student';
    $DATABASE_PASS = 'IA1vz6TNpdya6X8G';
    $DATABASE_NAME = 'virusnet';
    // Try and connect using the info above.
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    $con->set_charset("utf8");
    if (mysqli_connect_errno() ) {
        // If there is an error with the connection, stop the script and display the error.
        die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    define( 'DB_HOST', 'localhost' ); // set database host
    define( 'DB_USER', 'student' ); // set database user
    define( 'DB_PASS', 'IA1vz6TNpdya6X8G' ); // set database password
    define( 'DB_NAME', 'virusnet' ); // set database name
    define( 'DISPLAY_DEBUG', false ); //display db errors?


    header("Access-Control-Allow-Origin: *");
    error_reporting();

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LdUcegUAAAAADuXCiNmRXaU1rMQD4xdRjdmi7TO';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if ($recaptcha->score <= 0.5) {
        echo 'Du feilet captchaen din dustemikkel';
        die;
    }


    function errormsg(){
        echo "Du har oppgitt ugydlige tegn for brukernavn, studieretning eller kull. Her er kun tall og bokstaver tillat";
        die();
    }

    $nameUse = $_POST['brukernavn'];
    $studieUse = $_POST["studieretning"];
    $kullUse = $_POST["kull"];
    if($_POST['brukernavn'] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_POST['brukernavn'] )){
        errormsg();
    }else{
        if($_POST["studieretning"] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_POST["studieretning"] )){
            errormsg();
        }else{
            if($_POST["kull"] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_POST["kull"] )){
                errormsg();
            }
        }
    }

    if (!filter_var($_POST["epost"], FILTER_VALIDATE_EMAIL)) {
        echo "Eposten inneholde ugyldige tegn, eller er ikke skrevet korrekt";
    }

    $name = preg_replace( "/[^a-zA-Z0-9_]/", "", $nameUse);
    $epost = $_POST["epost"];
    $password = $_POST["passord"];
    $studieretning = preg_replace( "/[^a-zA-Z0-9_]/", "", $studieUse );
    $kull = preg_replace( "/[^a-zA-Z0-9_]/", "", $kullUse );
    $password = password_hash("etSaltSomIkkeKrenkerNoen".$password, PASSWORD_BCRYPT);



if (isset($name)) {
        $stmtS = $con->prepare('SELECT id, brukernavn FROM student WHERE brukernavn = ?');
        $stmtS->bind_param('s', $name);
        $stmtS->execute();
        $stmtS->store_result();
        //$stmtS->fetch();



        if ($stmtS->num_rows === 0 ) {
            $con->close();
            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'foreleser';
            $DATABASE_PASS = 'ITyu8uXEVmXxA3iX';
            $DATABASE_NAME = 'virusnet';
            // Try and connect using the info above.
            $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
            $con->set_charset("utf8");
            if (mysqli_connect_errno() ) {
                // If there is an error with the connection, stop the script and display the error.
                die ('Failed to connect to MySQL: ' . mysqli_connect_error());
            }

            $stmtF = $con->prepare('SELECT id, brukernavn FROM foreleser WHERE brukernavn = ?');
            $stmtF->bind_param('s', $name);
            $stmtF->execute();
            $stmtF->store_result();
            //$stmtF->fetch();

            if($stmtF->num_rows === 0 ){
                echo $name." ".$epost." ".$password." ".$studieretning." ".$kull;

                $entry = array(
                    'brukernavn' => $name,
                    'epost' => $epost,
                    'passord' => $password,
                    'studieretning' => $studieretning,
                    'kull' => $kull
                );

                $database = new DB();
                $add_query = $database->insert( 'student', $entry );
            }

        }
        //Har ikke endret på den opprinnelige spørringen som var laget for å legge ny bruker i database
        else {
            echo "Brukernavnet er allerede i bruk";
        }
    }
    else {
        echo "Fikk ikke kontakt med databasen";
    }


?>