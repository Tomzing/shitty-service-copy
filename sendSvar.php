<?php 
    session_start();

    include("phpscripts/mysqliDB.php");
    //Hvis fag pin ikke er satt, send personen tilbake
    //if (!isset($_SESSION['gittPin'])) {
    //    header('Location: gjestInputFagPin.php');
    //    exit();
    //}

    if(isset($_GET['idfag'])) {
        $valgtFag = $_GET['idfag'];
    }

    $valgtFag = $_SESSION['valgtFag'];

    //$_SESSION['valgtFag'] = $valgtFag;

    $chatid = $_GET['chatid'];
    $_SESSION['chatid'] = $chatid;
    $brukertype = $_SESSION['typebruker'];

    if(!isset($_SESSION["brukernavn"])) {
        $brukernavn = "Gjest";
    }
    else {
        $brukernavn = $_SESSION["brukernavn"];
    }

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    header("Access-Control-Allow-Origin: *");
    //error_reporting();

    $sqlFinnFag = "SELECT * FROM fag WHERE idfag = '$valgtFag'";
    $resultsFinnFag = $db->selectSQL($sqlFinnFag);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<style>
    #wrapper{
        flex-direction: column;
    }
</style>
<body>

    <div id="wrapper" class="inboks">
        <form method="post" action="phpscripts/sendSvarScript.php">
            <span id="#txtA">Poster som: <p id="brukernavn"></p></span>
            <span id="#txtA">Mottaker<p id="mottaker"></p></span>
            <span id="#txtA">Melding:</span><input style="height:200px;font-size:14pt;" type=text name="content"></input>
            <input type="checkbox" id="anonym" name="sendAnonymt" value="sendAnonymt">Sende anonymt?</input>
            <button id="confirm">Bekreft</button> 
            <div id="tilbakeKnapp">
                <a id="reg" href="index.php">Tilbake</a>
            </div>
        </form>
    </div>

    <script>

        var jsonFag = <?php echo json_encode($resultsFinnFag); ?>;

        document.getElementById("mottaker").innerHTML = jsonFag[0]['fag_navn'];

        document.getElementById("tilbakeKnapp").innerHTML = '<a href="kommentarer.php?inputPin='+jsonFag[0]["idfag"]+'">Tilbake</a>';

        var brukernavn = "<?php echo $brukernavn ?>";
        var brukertype = "<?php echo $brukertype ?>";

        if(brukertype === "foreleser") {
            document.getElementById("anonym").disabled = true;
            document.getElementById("brukernavn").innerHTML = brukernavn;
        }
        else if(brukernavn != "Gjest") {
            document.getElementById("brukernavn").innerHTML = brukernavn;
        }
        else {
            document.getElementById("brukernavn").innerHTML = "Gjest";
            document.getElementById("anonym").disabled = true;
        }
    </script>

</body>
</html>