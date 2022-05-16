<?php
    session_start();

    if(isset($_SESSION['valgtFag'])) {
        $gittPin  = $_SESSION['valgtFag'];
    }

    if(!isset($_SESSION['typebruker'])) {
        $_SESSION['typebruker'] = "gjest";
    }

    $typebruker = $_SESSION['typebruker'];

    /*if(isset($_GET['idfag'])) {
        $gittPin = $_GET['idfag'];
    }*/

    if(isset($_GET['chatid'])) {
        $idchatlog = $_GET['chatid'];
    }
    else {
        $idchatlog = $_SESSION['chatid'];
    }

    $_SESSION['idchatlog'] = $idchatlog;

    //echo $idchatlog;
    //echo $_GET['idfag'];
    //echo $gittPin;
    
    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    include("phpscripts/mysqliDB.php");
    header("Access-Control-Allow-Origin: *");
    //error_reporting();

    //echo $valgtKommentar;
        
    $sqlFinnFag = "SELECT * FROM fag WHERE idfag = '$gittPin'";
    $resultsFinnFag = $db->selectSQL($sqlFinnFag);

        //Hvis bruker av økten er enten en foreleser eller gjest så skal alle studenter være anonyme
    if($_SESSION["typebruker"] === "gjest" or $_SESSION["typebruker"] === "foreleser") {
        $sqlFinnKommentarer = "SELECT idchatlog, content, fagid, timestamp, brukertype FROM chatlog WHERE fagid = '$gittPin'";
        $resultsFinnKommentarer = $db->selectSQL($sqlFinnKommentarer);

        $sqlFinnSvar = "SELECT idsvar, idchatlog, content, timestamp, fagid, brukertype FROM svar WHERE idchatlog = '$idchatlog'";
        $resultsFinnSvar = $db->selectSQL($sqlFinnSvar);
        
        $sqlFinnValgtKommentar = "SELECT idchatlog, content, fagid, timestamp, brukertype FROM chatlog WHERE idchatlog = '$idchatlog'";
        $resultsFinnValgtKommentar = $db->selectSQL($sqlFinnValgtKommentar);

        $sqlFinnBruker = "SELECT id FROM student";
        $resultsFinnBruker = $db->selectSQL($sqlFinnBruker);    
    
    }
        //Hvis bruker av økten er enten en admin eller student så skal man kunne se hvem som har sendt hva, hvis mulig
    else {
        $sqlFinnKommentarer = "SELECT * FROM chatlog WHERE fagid = '$gittPin'";
        $resultsFinnKommentarer = $db->selectSQL($sqlFinnKommentarer);

        $sqlFinnSvar = "SELECT * FROM svar WHERE idchatlog = '$idchatlog'";
        $resultsFinnSvar = $db->selectSQL($sqlFinnSvar);
        
        $sqlFinnValgtKommentar = "SELECT * FROM chatlog WHERE idchatlog = '$idchatlog'";
        $resultsFinnValgtKommentar = $db->selectSQL($sqlFinnValgtKommentar);
        
        $sqlFinnBruker = "SELECT id, brukernavn, epost, studieretning, kull FROM student";
        $resultsFinnBruker = $db->selectSQL($sqlFinnBruker);    
    }
    
    $sqlFinnForeleser = "SELECT id, brukernavn, epost, bildestring FROM foreleser";
    $resultsFinnForeleser = $db->selectSQL($sqlFinnForeleser);
?>
<script>
    var json = <?php echo json_encode($resultsFinnFag); ?>;
    console.log(json)
    var jsonKommentarer = <?php echo json_encode($resultsFinnKommentarer); ?>;
    var jsonValgtKommentar = <?php echo json_encode($resultsFinnValgtKommentar); ?>;
    var jsonFinnBruker = <?php echo json_encode($resultsFinnBruker); ?>;
    var jsonFinnForeleser = <?php echo json_encode($resultsFinnForeleser); ?>;
    console.log(jsonFinnForeleser);

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
        <h2><span id="valgtFag">Svarer til emne: </span></h2>
        <p id="emnekode"></p>
        <p id="foreleser"></p>
        <p id="beskrivelse"></p>
        <h2>Svarer til:</h2>
        <div class="inboks" id="valgtKommentar"></div>

        <div id="sendSvar">
            <form method="get" action="sendSvar.php">
                <!--<a style="margin-left:20%;" href="sendSvar.php?idfag=">Send svar til valgt kommentar her</a>-->
            </form>
        </div>
    </div>

    <div id="wrapper">
        <div id="tilbakeKnapp">
            <a href="kommentarer.php" id="tilbakeKnapp">Tilbake</a>
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
        var chatid = <?php echo $idchatlog?>;
        var teller;

        var jsonFag = <?php echo json_encode($resultsFinnFag); ?>;
        var jsonFagObject = jsonFag;

        document.getElementById("valgtFag").innerHTML += jsonFagObject[0]["fag_navn"];
        document.getElementById("emnekode").innerHTML = 'Emnekode: ' + jsonFagObject[0]["emnekode"];
        document.getElementById("foreleser").innerHTML = 'Foreleser: ' + jsonFagObject[0]["foreleser"];
        document.getElementById("beskrivelse").innerHTML = 'Beskrivelse: ' + jsonFagObject[0]["beskrivelse"];
        document.getElementById("sendSvar").innerHTML = '<a style="margin-left:20%;" href="sendSvar.php?idfag='+jsonFagObject[0]["idfag"]+'&chatid='+chatid+'">Send svar til valgt kommentar her</a>';


        var jsonKommentarer = <?php echo json_encode($resultsFinnKommentarer); ?>;
        var jsonFinnSvar = <?php echo json_encode($resultsFinnSvar); ?>;
        var jsonFinnBruker = <?php echo json_encode($resultsFinnBruker); ?>;
        var jsonFinnForeleser = <?php echo json_encode($resultsFinnForeleser); ?>;

        var aktivtNavn = "Anonymous";
        var typebruker = "<?php echo $typebruker ?>";

        var bilde = '<img src="http://icons.iconarchive.com/icons/visualpharm/must-have/256/User-icon.png">';

        document.getElementById("tilbakeKnapp").innerHTML = '<a href="kommentarer.php?inputPin='+jsonFag[0]["idfag"]+'">Tilbake</a>';

        //Skriver valgt kommentar til øverste firkant
        function skrivValgtKommentar() {
            var teller = 0;
            if(typebruker === "student" || typebruker === "admin") {
                    console.log("Ikke pålogget som gjest/foreleser");
        
                    for(var j = 0; j < jsonFinnForeleser.length; j++) {
                        //Iterering gjennom foreleser databasen
                        if(jsonFinnForeleser[j]["brukernavn"] === jsonValgtKommentar[0]["brukertype"]) {
                            console.log("Kommentar av foreleser");
                            var aktivtNavn = jsonFinnForeleser[j]["brukernavn"];
                            let bilde = '<img src="' + jsonFinnForeleser[j]["bildestring"] + '">';

                            if(teller === 0) {
                                document.getElementById("valgtKommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonValgtKommentar[0]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonValgtKommentar[0]["content"] + '</p>'  + '</div></div>';
                                teller++;
                            }
                        }
                    }
                    for(var j = 0; j < jsonFinnBruker.length; j++) {
                                    
                        //Iterering gjennom student databasen
                        if(jsonFinnBruker[j]["brukernavn"] === jsonValgtKommentar[0]["avsenderID"]) {
                            var aktivtNavn = jsonFinnBruker[j]["brukernavn"];

                            //Fylle inn valgt kommentar til toppen av siden
                            if(teller == 0) {
                                document.getElementById("valgtKommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonValgtKommentar[0]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonValgtKommentar[0]["content"] + '</p>'  + '</div></div>';
                                teller++;
                            }
                        }
                    }
                    
                if(jsonValgtKommentar[0]["brukertype"] === "gjest") {
                    var aktivtNavn = "Gjest";

                    if(teller === 0) {
                        document.getElementById("valgtKommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonValgtKommentar[0]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonValgtKommentar[0]["content"] + '</p>'  + '</div></div>';
                        teller++;
                    }
                }
            }
        else {
            if(typebruker === "gjest" || typebruker === "foreleser") {
                console.log("Logget på som gjest/foreleser");
                var teller = 0;
            for(var i = 0; i < jsonValgtKommentar.length; i++) {
                if(jsonValgtKommentar[0]["brukertype"] != "gjest") {
                    for(var j = 0; j < jsonFinnForeleser.length; j++) {

                        //Iterering gjennom foreleser databasen
                        if(jsonFinnForeleser[j]["brukernavn"] === jsonValgtKommentar[0]["brukertype"]) {
                            var aktivtNavn = jsonFinnForeleser[j]["brukernavn"];
                            let bilde = '<img src="' + jsonFinnForeleser[j]["bildestring"] + '">';

                            if(teller === 0) {
                                document.getElementById("valgtKommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonValgtKommentar[0]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonValgtKommentar[0]["content"] + '</p>'  + '</div></div>';
                                teller++;
                            }
                        }
                    }
                    //Super hacky måte å holde styr på om en student kommentar allerede har blitt skrevet.
                    //Må ha den her ettersom jeg ikke kan sjekke avsenderid-en uten at det blir en åpen json
                    var skrevet = 0;
                        //Iterering gjennom student databasen
                        if(jsonValgtKommentar[0]["brukertype"] === "student" && skrevet === 0) {
                            var aktivtNavn = "Student";
                
                            if(teller === 0) {
                                document.getElementById("valgtKommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonValgtKommentar[0]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonValgtKommentar[0]["content"] + '</p>'  + '</div></div>';
                                skrevet = 1;
                                teller++;
                            }
                        }
                    
                }
                else {
                    var aktivtNavn = "Gjest";

                    if(teller === 0) {
                        document.getElementById("valgtKommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonValgtKommentar[0]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonValgtKommentar[0]["content"] + '</p>'  + '</div></div>';
                        teller++;
                    }
                }
            }
        }
        }
        }


        skrivValgtKommentar();

        if(typebruker === "student" || typebruker === "admin") {
            //Brukere er anonyme hvis avsenderID-en er 0
            for(var i = 0; i < jsonFinnSvar.length; i++) {

                if(jsonFinnSvar[i]["brukertype"] != "gjest") {
                    for(var j = 0; j < jsonFinnForeleser.length; j++) {

                        //Iterering gjennom foreleser databasen
                        if(jsonFinnForeleser[j]["brukernavn"] === jsonFinnSvar[i]["avsenderID"]) {
                            var aktivtNavn = jsonFinnForeleser[j]["brukernavn"];
                            let bilde = '<img src="' + jsonFinnForeleser[j]["bildestring"] + '">';

                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonFinnSvar[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonFinnSvar[i]["content"] + '</p>'  + '<form method="GET" action="rapporter.php?chatid=' + jsonFinnSvar[i]["idsvar"] + '" value="' + jsonFinnSvar[i]["idsvar"] +'">' + '<a href="rapporter.php'+ '?chatid='+jsonFinnSvar[i]["idsvar"]+'&isSvar=y" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '</div></div>';
                        }
                    }

                    for(var j = 0; j < jsonFinnBruker.length; j++) {
                        
                        //Iterering gjennom student databasen
                        if(jsonFinnBruker[j]["brukernavn"] === jsonFinnSvar[i]["avsenderID"]) {
                            var aktivtNavn = jsonFinnBruker[j]["brukernavn"];
                
                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonFinnSvar[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonFinnSvar[i]["content"] + '</p>'  + '<form method="GET" action="rapporter.php?chatid=' + jsonFinnSvar[i]["idsvar"] + '" value="' + jsonFinnSvar[i]["idsvar"] +'">' + '<a href="rapporter.php'+ '?chatid='+jsonFinnSvar[i]["idsvar"]+'&isSvar=y" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '</div></div>';
                        }
                    }
                }
                else {
                    var aktivtNavn = "Gjest";

                    document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonFinnSvar[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonFinnSvar[i]["content"] + '</p>'  + '<form method="GET" action="rapporter.php?chatid=' + jsonFinnSvar[i]["idsvar"] + '" value="' + jsonFinnSvar[i]["idsvar"] +'">' + '<a href="rapporter.php'+ '?chatid='+jsonFinnSvar[i]["idsvar"]+'&isSvar=y" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '</div></div>';
                }
            }
        }
        else {
            if(typebruker === "gjest" || typebruker === "foreleser") {
            //Brukere er anonyme hvis avsenderID-en er 0
            for(var i = 0; i < jsonFinnSvar.length; i++) {

                if(jsonFinnSvar[i]["brukertype"] != "gjest") {
                    for(var j = 0; j < jsonFinnForeleser.length; j++) {

                        //Iterering gjennom foreleser databasen
                        if(jsonFinnForeleser[j]["brukernavn"] === jsonFinnSvar[i]["brukertype"]) {
                            var aktivtNavn = jsonFinnForeleser[j]["brukernavn"];
                            let bilde = '<img src="' + jsonFinnForeleser[j]["bildestring"] + '">';

                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonFinnSvar[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonFinnSvar[i]["content"] + '</p>'  + '<form method="GET" action="rapporter.php?chatid=' + jsonFinnSvar[i]["idsvar"] + '" value="' + jsonFinnSvar[i]["idsvar"] +'">' + '<a href="rapporter.php'+ '?chatid='+jsonFinnSvar[i]["idsvar"]+'&isSvar=y" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '</div></div>';
                        }
                    }
                    //Super hacky måte å holde styr på om en student kommentar allerede har blitt skrevet.
                    //Må ha den her ettersom jeg ikke kan sjekke avsenderid-en uten at det blir en åpen json
                    var skrevet = 0;
                    for(var j = 0; j < jsonFinnBruker.length; j++) {
                        
                        //Iterering gjennom student databasen
                        if(jsonFinnSvar[i]["brukertype"] === "student" && skrevet === 0) {
                            var aktivtNavn = "Student";
                
                            document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonFinnSvar[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonFinnSvar[i]["content"] + '</p>'  + '<form method="GET" action="rapporter.php?chatid=' + jsonFinnSvar[i]["idsvar"] + '" value="' + jsonFinnSvar[i]["idsvar"] +'">' + '<a href="rapporter.php'+ '?chatid='+jsonFinnSvar[i]["idsvar"]+'&isSvar=y" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '</div></div>';
                            skrevet = 1;
                        }
                    }
                }
                else {
                    var aktivtNavn = "Gjest";

                    document.getElementById("kommentar").innerHTML += '<div class="melding">' + bilde + '<div> <p id="navnAvsender"><b>Avsender: '+ aktivtNavn + '</b></p>' + '<p id="tidSendt">Sendt: '+jsonFinnSvar[i]["timestamp"] + '</p>' + '<p id="mottaker">'+jsonFinnSvar[i]["content"] + '</p>'  + '<form method="GET" action="rapporter.php?chatid=' + jsonFinnSvar[i]["idsvar"] + '" value="' + jsonFinnSvar[i]["idsvar"] +'">' + '<a href="rapporter.php'+ '?chatid='+jsonFinnSvar[i]["idsvar"]+'&isSvar=y" name="idchatlog" type="submit" value="submit" class="action">Rapporter</a></form>' + '</div></div>';
                }
            }
        }
        }        
    </script>
</body>
</html>