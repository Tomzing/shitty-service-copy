
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
        <span id="#txtB">Passord:</span><input  id="pass" type="password" name="passord" required></input>
        <span id="#txtB">Epost</span><input  id="inputB" type="email" name="epost" required></input>
        <span>Et bilde av deg takk</span>
        <input type="file" name="myfile" id="fileToUpload">
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <button id="confirm" name="submit" type="submit">Bekreft</button> <a id="reg" href="hvemErDu.php">Tilbake</a>
    </form>
        

    
    </div>

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
