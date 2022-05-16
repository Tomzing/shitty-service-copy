<?php 

require 'phpscripts/sesSjekkOgLogg.php';
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include("phpscripts/mysqliDB.php");
header("Access-Control-Allow-Origin: *");
//error_reporting();

$sqlFinnFag = "SELECT * FROM fag";
$resultsFinnFag = $db->selectSQL($sqlFinnFag);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

    <div id="wrapper" >
        <h2>Velg emne</h2>
        <div id="innhold">
        <!--
            <a href="inboks.php">Datasikkerhet</a>
            <a href="inboks.php">Webutvikling</a>
            <a href="inboks.php">Matte</a>
            <a href="inboks.php">Programmering</a>
            <a href="inboks.php">Svindel</a>
            <a href="inboks.php">Norsk</a>
            <a href="inboks.php">Markedsføring</a>
            <a href="inboks.php">Prokastinering 101</a>
            <a href="inboks.php">Rema 1000</a>
        -->
        </div>
    </div>

    <div id="wrapper">
        <a href="innloggetMeny.php">Tilbake</a>
    </div>

    <script>
        var jsonFinnFag = <?php echo json_encode($resultsFinnFag); ?>;

    
        for(var i = 0; i < jsonFinnFag.length; i++) {
            var linkTilFag = jsonFinnFag[i]["idfag"];
            var navnFag = jsonFinnFag[i]["fag_navn"];
            document.getElementById("innhold").innerHTML += '<form method="get" action="phpscripts/gjestFinnFagScript.php" value="' + linkTilFag +'">' + '<a href="kommentarer.php?inputPin='+linkTilFag+'"name="inputPin" type="submit" value="submit" class="action">'+navnFag+'</a></form>';

            //document.getElementById("innhold").innerHTML += "<a href='" + linkTilFag + "'>" + navnFag + "</a>";
        }
    </script>
    


</body>
</html>