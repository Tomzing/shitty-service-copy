<?php
    session_start();
    include("mysqliDB.php");
    
    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header("Access-Control-Allow-Origin: *");
    error_reporting();*/

    require '../vendor/autoload.php';

    use Monolog\Logger;
    use Monolog\Handler\GelfHandler;
    use Gelf\PublisherInterface;
    use Gelf\Publisher;
    use Gelf\Message;
    use Gelf\Transport\UdpTransport;

$logger = new Gelf\Logger();
//$logger = new Logger('Innlogging event');

$given_params = array_keys($_POST);

if(sizeof($given_params) > 4) {
    die('Her har du gjort noe finurlig, Mr. Hackerman');
}

if (!isset($_POST['brukernavn'], $_POST['passord']) ) {
    // Now we check if the data from the login form was submitted, isset() will check if the data exists.
    // Could not get the data that should have been sent.
    die ('Fyll inn både brukernavn og passord din juksemaker!');
}

        $passwordtobehashed = $_POST['passord'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6LdUcegUAAAAADuXCiNmRXaU1rMQD4xdRjdmi7TO';
        $recaptcha_response = $_POST['recaptcha_response'];

        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        // Take action based on the score returned:
        if ($recaptcha->score >= 0.5) {
            $selected_val = $_POST['velgStilling'];

            if($selected_val === "student") {
                // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
                if ($stmt = $con->prepare('SELECT id, passord FROM student WHERE brukernavn = ?')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt->bind_param('s', $_POST['brukernavn']);
                    $stmt->execute();
                    // Store the result so we can check if the account exists in the database.
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $passord);
                        $stmt->fetch();
                        // Account exists, now we verify the password.
                        // Note: remember to use password_hash in your registration file to store the hashed passwords.

                        if (password_verify("etSaltSomIkkeKrenkerNoen".$passwordtobehashed,$passord)) {
                            // Verification success! User has loggedin!
                            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                            session_regenerate_id();
                            $_SESSION['loggedin'] = TRUE;
                            $_SESSION['typebruker'] = $selected_val;
                            $_SESSION['brukernavn'] = $_POST['brukernavn'];
                            $_SESSION['brukerid'] = $id;
                            setcookie('Brukernavn', $_POST['brukernavn']);
                            echo 'Velkommen inn, ' . $_SESSION['brukernavn'] . ' med brukerid ' . $_SESSION['brukerid'] . '!';

                            //$transport = new UdpTransport("127.0.0.1", 12201 /*, Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN*/);
                            //$publisher = new Publisher($transport);
                            //$handler = new GelfHandler($publisher,Logger::INFO);

                            //$message = "IP: " . getUserIpAddr() . " student: " . $_SESSION['brukernavn'] . " har logget inn";
                            //$log = new Logger('Lego');
                            //$log->pushHandler($handler);
                            //$log->Info('Info: ' . $message);

                            $logger->notice("IP: " . getUserIpAddr() . " student: " . $_SESSION['brukernavn'] . " har logget inn");
                            //$logger->pushHandler($handler);

                            header("Refresh:2; url=../innloggetMeny.php");
                        } else {
                            echo 'Feil passord :(';
                        }
                    } else {
                        echo 'Feil brukernavn :(';
                    }
                    $stmt->close();
                }
            }
            else if($selected_val === "foreleser") {
                // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
                if ($stmt = $con->prepare('SELECT id, passord FROM foreleser WHERE brukernavn = ?')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt->bind_param('s', $_POST['brukernavn']);
                    $stmt->execute();
                    // Store the result so we can check if the account exists in the database.
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $passord);
                        $stmt->fetch();
                        // Account exists, now we verify the password.
                        // Note: remember to use password_hash in your registration file to store the hashed passwords.
                        if (password_verify("etSaltSomIkkeKrenkerNoen".$passwordtobehashed,$passord)) {
                            // Verification success! User has loggedin!
                            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                            session_regenerate_id();
                            $_SESSION['loggedin'] = TRUE;
                            $_SESSION['typebruker'] = $selected_val;
                            $_SESSION['brukernavn'] = $_POST['brukernavn'];
                            $_SESSION['brukerid'] = $id;
                            setcookie('Brukernavn', $_POST['brukernavn']);
                            echo 'Velkommen inn, ' . $_SESSION['brukernavn'] . '!';
                            $logger->notice("IP: " . getUserIpAddr() . " foreleser: " . $_SESSION['brukernavn'] . " har logget inn");
                            header("Refresh:2; url=../innloggetMeny.php");
                        }
                        else {
                            echo 'Feil passord :(';
                        }

                    } else {
                        echo 'Feil brukernavn :(';
                    }
                    $stmt->close();
                }
            }
            else if($selected_val === "admin") {
                // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
                if ($stmt = $con->prepare('SELECT id, passord FROM admin WHERE brukernavn = ?')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt->bind_param('s', $_POST['brukernavn']);
                    $stmt->execute();
                    // Store the result so we can check if the account exists in the database.
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $passord);
                        $stmt->fetch();
                        // Account exists, now we verify the password.
                        // Note: remember to use password_hash in your registration file to store the hashed passwords.
                        if (password_verify("etSaltSomIkkeKrenkerNoen".$passwordtobehashed,$passord)) {
                            // Verification success! User has loggedin!
                            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                            session_regenerate_id();
                            $_SESSION['loggedin'] = TRUE;
                            $_SESSION['typebruker'] = $selected_val;
                            $_SESSION['brukernavn'] = $_POST['brukernavn'];
                            $_SESSION['brukerid'] = $id;
                            setcookie('Brukernavn', $_POST['brukernavn']);
                            echo 'Velkommen inn, ' . $_SESSION['brukernavn'] . '!';
                            $logger->notice("IP: " . getUserIpAddr() . " admin: " . $_SESSION['brukernavn'] . " har logget inn");
                            header("Refresh:2; url=../innloggetMeny.php");
                        }
                        else {
                            echo 'Feil passord :(';
                        }

                    } else {
                        echo 'Feil brukernavn :(';
                    }
                    $stmt->close();
                }
            }
        } else {
            echo 'Du feilet captchaen din dustemikkel :('. ' ' . $recaptcha->score;
        }

    }

?>