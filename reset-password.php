
<?php include "phptrym/db.php"; ?>

<?php
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"])
    && ($_GET["action"]=="reset") && !isset($_POST["action"])){
    $key = $_GET["key"];
    $email = $_GET["email"];
    $curDate = date("Y-m-d H:i:s");
    $query = "SELECT * FROM `password_reset_temp` WHERE `keypassord`='".$key."' and `email`='".$email."';";
    $results = $db->selectSQL($query);
    foreach($results as $a){
        $row =$a["expDate"];
    }

    if ($row==""){
        $error .= '<h2>Invalid Link</h2>
<p>Linken er ikke lenger valid eller har blitt deaktivert. Prøv å send inn på nytt.</p>
<p><a href="https://158.39.188.209/PassReset/forgotpassword.php">
Klikk her</a>for å gjenopprette passord</p>';
    }else{

        $expDate = $row;
        if ($expDate >= $curDate){
            ?>
            <br />
            <form method="post" action="" name="update">
                <input type="hidden" name="action" value="update" />
                <br /><br />
                <label><strong>Enter New Password:</strong></label><br />
                <input type="password" name="pass1" maxlength="15" required />
                <br /><br />
                <label><strong>Re-Enter New Password:</strong></label><br />
                <input type="password" name="pass2" maxlength="15" required/>
                <br /><br />
                <input type="hidden" name="email" value="<?php echo $email;?>"/>
                <input type="submit" value="Reset Password" />
            </form>
            <?php
        }else{
            $error= "<h2>Link Utgått</h2>
<p>The link is expired. You are trying to use the expired link which 
as valid only 24 hours (1 days after request).<br /><br /></p>";
        }
    }
    if($error!=""){
        echo "<div class='error'>".$error."</div><br />";
    }
} // isset email key validate end


if(isset($_POST["email"]) && isset($_POST["action"]) &&
    ($_POST["action"]=="update")){
    $error="";
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    $email = $_POST["email"];
    $curDate = date("Y-m-d H:i:s");
    if ($pass1!=$pass2){
        $error.= "<p>Passordene matcher ikke! De må være like<br /><br /></p>";
    }
    if($error!=""){
        echo "<div class='error'>".$error."</div><br />";
    }else{
        $pass1 = password_hash( $pass1, PASSWORD_BCRYPT);

        $query2 ="UPDATE user SET password ='".$pass1."', created ='".$curDate."' WHERE email ='".$email."'";
        $endofres = $db->selectSQL($query2);

        $query3 = "DELETE FROM `passordreset` WHERE `email`='".$email."'";
        $endofres2 = $db->selectSQL($query3);
        echo '<div class="error"><p>Gratulerer! Ditt Passord har blitt gjenopprettet!. Du kan nå prøve å logge deg inn igjen.</p>
<p>Du kan nå gå tilbake til applikasjonen og Logge deg inn på nytt!</p></div><br />';
    }
}
?>
