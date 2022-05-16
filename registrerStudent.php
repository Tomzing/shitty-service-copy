
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charsert="utf-8">
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
<body>

<div id="wrapper">

    <h2 id="title">Registrer deg</h2>

    <div id="login">
        <form action="phpscripts/registerBackendStudent.php" method="POST" id="login">
            <span id="#txtA">Brukernavn:</span><input id="inputA" type="text" name="brukernavn" required> </input>
            <span id="#txtB">Passord:</span><input  id="pass" type="password" name="passord" required></input>
            <span id="#txtC">Epost:</span><input  id="inputC" type="text" name="epost" required></input>
            <span id="#txtD">Studierettning:</span><input  id="inputD" type="text" name="studieretning" required></input>
            <span id="#txtE">Kull:</span><input  id="inputE" type="text" name="kull" required></input>
            <span id="#txtB">Jeg bekrefter at jeg passet på å skrive riktig passord</span><input type="checkbox" name="riktigPassord">
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <button id="confirm" type="submit">Bekreft</button> <a id="reg" href="hvemErDu.php">Tilbake</a>
        </form>


        <div id="regBox"></div>
        <p lang="NO" id="feedback2">
            You just at least have 1 big letter ( i mean capital letter)
            and 1 number and length of at least 8 characters for password i think.
            we do not accept less secure password maybe baby
        </p>
        <p id="feedback"> </p>
        <script>
            var sikkert = false;
            var code = document.getElementById("pass");
            var display = document.getElementsByClassName("feedback2");
            code.addEventListener("keyup", function() {
                checkpassword(code.value);
            });
            document.getElementById("confirm").onmouseover = beskjed;
            function beskjed(){
                if(!sikkert)
                    alert("you password is not safe enough yet, you will get a message in the top with an alertbox when the password is good and strong enough")
            }




            function checkpassword(password) {
                sikkert = false;
                var strength = 0;
                if (password.match(/[a-z]+/)) {
                    if (password.match(/[A-Z]+/)) {
                        if (password.match(/[0-9]+/)) {
                            if(password.length > 7){
                                alert("password is now safe enough, dont make any changes!!!!")
                                sikkert = true;
                            }

                        }
                    }
                }

            }
        </script>
    </div>

</body>
</html>
