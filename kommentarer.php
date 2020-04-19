<?php
include("phpscripts/mysqliDB.php");
header("Access-Control-Allow-Origin: *");

    //Hvis fag pin ikke er satt, send personen tilbake
   /* if (!isset($_SESSION['gittPin'])) {
        header('Location: gjestInputFagPin.php');
        exit();
    }*/

    //I tilfelle man er på denne siden uten session, gi de gjest rettigheter
    if(!isset($_SESSION['typebruker'])) {
        $_SESSION['typebruker'] = "gjest";
    }


    $gittPin = $_SESSION['gittPinn'];

    $_SESSION['valgtFag'] = $gittPin;

    $typebruker = $_SESSION['typebruker'];

    $bruker = 0;

    if(isset($_GET['inputPin'])) {
        $_SESSION['valgtFag'] = $_GET['inputPin'];

        $gittPin = $_SESSION['valgtFag'];

        if(isset($_SESSION['loggedin'])) {
            $bruker = true;
        }
    }

    echo $gittPin;

    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    */

    //error_reporting();
        
    //$sqlFinnFag = "SELECT * FROM fag WHERE idfag = '$gittPin'";
    //$resultsFinnFag = $db->selectSQL($sqlFinnFag);
$resultsFinnFag = [];
$stmtA = $con->prepare('SELECT idfag,fag_navn,emnekode,foreleser,beskrivelse FROM fag WHERE idfag =  ?');
$stmtA->bind_param('i', $gittPin);
$stmtA->execute();
$stmtA->store_result();
$stmtA->bind_result($idfag,$fag_navn,$emnekode,$foreleser,$beskrivelse);
$resultsFinnFag = array();
while($stmtA->fetch()){
    $resultsFinnFag[] = array("idfag"=>$idfag,"fag_navn"=>fag_navn,"emnekode"=>$emnekode,"foreleser"=>$foreleser,"beskrivelse"=>$beskrivelse);
}
mysqli_stmt_close($stmtA);









    //Hvis bruker av økten er enten en foreleser eller gjest så skal alle studenter være anonyme aaaa
    if($_SESSION["typebruker"] === "gjest" or $_SESSION["typebruker"] === "foreleser") {
        $resultsFinnKommentarer = $con->prepare('SELECT idchatlog, content, fagid, timestamp, brukertype FROM chatlog WHERE fagid = ?');
        $resultsFinnKommentarer->bind_param('i', $gittPin);
        $resultsFinnKommentarer->execute();
        $resultsFinnKommentarer->store_result();


        $sqlFinnBruker = "SELECT id FROM student";
        $resultsFinnBruker = $db->selectSQL($sqlFinnBruker);    
    }
    //Hvis bruker av økten er enten en admin eller student så skal man kunne se hvem som har sendt hva, hvis mulig
    else {

        $stmtS = $con->prepare('SELECT * FROM chatlog WHERE fagid = ?');
        $stmtS->bind_param('i', $gittPin);
        $stmtS->execute();
        $stmtS->store_result();
        $sqlFinnKommentarer = $stmtS;

        $sqlFinnBruker = "SELECT id, brukernavn, epost, studieretning, kull FROM student";
        $resultsFinnBruker = $db->selectSQL($sqlFinnBruker);
    }

    $sqlFinnSvar = "SELECT * FROM svar";
    $resultsFinnSvar = $db->selectSQL($sqlFinnSvar);

    $sqlFinnForeleser = "SELECT id, brukernavn, epost, bildestring FROM foreleser";
    $resultsFinnForeleser = $db->selectSQL($sqlFinnForeleser);
?>
<script>
    var json = <?php echo json_encode(array("resultsFinnFag"=>$resultsFinnFag), JSON_UNESCAPED_UNICODE); ?>;
    var jsonKommentarer = <?php echo json_encode($resultsFinnKommentarer); ?>;
    var jsonFinnBruker = <?php echo json_encode($resultsFinnBruker); ?>;
    var jsonFinnForeleser = <?php echo json_encode($resultsFinnForeleser); ?>;

</script>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

    <div id="wrapper" >
        <h2><span id="valgtFag">Tittel på fag her</span></h2>
        <p id="emnekode"></p>
        <p id="foreleser"></p>
        <p id="beskrivelse"></p>

        <form method="get" action="phpscripts/sendValgtFagVidereScript.php">
            <a id="linkTilMelding"style="margin-left:20%;" href="sendMelding.php">Send ny melding til foreleser i <span id="valgtFag1">Valgt Fag Her</span></a>
        </form>
    </div>

    <div id="wrapper">
        <div id="tilbakeKnapp">
            <a href="gjestInputFagPin.php" id="tilbakeKnapp">Tilbake</a>
        </div>
    </div>

    <div id="wrapper" class="inboks">
        <div id="kommentar">
            <!--<div class="melding">
                <img src="http://icons.iconarchive.com/icons/visualpharm/must-have/256/User-icon.png">
                <div>
                    <p id="navnAvsender"><b>Student</b><p>
                    <p id="tidSendt">Sendt: </p>
                    <p id="mottaker">Kommentarer på faget her</p>
                    <div>
                        <a href="kommenter.php" class="action">Kommenter</a>
                            <a href="rapporter.php" class="action">Rapporter</a>
                        <a href="kommentarer.php" class="action">Kommentarer <span>(0)</span></a>
                    </div>
                </div>
            </div>-->
        </div>
    </div>

    <script>

        var jsonFag = <?php echo json_encode($resultsFinnFag); ?>;
        var jsonFagObject = jsonFag;

        //Sjekker om array returnert av den gitte pin koden er korrekt
        if(jsonFag.length == 0) {
            document.getElementById("valgtFag").innerHTML = "UGYLDIG PIN KODE";
            document.getElementById("linkTilMelding").innerHTML = "";
        }
        else {
            var bruker = <?php echo $bruker ?>;

            console.log(bruker);
            if(bruker) {
                document.getElementById("tilbakeKnapp").innerHTML = '<a href="fagOversikt.php" id="tilbakeKnapp">Tilbake</a>';
            }

            var jsonFag = <?php echo json_encode($resultsFinnFag); ?>;
            var jsonFagObject = jsonFag;

            document.getElementById("valgtFag").innerHTML = jsonFagObject[0]["fag_navn"];
            document.getElementById("emnekode").innerHTML = 'Emnekode: ' + jsonFagObject[0]["emnekode"];
            document.getElementById("foreleser").innerHTML = 'Foreleser: ' + jsonFagObject[0]["foreleser"];
            document.getElementById("beskrivelse").innerHTML = 'Beskrivelse: ' + jsonFagObject[0]["beskrivelse"];
            document.getElementById("valgtFag1").innerHTML = jsonFagObject[0]["fag_navn"];

            var jsonKommentarer = <?php echo json_encode($resultsFinnKommentarer); ?>;
            var jsonFinnSvar = <?php echo json_encode($resultsFinnSvar); ?>;
            var jsonFinnBruker = <?php echo json_encode($resultsFinnBruker); ?>;
            var jsonFinnForeleser = <?php echo json_encode($resultsFinnForeleser); ?>;

            var aktivtNavn = "Anonymous";
            var idfag = jsonFag[0]["idfag"];

            var typebruker = "<?php echo $typebruker ?>";

            var bilde = '<img src="http://icons.iconarchive.com/icons/visualpharm/must-have/256/User-icon.png">';

        //Hvis brukertypen er student eller admin så skal navn på avsenderne være vist i grensesnitt
        if(typebruker === "student" || typebruker === "admin") {
            //Brukere er anonyme hvis avsenderID-en er 0
            for(var i = 0; i < jsonKommentarer.length; i++) {
                var antallSvar = 0;
                var aktivKommentar = jsonKommentarer[i]["idchatlog"];
                for(var k = 0; k < jsonFinnSvar.length; k++) {
                    if(jsonKommentarer[i]["idchatlog"] === jsonFinnSvar[k]["idchatlog"]) {
                        antallSvar++;
                    }
                }
                if(jsonKommentarer[i]["avsenderID"] != 0) {
                    for(var j = 0; j < jsonFinnForeleser.length; j++) {

                        //Iterering gjennom foreleser databasen
                        if(jsonFinnForeleser[j]["brukernavn"] === jsonKommentarer[i]["avsenderID"]) {
                            var aktivtNavn = jsonFinnForeleser[j]["brukernavn"];
                            let bilde = '<img src="' + jsonFinnForeleser[j]["bildestring"] + '">';

                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n"  name="idchatlog" type="submit" value="submit" class="action">Rapporter </a></form>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                        }
                    }

                    for(var j = 0; j < jsonFinnBruker.length; j++) {
                                    
                        //Iterering gjennom student databasen
                        if(jsonFinnBruker[j]["brukernavn"] === jsonKommentarer[i]["avsenderID"] && jsonKommentarer[i]["brukertype"] === "gjest") {
                            var aktivtNavn = "Student";

                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n" name="idchatlog" type="submit" value="submit" class="action">Rapporter </a></form>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                        }
                        else if(jsonFinnBruker[j]["brukernavn"] === jsonKommentarer[i]["avsenderID"]) {
                            var aktivtNavn = jsonFinnBruker[j]["brukernavn"];
                
                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                        }
                    }
                }
                else {
                    var aktivtNavn = "Gjest";

                    document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                }
            }
        }
        else {
            if(typebruker === "gjest" || typebruker === "foreleser") {
            //Brukere er anonyme hvis avsenderID-en er 0
            for(var i = 0; i < jsonKommentarer.length; i++) {
                var antallSvar = 0;
                var aktivKommentar = jsonKommentarer[i]["idchatlog"];
                for(var k = 0; k < jsonFinnSvar.length; k++) {
                    if(jsonKommentarer[i]["idchatlog"] === jsonFinnSvar[k]["idchatlog"]) {
                        antallSvar++;
                    }
                }
                if(jsonKommentarer[i]["brukertype"] != "gjest") {
                    for(var j = 0; j < jsonFinnForeleser.length; j++) {

                        //Iterering gjennom foreleser databasen
                        if(jsonFinnForeleser[j]["brukernavn"] === jsonKommentarer[i]["brukertype"]) {
                            var aktivtNavn = jsonFinnForeleser[j]["brukernavn"];
                            let bilde = '<img src="' + jsonFinnForeleser[j]["bildestring"] + '">';

                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                        }
                    }
                    //Super hacky måte å holde styr på om en student kommentar allerede har blitt skrevet.
                    //Må ha den her ettersom jeg ikke kan sjekke avsenderid-en uten at det blir en åpen json
                    var skrevet = 0;
                    for(var j = 0; j < jsonFinnBruker.length; j++) {
                        
                        //Iterering gjennom student databasen
                        if(jsonKommentarer[i]["brukertype"] === "student" && skrevet === 0) {
                            var aktivtNavn = "Student";
                
                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                            skrevet = 1;
                        }
                    }
                }
                else {
                    var aktivtNavn = "Gjest";

                    document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonKommentarer[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonKommentarer[i]["content"] + '</p>' + '<form method="GET" action="rapporter.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="rapporter.php'+ '?chatid='+aktivKommentar+'&isSvar=n" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>'  + '</div></div>' + '<form method="GET" action="svar.php?chatid=' + aktivKommentar + '" value="' + aktivKommentar +'">' + '<a href="svar.php'+ '?chatid='+aktivKommentar+'&idfag='+idfag +'" name="idchatlog" type="submit" value="submit" class="action">Svar <span>(' + antallSvar + ')</span></a></form>'  + '</div></div>';
                }
            }
        }
        }
        }
                
    </script>
</body>
</html>