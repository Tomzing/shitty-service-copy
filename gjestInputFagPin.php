<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
    <div id="wrapper" >
        <h2>Skriv pin for faget du vil se på:</h2>
        <form method="get" action="phpscripts/gjestFinnFagScript.php">
            <input type="number" id="inputA" name="inputPin" required>
            <button type="submit">Sjekk pin</button>
        </form>
        <p>Eksempler på noen pin-koder: 1000, 1001, 1002.</p>
    </div>

    <div id="wrapper">
        <a href="index.php">Tilbake</a>
    </div>
</body>
</html>
