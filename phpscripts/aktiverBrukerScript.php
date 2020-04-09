<?php
    require 'mysqliDB.php';

    if(!empty($_POST)){
        if(isset($_POST['aktivIdS'])){
            $studentId = $_POST['studentId'];
            $sqlOppdaterStudent = 'UPDATE student SET aktivert="1" WHERE id="' . $studentId . '";';
            if($con->query($sqlOppdaterStudent) === TRUE){
                echo '<script>console.log("Bruker Oppdatert");</script>';
            } else {
                echo '<script>console.log("Feil ved oppdatering av bruker");</script>';
            }
        }

        if(isset($_POST['aktivIdF'])){
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