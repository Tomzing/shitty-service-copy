<?php 

require 'phpscripts/sesSjekkOgLogg.php';

$brukertype = $_SESSION["typebruker"];

echo $brukertype;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<style>
    #listeInboks a{
        padding: 20px;
    }
</style>

<body>
<div id="wrapper" class="kuk">

    <a href="bytt.php">
        Bytt passord.
    </a>

    <a href="fagOversikt.php">
        Se alle fag.
    </a>
    <a href="phpscripts/loggUtScript.php">
        Logg ut.
    </a>
</div>

<script>

    var brukertype = "<?php echo $brukertype?>";

    console.log(brukertype);

    if(brukertype == "admin") {
        document.getElementById("wrapper").innerHTML += "<a href='admin.php'>Godkjenn brukere</a>";
    }
</script>


</body>
</html>