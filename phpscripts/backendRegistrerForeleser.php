<?php
include("connectionTable.php");

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'foreleser';
$DATABASE_PASS = 'ITyu8uXEVmXxA3iX';
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
define( 'DB_USER', 'foreleser' ); // set database user
define( 'DB_PASS', 'ITyu8uXEVmXxA3iX' ); // set database password
define( 'DB_NAME', 'virusnet' ); // set database name
define( 'DISPLAY_DEBUG', false ); //display db errors?

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
    echo "Du har oppgitt ugydlige tegn for brukernavn. Her er kun tall og bokstaver tillat";
    die();
}

$nameUse = $_POST["navn"];


if($_POST['brukernavn'] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_POST['brukernavn'] )){
    errormsg();
}

if (!filter_var($_POST["epost"], FILTER_VALIDATE_EMAIL)) {
    echo "Eposten inneholde ugyldige tegn, eller er ikke skrevet korrekt";
    die();
}

$brukernavn = $nameUse;
$epost = $_POST["epost"];
$password = $_POST["passord"];
$password = password_hash("etSaltSomIkkeKrenkerNoen".$password, PASSWORD_BCRYPT);


$currentDir = getcwd();
    $uploadDirectory = "/foreleser/bilde/";

    $errors = []; // Store all foreseen and unforseen errors here

    $fileExtensions = ['jpeg','jpg','png']; // Whitelist of allowed file extensions

    $fileName = $_FILES['myfile']['name'];
    $fileSize = $_FILES['myfile']['size'];
    $fileTmpName  = $_FILES['myfile']['tmp_name'];
    $fileType = $_FILES['myfile']['type'];
    $imageinfo = getimagesize($_FILES['myfile']['tmp_name']);
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $renamed = md5($fileName. time());
    $uploadPath = $currentDir . $uploadDirectory . $renamed . $fileExtension;

    $bildestring = "./foreleser/bilde/" . $renamed.$fileExtension;

    if (isset($_POST['submit'])) {

        if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && isset($imageinfo))
        {
            $errors[] = 'Sorry, we only accept GIF and JPEG images\n';
        }

        if (! in_array($fileExtension,$fileExtensions)) {
            $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

        if ($fileSize > 2000000) {
            $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }
        if ($fileSize < 0) {
            $errors[] = "This file is smaller than allowed.";
        }
        
        if (empty($errors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            if ($didUpload) {
                echo "The file has been uploaded";
            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
            }
        }
    }


    if (isset($_POST["submit"])) {
        mysqli_close($con);
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'student';
        $DATABASE_PASS = 'IA1vz6TNpdya6X8G';
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
        define( 'DB_USER', 'student' ); // set database user
        define( 'DB_PASS', 'IA1vz6TNpdya6X8G' ); // set database password
        define( 'DB_NAME', 'virusnet' ); // set database name
        define( 'DISPLAY_DEBUG', false ); //display db errors?
        $stmtS = $con->prepare('SELECT id, brukernavn FROM student WHERE brukernavn = ?');
        $stmtS->bind_param('s', $brukernavn);
        $stmtS->execute();
        $stmtS->store_result();
        //$stmtS->fetch();
        mysqli_close($con);

        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'foreleser';
        $DATABASE_PASS = 'ITyu8uXEVmXxA3iX';
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
        define( 'DB_USER', 'foreleser' ); // set database user
        define( 'DB_PASS', 'ITyu8uXEVmXxA3iX' ); // set database password
        define( 'DB_NAME', 'virusnet' ); // set database name
        define( 'DISPLAY_DEBUG', false ); //display db errors?
        $stmtF = $con->prepare('SELECT id, brukernavn FROM foreleser WHERE brukernavn = ?');
        $stmtF->bind_param('s', $brukernavn);
        $stmtF->execute();
        $stmtF->store_result();
        //$stmtF->fetch();

        if ($stmtS->num_rows === 0 && $stmtF->num_rows === 0) {
            echo $brukernavn. " " .$epost. " " .$password. " " .$bildestring;

            $entry = array(
                'brukernavn' => $brukernavn,
                'epost' => $epost,
                'passord' => $password,
                'bildestring' => $bildestring
            );
        
            $database = new DB();
            $add_query = $database->insert('foreleser', $entry);
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
