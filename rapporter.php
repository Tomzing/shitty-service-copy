<?php
    session_start();
    $_SESSION['chatId'] = $_GET['chatid'];
    $_SESSION['isSvar'] = $_GET['isSvar'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<style>
    #wrapper{
        flex-direction: column;
    }
</style>
<body>
<div id="wrapper" >
    <h2>Si ifra!</h2>
    <p>Du er i ferd med å rapportere en melding skrevet av <span id="targetReport">Testbruker</span></p>
<div/>


        <div class="melding">
        <img src="http://icons.iconarchive.com/icons/visualpharm/must-have/256/User-icon.png">
        <div>
            <b>Student</b>
            <p id="mottaker">Hei, jeg synes forelesningene går litt sakte i det siste</p>

        </div>
        </div>


<div id="wrapper" class="inboks">
    <form method="post" action="phpscripts/sendRapportScript.php">
        <span id="#txtA">Kontekst</span><textarea style="height:200px;font-size:14pt;" type=text name='content'></textarea>
        <button id="confirm">Rapporter</button> <a id="reg" href="inboks.php">Tilbake</a>

    </form>
</div>

</body>
</html>