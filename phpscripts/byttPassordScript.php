<?php


    $typeBruker = $_SESSION['typebruker'];

    $brukernavn = htmlspecialchars($_COOKIE["Brukernavn"]);

    $gammeltPassord = $_POST['gammeltPassord'];
    $nyttPassord = $_POST['nytttPassord'];

    if($typeBruker === 'student') {
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'student';
        $DATABASE_PASS = 'IA1vz6TNpdya6X8G';
        $DATABASE_NAME = 'virusnet';
        // Try and connect using the info above.
        $conMysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        $conMysqli->set_charset("utf8");
        if (mysqli_connect_errno() ) {
            // If there is an error with the connection, stop the script and display the error.
            die ('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        $con = mysqli_connect("localhost", "root", "1337hackermangruppe09", "virusnet");
        define( 'DB_HOST', 'localhost' ); // set database host
        define( 'DB_USER', 'student' ); // set database user
        define( 'DB_PASS', 'IA1vz6TNpdya6X8G' ); // set database password
        define( 'DB_NAME', 'virusnet' ); // set database name
        define( 'DISPLAY_DEBUG', false ); //display db errors?

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
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'foreleser';
        $DATABASE_PASS = 'ITyu8uXEVmXxA3iX';
        $DATABASE_NAME = 'virusnet';
// Try and connect using the info above.
        $conMysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        $conMysqli->set_charset("utf8");
        if (mysqli_connect_errno() ) {
            // If there is an error with the connection, stop the script and display the error.
            die ('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        $con = mysqli_connect("localhost", "root", "1337hackermangruppe09", "virusnet");
        define( 'DB_HOST', 'localhost' ); // set database host
        define( 'DB_USER', 'foreleser' ); // set database user
        define( 'DB_PASS', 'ITyu8uXEVmXxA3iX' ); // set database password
        define( 'DB_NAME', 'virusnet' ); // set database name
        define( 'DISPLAY_DEBUG', false ); //display db errors?

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