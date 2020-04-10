<?php include "phptrym/db.php"; ?>

<?php
header("Access-Control-Allow-Origin: *");
$error="";
if(isset($_POST["email"]) && (!empty($_POST["email"]))){
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $error="<p>Invalid email address please type a valid email address!</p>";
    }else{
        $sel_query = "SELECT * FROM user WHERE email='".$email."'";
        $results = $db->selectSQL($sel_query);
        $row = $results;
        if ($row==""){
            $error= "<p>No user is registered with this email address!</p>";
        }
    }
    if($error!=""){
        echo "<div class='error'>".$error."</div><br /><a href='javascript:history.go(-1)'>Go Back</a>";
    }else{
        $expFormat = mktime(
            date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
        );
        $expDate = date("Y-m-d H:i:s",$expFormat);
        $key = md5(2418*2+$email);
        $addKey = substr(md5(uniqid(rand(),1)),3,10);
        $key = $key . $addKey;
        // Insert Temp Table
        $sel_insert_query = "INSERT INTO `passordreset` (`email`, `keypassord`, `expDate`) VALUES ('".$email."', '".$key."', '".$expDate."');";
        $results2 = $db->selectSQL($sel_insert_query);
        $output='<p>Kjære Bruker,</p>';
        $output.='<p>Vennligst klikk på linken for å resette ditt passord.</p>';
        $output.='<p>-------------------------------------------------------------</p>';
        $output.='<p><a href="https://app.vjshoppingguide.com/f7productapi/reset-password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank"> https://158.39.188.209/ResetPassword/reset-password.php?key='.$key.'&email='.$email.'&action=reset</a></p>';
        $output.='<p>-------------------------------------------------------------</p>';
        $output.='<p>Please be sure to copy the entire link into your browser.The link will expire after 1 day for security reason.</p>';
        $output.='<p>If you did not request this forgotten password email, no action is needed, your password will not be reset. However, you may want to log into your account and change your security password as someone may have guessed it.</p>';
        $output.='<p>Thanks,</p>';
        $output.='<p>AllPHPTricks Team</p>';
        $body = $output;
        $subject = "Password Recovery";
        require_once('PHPMailer/PHPMailerAutoload.php');
        $mail = new PHPMailer();

        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host       = "mail.leratechsolutions.com"; // SMTP server
                            // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        //$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        $mail->Host       = "mail.leratechsolutions.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username   = "mattia@leratechsolutions.com";  // GMAIL username
        $mail->Password   = "No996830950!";            // GMAIL password

        $mail->SetFrom('mattia@leratechsolutions.com', 'Gjennoppretting av Passord ShittyServiceAS');

        $mail->AddReplyTo('mattia@leratechsolutions.com', 'Gjennoppretting av Passord ShittyServiceAS');

        $mail->Subject    = "Gjennoppretting av Passord";

        $mail->AltBody    = "For å se Eposten må det benyttes en HTML5 kompatibel Nettleser"; // optional, comment out and test

        $mail->MsgHTML($body);

        $address = $email;
        $mail->AddAddress($address, "First Last");

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "<div class='error'>
<p>En E-post har blitt sendt til din E-post adresse med videre instrukser for resetting av passord.</p>
</div><br /><br /><br />";
        }

        }

}else{
    ?>
    <form method="post" action="" name="reset"><br /><br />
        <label><strong>Skriv din E-post her:</strong></label><br /><br />
        <input type="email" name="email" placeholder="username@email.com" />
        <br /><br />
        <input type="submit" value="Reset Password"/>
    </form>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<?php } ?>