
<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

<div id="wrapper">

    <h2 id="title">Registrer deg</h2>

    <div id="login">
    <form action="phpscripts/registerBackendStudent.php" method="get" id="login">
        <span id="#txtA">Brukernavn:</span><input id="inputA" type="text" name="brukernavn" required> </input>
        <span id="#txtB">Passord:</span><input  id="inputB" type="password" name="passord" required></input>
        <span id="#txtC">Epost:</span><input  id="inputC" type="text" name="epost" required></input>
        <span id="#txtD">Studierettning:</span><input  id="inputD" type="text" name="studieretning" required></input>
        <span id="#txtE">Kull:</span><input  id="inputE" type="text" name="kull" required></input>
        <span id="#txtB">Jeg bekrefter at jeg passet på å skrive riktig passord</span><input type="checkbox" name="riktigPassord">
        <button id="confirm" type="submit">Bekreft</button> <a id="reg" href="hvemErDu.php">Tilbake</a>
    </form>



    <div id="regBox"></div>

    <p id="feedback"> </p>

</div>

</body>
</html>