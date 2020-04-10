<?php
    if(false)header("Location: innloggetMeny.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<script src="https://www.google.com/recaptcha/api.js?render=6LdUcegUAAAAAEaEyVtUMVUltxOTPsIGLg7GwdLA"></script>
<script>
    window.onload;

    grecaptcha.ready(function() {
        grecaptcha.execute('6LdUcegUAAAAAEaEyVtUMVUltxOTPsIGLg7GwdLA', {action: 'homepage'}).then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
            console.log(recaptchaResponse.value);
        });
    });
</script>

<style>
    button{
        width: 100%;
        height: 100%;
    }
</style>

<body>
<style>div a{margin:0;}</style>
<div id="wrapper">

    <h2 id="title">VisVas</h2>

    <form action="phpscripts/innloggingBackend.php" method="post" id="login">
        <span id="#txtA">Brukernavn:</span><input id="inputA" type=text name="brukernavn" required></input>
        <span id="#txtB">Passord:</span><input  id="inputB" type="password" name="passord" required></input>
        <input type="radio" name="velgStilling" value="student" checked="checked">Student
        <input type="radio" name="velgStilling" value="foreleser">Foreleser
        <input type="radio" name="velgStilling" value="admin">Administrator

        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

        <a href="innloggetMeny.php"><button style="margin-left:0;" type="submit">login</button></a>
    </form>
    <a style="margin-left:0;" href="hvemErDu.php"><button>Registrer deg</button></a>
    <form id="gjestInnlogging" action="phpscripts/gjestInnlogging.php" method="post">
        <a href="innloggetMeny.php"><button style="margin-left:0;" id="reg" type="submit" value="gjest">Gjest?</button></a>
    </form>
    <a id="reg" href="glemtPass.php">Glemt Passord</a> 

    <br>

    <div id="regBox"></div>

    <p id="feedback"> </p>

    <img src="cate.png">

</div>


</body>
</html>