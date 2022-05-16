<?php
    session_start();

    //Allerede innlogget forelesere/studenter skal ikke få ny session id
    //Gjester vil ikke nå få en ny sesjon uten å logge på
    if(!isset($_SESSION['loggedin'])) {
        session_regenerate_id(true);
        //$_SESSION['loggedin'] = TRUE;
        $_SESSION['typebruker'] = 'gjest';

        echo 'Velkommen gjest!';
        header("Refresh:2; url=../gjestInputFagPin.php");
    }
    else if($_SESSION['typebruker'] === 'gjest') {
        echo 'Velkommen tilbake, gjest!';
        header("Refresh:2; url=../gjestInputFagPin.php");    
    }
    else {
        echo 'Velkommen tilbake, registrert bruker!';
        header("Refresh:2; url=../gjestInputFagPin.php");
    }

?>