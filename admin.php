<?php
    session_start();

    //Sjekker om bruker er admin, hvis ikke hiv de ut
    if ($_SESSION['typebruker'] != "admin") {
        header('Location: index.php');
        exit();
    }
    //require 'phpscripts/sesSjekkOgLogg.php';

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    include("phpscripts/mysqliDB.php");
    header("Access-Control-Allow-Origin: *");
    //error_reporting();

    $sqlUaktivertStudent = "SELECT id, brukernavn, epost, studieretning, kull FROM student WHERE aktivert = 0";
    $resultatUaktivertStudent = $db->selectSQL($sqlUaktivertStudent);

    $sqlUaktivertForeleser = "SELECT id, brukernavn, epost, bildestring FROM foreleser WHERE aktivert = 0";
    $resultatUaktivertForeleser = $db->selectSQL($sqlUaktivertForeleser);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
<div id="wrapper">
    <h2>Adminpanel</h2>
</div>

<div id="wrapper">
    <h2>Godkjenn medlemskap studenter</h2>
    <div>
        <div id="brukerStudenter">
            
        <!--En representasjon av hvordan studenten vil bli representert i html
            <div class="melding">
             bilde 
                <div> 
                    <p id="navnAvsender"><b>Brukernavn:</b> navn</p>
                    <p id="epost"><b>E-post:</b> epost</p>
                    <p id="studieretning"><b>Studieretning:</b> studieretning</p>
                    <p id="kull"><b>Årskull:</b> kull</p>
                    <form method="POST" action="phpscripts/aktiverBrukerScript.php">
                        <input type="submit" name="aktivIdS" value="id" class="action">Aktiver</input>
                    </form>
                </div>
            </div>-->
        
        </div>
    </div>
</div>
<div id="wrapper">
    <h2>Godkjenn foreleser</h2>
    <div>
        <div id="brukerForeleser">
        </div>
    </div>        
</div>    
    
    <script>
        var bilde = '<img src="http://icons.iconarchive.com/icons/visualpharm/must-have/256/User-icon.png">';
        
        var jsonFinnUaktivertStudent = <?php echo json_encode($resultatUaktivertStudent); ?>; 
        var jsonUaktivSObjekt = jsonFinnUaktivertStudent;

        var jsonUaktivForeleser = <?php echo json_encode($resultatUaktivertForeleser); ?>;
        var jsonUaktivFObjekt = jsonUaktivForeleser;


        for(var i = 0; i < jsonUaktivSObjekt.length; i++){

            var id = jsonUaktivSObjekt[i]["id"];
            var navn = jsonUaktivSObjekt[i]["brukernavn"];
            var epost = jsonUaktivSObjekt[i]["epost"];
            var studieretning = jsonUaktivSObjekt[i]["studieretning"];
            var kull = jsonUaktivSObjekt[i]["kull"];

            document.getElementById("brukerStudenter").innerHTML += '<div class="melding">' + bilde + '<div> <p id="idBruker"><b>Id: </b>' + id + '</p><p id="navnAvsender"><b>Brukernavn:</b> ' + navn + '</p>' + '<p id="epost"><b>E-post:</b> ' + epost + '</p>' + '<p id="studieretning"><b>Studieretning:</b>' + studieretning + '</p>' + '<p id="kull"><b>Årskull:</b> ' + kull + '</p>' + '<form method="POST" action="phpscripts/aktiverBrukerScript.php">' + '<input type="hidden" id="studentId" name="studentId" value="' + id + '"></input>' + '<input type="submit" name="aktivIdS" value="'+ id +'" class="action">Aktiver</input></form>' + '</div></div>';
        }
        

        for(var j = 0; j < jsonUaktivFObjekt.length; j++){
            var id = jsonUaktivFObjekt[j]['id'];
            var navn = jsonUaktivFObjekt[j]['brukernavn'];
            var epost = jsonUaktivFObjekt[j]['epost'];
            let bildeF = '<img src="' + jsonUaktivFObjekt[j]['bildestring'] + '">';

            document.getElementById("brukerForeleser").innerHTML +=  '<div class="melding">' + bildeF + '<div> <p id="idBruker"><b>Id: </b>' + id + '</p> <p id="navnAvsender"><b>Brukernavn: ' + navn + '</b></p>' + '<p id="epost">E-post: ' + epost + '</p>' + '<form method="POST" action="phpscripts/aktiverBrukerScript.php">' + '<input type="hidden" id="foreleserId" name="foreleserId" value="' + id + '"></input>' + '<input type="submit" name="aktivIdF" value="' + id + '" class="action">Aktiver</input></form>' + '</div></div>';
        }
        

    </script>
</body>
</html>