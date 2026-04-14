<?php
//starts user session, collects important data
session_start();
//errors displayed on site, kill before going live
ini_set('display_errors', 1);
//all errors even warnings
error_reporting(E_ALL);
require_once ("init.php");

// --- NEU: Automatischer Redirect, falls bereits eingeloggt ---
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        header("Location: admin.php");
        exit;
    } else {
        header("Location: user.php");
        exit;
    }
}

//db inputs hardcoded, need to be loaded via ajax in main file soon
$servername = "localhost";
$username_db   = "root";
$password_db   = "";
$dbname     = "users";
$login_success = false;
$redirect_url = '';
//build db connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname); //new object in sqli class, try's to connect with db
if ($conn->connect_error) { //did it work?
    die("Connection failed: " . $conn->connect_error); //kill script if error
}
//initialize for later
$error_message = '';
$success_message = '';

//start form handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //check if register is clicked
    if (isset($_POST['register'])) {
        $username_form = $_POST['username'] ?? ''; //get username from form and set empty string
        $password_form = $_POST['password'] ?? ''; //same for passw

        if (empty($username_form) || empty($password_form)) {
            $error_message = "Benutzername und Passwort sind erforderlich.";
        } else {
            // Prüfen, ob Benutzer bereits existiert
            $sql = "SELECT id FROM user WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username_form);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Dieser Benutzername ist bereits vergeben.";
            } else {
                //creates hash for input password+random salt; never in plain text
                $password_hash = password_hash($password_form, PASSWORD_DEFAULT);

                //insert new user in db bind strings together with hash
                $sql_insert = "INSERT INTO user (username, password) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("ss", $username_form, $password_hash);

                if ($stmt_insert->execute()) { //sends execute order to db
                    $success_message = "Registrierung erfolgreich! Du kannst dich jetzt einloggen.";
                } else {
                    $error_message = "Fehler bei der Registrierung: " . $conn->error;
                }
                $stmt_insert->close();
            }
            $stmt->close();
            //close to free ressources
        }

        //use if login button is used
    } elseif (isset($_POST['login'])) {

        $username_form = $_POST['username'] ?? '';
        $password_form = $_POST['password'] ?? ''; //plain text psswd from form
        // same as in registration
        if (empty($username_form) || empty($password_form)) {
            $error_message = "Bitte beide Felder ausfüllen.";
        } else {
            //fetch user from db, get primary key, username and password
            $sql = "SELECT id, username, password, is_admin FROM user WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username_form);
            $stmt->execute();
            $result = $stmt->get_result();

            // looks if exactly one user is found
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc(); //--> changes to array form
                $hash_from_db = $user['password']; // saves password

                // (Der Debug-Block ist jetzt auskommentiert)
                /* // --- NEUER DEBUG-BLOCK ---
                echo "<div style='font-family: monospace; background: #ffffcc; border: 2px solid #e6db55; padding: 15px; text-align: left;'>";
                echo "<h2>DEBUG: Was ist WIRKLICH in der Datenbank?</h2>";

                echo "<b>Formular-Eingabe (Passwort):</b><br>";
                var_dump($password_form);

                echo "<br><br><b>Inhalt aus DB-Spalte 'password':</b><br>";
                var_dump($hash_from_db); // Das ist der entscheidende Wert!

                echo "<br><br><b>Ergebnis von password_get_info(...):</b><br>";
                var_dump(password_get_info($hash_from_db));
                echo "</div>";
                exit; // Stoppt das Skript hier, damit wir nur das sehen.
                // --- ENDE DEBUG-BLOCK --*/

                // is used algo not unkown? if not its plain text stored password
                if (password_get_info($hash_from_db)['algoName'] !== 'unknown') {

                    //checks password from input with hash can extract salt
                    if (password_verify($password_form, $hash_from_db)) {
                        // start login logging
                        // if login good, log the successful attempt
                        $status = 1;
                        // $ip_address = get_secure_ip(); //get ip from user
                        $ip_address = "127.0.0.1"; // dummy ip
                        $user_id = $user['id']; // get user id from db
                        $sql_log = "INSERT INTO login_logs (user_id, ip_address, success) VALUES (?, ?, ?)"; //log data
                        $stmt_log = $conn->prepare($sql_log);
                        $stmt_log->bind_param("isi", $user_id, $ip_address, $status);
                        $stmt_log->execute();
                        $stmt_log->close();

                        //login successful
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['is_admin'] = $user['is_admin'];
                        $_SESSION['user_id']  = $user['id'];

                        $login_success = true;
                        $success_message = "Erfolgreich eingeloggt! Weiterleitung...";
                        $redirect_url = ($user['is_admin'] == 1) ? "admin.php" : "index.php";
                    } else {
                        //not successful
                        $error_message = "Falsches Passwort.";
                        //log failed login attempt
                        $status = 0;
                        // $ip_address = get_secure_ip();
                        $ip_address = "127.0.0.1"; // dummy ip
                        $user_id = $user['id'];
                        $sql_log = "INSERT INTO login_logs (user_id, ip_address, success) VALUES (?, ?, ?)";
                        $stmt_log = $conn->prepare($sql_log);
                        $stmt_log->bind_param("isi", $user_id, $ip_address, $status);
                        $stmt_log->execute();
                        $stmt_log->close();
                    }
                }
                //checks plain password
                else if ($password_form === $hash_from_db) {
                    // start login logging
                    //same as first logging, just with clear text psswd.
                    $status = 1;
                    // $ip_address = get_secure_ip();
                    $ip_address = "127.0.0.1"; // dummy ip
                    $user_id = $user['id'];
                    $sql_log = "INSERT INTO login_logs (user_id, ip_address,success) VALUES (?, ?, ?)";
                    $stmt_log = $conn->prepare($sql_log);
                    $stmt_log->bind_param("isi", $user_id, $ip_address, $status);
                    $stmt_log->execute();
                    $stmt_log->close();


                    //login with plain pass done
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    $_SESSION['user_id']  = $user['id'];

                    $login_success = true;
                    $success_message = "Erfolgreich eingeloggt! Weiterleitung...";
                    $redirect_url = ($user['is_admin'] == 1) ? "admin.php" : "index.php";

                    //now create hash
                    $new_hash = password_hash($password_form, PASSWORD_DEFAULT);
                    // update new password
                    $sql_update = "UPDATE user SET password = ? WHERE id = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("si", $new_hash, $user['id']);
                    $stmt_update->execute();
                    $stmt_update->close();



                } else {
                    //wrong password but plain
                    $error_message = "Falsches Passwort.";
                    $status = 0; //log failed attempt
                    // $ip_address = get_secure_ip();
                    $ip_address = "127.0.0.1"; // dummy ip
                    $user_id = $user['id'];
                    $sql_log = "INSERT INTO login_logs (user_id, ip_address, success) VALUES (?, ?, ?)";
                    $stmt_log = $conn->prepare($sql_log);
                    $stmt_log->bind_param("isi", $user_id, $ip_address, $status);
                    $stmt_log->execute();
                    $stmt_log->close();
                }


            } else {
                $error_message = "Benutzername nicht gefunden.";

            }
            $stmt->close(); // select statement closed
        }
    }
}
$conn->close(); // close db conn

// --- Logik zur Formular-Anzeige ---
// Standardmäßig Login anzeigen, außer ?action=register ist gesetzt
$action = $_GET['action'] ?? 'login'; // default login page looks for action param in url
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./style/main.css">
    <style>
        body { font-family: sans-serif; text-align: center; }
        form { background: #f4f4f4; border: 1px solid #ccc; padding: 20px; max-width: 28em; margin: 20px auto; }
        input[type="text"], input[type="password"] { width: 90%; padding: 10px; margin-bottom: 10px; }
        button { background: #337ab7; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
        .toggle-link { margin-top: 15px; }
    </style>
</head>
<body>
<?php include './template/navbar.php'; ?>
<?php if ($error_message): ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if ($success_message): ?>
    <p class="success"><?php echo $success_message; ?></p>
<?php endif; ?>

<div id="message_shown" class="message_hidden">
</div>

<?php if ($action === 'register'): ?>

    <form action="auth.php?action=register" method="POST">
        <h1>Registrieren</h1>
        <p>Erstelle einen neuen Account.</p>
        <div>
            <label for="username">Benutzername:</label><br>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Passwort:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="register">Registrieren</button>
        <div class="toggle-link">
            Hast du schon einen Account? <a href="auth.php?action=login">Hier einloggen</a>
        </div>
        <p>Get <a class="underline" href="index.php">back to start</a></p>
    </form>

<?php else: ?>

    <form action="auth.php?action=login" method="POST">
        <h1>Login</h1>
        <div>
            <label for="username">Benutzername:</label><br>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Passwort:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
        <div class="toggle-link">
            Noch kein Account? <a href="auth.php?action=register">Hier registrieren</a>
        </div>
        <p>Get <a class="underline" href="index.php">back to start</a></p>
    </form>
    

<?php endif; ?>
<script src="functions.js"></script>

<?php if ($login_success): ?>
    <script>
        // Aufruf der JS-Funktion mit der korrekten URL aus PHP
        handleSuccessfulLogin("<?php echo $redirect_url; ?>");
    </script>
<?php endif; ?>
</body>
</html>