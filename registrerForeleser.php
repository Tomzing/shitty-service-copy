
<!DOCTYPE html>
<html>
<head>
    <meta charsert="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

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

<div id="wrapper">

    <h2 id="title">Registrer deg (bare for forelesere)</h2>

    <div id="login">
    <form action="phpscripts/backendRegistrerForeleser.php" method="POST"  enctype="multipart/form-data">
        <span id="#txtA">Brukernavn:</span><input id="inputA" type="text" name="navn" required></input>
        <span id="#txtB">Passord:</span><input  id="inputB" type="password" name="passord" required></input>
        <span id="#txtB">Epost</span><input  id="inputB" type="email" name="epost" required></input>
        <span>Et bilde av deg takk</span>
        <input type="file" name="file" id="fileToUpload">
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <button id="confirm" name="submit" type="submit">Bekreft</button> <a id="reg" href="hvemErDu.php">Tilbake</a>
    </form>
        

    
    </div>

    <div id="regBox"></div>

    <p id="feedback"> </p>

</div>

</body>
</html>
