<?php require 'phpscripts/sesSjekkOgLogg.php';?>

<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

<div id="wrapper">

    <h2 id="title">Bytt passord</h2>

    <form id="login" action="phpscripts/byttPassordScript.php" method="post">
        <span id="#txtB">Gammelt passord</span><input  id="inputB" type="password" name="gammeltPassord"></input>
        <span id="#txtB">Nytt passord</span><input  id="inputB" type="password" name="nyttPassord"></input>
        <span id="#txtB">Jeg bekrefter at jeg passet på å skrive riktig passord</span><input type="checkbox">
        <button id="confirm" type="submit">Bekreft bytte av passord</button> <a id="reg" href="innloggetMeny.php">Tilbake</a>
    </form>

    <div id="regBox"></div>

    <p id="feedback"> </p>

</div>

</body>
</html>