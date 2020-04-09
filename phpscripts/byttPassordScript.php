<?php
    require("mysqliDB.php");

    $typeBruker = $_SESSION['typebruker'];

    $brukernavn = htmlspecialchars($_COOKIE["Brukernavn"]);

    $gammeltPassord = $_POST['gammeltPassord'];
    $nyttPassord = $_POST['nytttPassord'];

    if($typeBruker === 'student') {
        $result = mysqli_query($con, "SELECT * from student WHERE brukernavn='" . $brukernavn . "'");
        $row = mysqli_fetch_array($result);
        if ($_POST["gammeltPassord"] == $row["passord"]) {
            mysqli_query($con, "UPDATE student set passord='" . $_POST["nyttPassord"] . "' WHERE brukernavn='" . $brukernavn . "'");
            echo "Passord endret";
            header("Refresh:2; url=../index.php");
        } else {
            echo "Gamle passordet er ikke korrekt";
            header("Refresh:2; url=../bytt.php");
        }
    }
    else if($typeBruker === 'foreleser') {
        $result = mysqli_query($con, "SELECT * from foreleser WHERE brukernavn='" . $brukernavn . "'");
        $row = mysqli_fetch_array($result);
        if ($_POST["gammeltPassord"] == $row["passord"]) {
            mysqli_query($con, "UPDATE foreleser set passord='" . $_POST["nyttPassord"] . "' WHERE brukernavn='" . $brukernavn . "'");
            echo "Passord endret";
            header("Refresh:2; url=../index.php");
        } else {
            echo "Gamle passordet er ikke korrekt";
            header("Refresh:2; url=../bytt.php");

        }
    }
?>