<?php
    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    error_reporting();*/
    include("mysqliDB.php");
    header("Access-Control-Allow-Origin: *");
    error_reporting();


    function errormsg(){
        echo "Du har oppgitt ugydlige tegn for brukernavn, studieretning eller kull. Her er kun tall og bokstaver tillat";
        die();
    }

    $nameUse = $_GET['brukernavn'];
    $studieUse = $_GET["studieretning"];
    $kullUse = $_GET["kull"];
    if($_GET['brukernavn'] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_GET['brukernavn'] )){
        errormsg();
    }else{
        if($_GET["studieretning"] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_GET["studieretning"] )){
            errormsg();
        }else{
            if($_GET["kull"] != preg_replace( "/[^a-zA-Z0-9_]/", "", $_GET["kull"] )){
                errormsg();
            }
        }
    }

    if (!filter_var($_GET["epost"], FILTER_VALIDATE_EMAIL)) {
        echo "Eposten inneholde ugyldige tegn, eller er ikke skrevet korrekt";
    }

    $name = preg_replace( "/[^a-zA-Z0-9_]/", "", $nameUse);
    $epost = $_GET["epost"];
    $password = $_GET["passord"];
    $studieretning = preg_replace( "/[^a-zA-Z0-9_]/", "", $studieUse );
    $kull = preg_replace( "/[^a-zA-Z0-9_]/", "", $kullUse );
    echo $name;
    $password = password_hash("etSaltSomIkkeKrenkerNoen".$password, PASSWORD_BCRYPT);



if (isset($name)) {
        $stmtS = $con->prepare('SELECT id, brukernavn FROM student WHERE brukernavn = ?');
        $stmtS->bind_param('s', $name);
        $stmtS->execute();
        $stmtS->store_result();
        //$stmtS->fetch();

        $stmtF = $con->prepare('SELECT id, brukernavn FROM foreleser WHERE brukernavn = ?');
        $stmtF->bind_param('s', $name);
        $stmtF->execute();
        $stmtF->store_result();
        //$stmtF->fetch();

        if ($stmtS->num_rows === 0 && $stmtF->num_rows === 0) {
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
        //Har ikke endret på den opprinnelige spørringen som var laget for å legge ny bruker i database
        else {
            echo "Brukernavnet er allerede i bruk";
        }
    }
    else {
        echo "Fikk ikke kontakt med databasen";
    }


?>