<?php
include("mysqliDB.php");
header("Access-Control-Allow-Origin: *");



$brukernavn = $_POST["navn"];
$epost = $_POST["epost"];
$password = $_POST["passord"];


$currentDir = getcwd();
    $uploadDirectory = "/foreleser/bilde/";

    $errors = []; // Store all foreseen and unforseen errors here

    $fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

    $fileName = $_FILES['myfile']['name'];
    $fileSize = $_FILES['myfile']['size'];
    $fileTmpName  = $_FILES['myfile']['tmp_name'];
    $fileType = $_FILES['myfile']['type'];

    $fileExtension = strtolower(end(explode('.',$fileName)));

    $uploadPath = $currentDir . $uploadDirectory . basename($fileName);

    $bildestring = "./foreleser/bilde/" . basename($fileName);

    if (isset($_POST['submit'])) {

        if (! in_array($fileExtension,$fileExtensions)) {
            $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

        if ($fileSize > 2000000) {
            $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }

        if (empty($errors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            if ($didUpload) {
                echo "The file " . basename($fileName) . " has been uploaded";
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
        $stmtS = $con->prepare('SELECT id, brukernavn FROM student WHERE brukernavn = ?');
        $stmtS->bind_param('s', $brukernavn);
        $stmtS->execute();
        $stmtS->store_result();
        //$stmtS->fetch();

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
