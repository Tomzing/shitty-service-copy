<?php


    if(!empty($_POST)){
        if(isset($_POST['aktivIdS'])){
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
            $studentId = $_POST['studentId'];
            $sqlOppdaterStudent = 'UPDATE student SET aktivert="1" WHERE id="' . $studentId . '";';
            if($con->query($sqlOppdaterStudent) === TRUE){
                echo '<script>console.log("Bruker Oppdatert");</script>';
            } else {
                echo '<script>console.log("Feil ved oppdatering av bruker");</script>';
            }
        }

        if(isset($_POST['aktivIdF'])){
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
            $foreleserId = $_POST['foreleserId'];
            $sqlOppdaterForeleser = 'UPDATE foreleser SET aktivert="1" WHERE id="' . $foreleserId . '";';
            if($con->query($sqlOppdaterForeleser) === TRUE){
                echo '<script>console.log("Bruker Oppdatert");</script>';
            } else {
                echo '<script>console.log("Feil ved oppdatering av bruker");</script>';
            }
        }

    }
?>