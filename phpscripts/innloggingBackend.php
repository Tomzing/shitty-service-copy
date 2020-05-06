<?php
    session_start();

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

    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

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
                            setcookie('Brukernavn', $_POST['brukernavn'], $secure=FALSE, $httponly=FALSE);
                            echo 'Save pathen er ' . session_save_path();
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
                            echo "<a href='/innloggetMeny.php'>Trykk her om du ikke blir videreført :)</a>";
                            echo "<a href='/innloggetMeny.php'>Trykk her om du ikke blir videreført :)</a>";
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
                            setcookie('Brukernavn', $_POST['brukernavn'], $secure=FALSE, $httponly=FALSE);
                            echo 'Velkommen inn, ' . $_SESSION['brukernavn'] . '!';
                            $logger->notice("IP: " . getUserIpAddr() . " foreleser: " . $_SESSION['brukernavn'] . " har logget inn");
                            echo "<a href='/innloggetMeny.php'>Trykk her om du ikke blir videreført :)</a>";
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

                $DATABASE_HOST = 'localhost';
                $DATABASE_USER = 'admin';
                $DATABASE_PASS = 'kbp5V8Nmb8ry6EZf';
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
                define( 'DB_USER', 'admin' ); // set database user
                define( 'DB_PASS', 'kbp5V8Nmb8ry6EZf' ); // set database password
                define( 'DB_NAME', 'virusnet' ); // set database name
                define( 'DISPLAY_DEBUG', false ); //display db errors?


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
                            setcookie('Brukernavn', $_POST['brukernavn'], $secure=FALSE, $httponly=FALSE);
                            echo 'Velkommen inn, ' . $_SESSION['brukernavn'] . '!';
                            $logger->notice("IP: " . getUserIpAddr() . " admin: " . $_SESSION['brukernavn'] . " har logget inn");
                            echo "<a href='/innloggetMeny.php'>Trykk her om du ikke blir videreført :)</a>";
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