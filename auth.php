<?php
// Fehleranzeige ganz nach oben!
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Hier passiert die Magie: Session wird gestartet UND CSRF-Token erstellt
require_once ("init.php");
require_once ("functions.php");

// --- Automatischer Redirect, falls bereits eingeloggt (Für den direkten Seitenaufruf) ---
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        header("Location: admin.php");
        exit;
    } else {
        header("Location: user.php");
        exit;
    }
}

// db inputs hardcoded
$servername = "localhost";
$username_db   = "root";
$password_db   = "";
$dbname     = "users";

$login_success = false;
$redirect_url = '';

// build db connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// initialize for later
$error_message = '';
$success_message = '';

// Wenn der User gerade von einer erfolgreichen Registrierung weitergeleitet wurde:
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success_message = "Registrierung erfolgreich! Du kannst dich jetzt einloggen.";
}

// start form handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Sicherheitsfehler: Ungültiger CSRF-Token. Bitte lade die Seite neu und versuche es erneut.");
    }

    // --- REGISTRIERUNG ---
    if (isset($_POST['register'])) {
        $username_form = $_POST['username'] ?? '';
        $password_form = $_POST['password'] ?? '';

        // Rate Limiting für Registrierung
        $ip_address = get_secure_ip();
        $max_regs_per_day = 3; 
        
        $sql_rate = "SELECT COUNT(*) as reg_count FROM register_logs WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $stmt_rate = $conn->prepare($sql_rate);
        $stmt_rate->bind_param("s", $ip_address);
        $stmt_rate->execute();
        $rate_result = $stmt_rate->get_result()->fetch_assoc();
        $stmt_rate->close();

        if ($rate_result['reg_count'] >= $max_regs_per_day) {
            $error_message = "Limit erreicht: Von deiner IP-Adresse wurden heute bereits zu viele Accounts erstellt.";
        } 
        else if (empty($username_form) || empty($password_form)) {
            $error_message = "Benutzername und Passwort sind erforderlich.";
        } 
        else {
            // Prüfen, ob Benutzer bereits existiert
            $sql = "SELECT id FROM user WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username_form);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Dieser Benutzername ist bereits vergeben.";
            } else {
                $password_hash = password_hash($password_form, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO user (username, password) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("ss", $username_form, $password_hash);

                if ($stmt_insert->execute()) { 
                    // Erfolgreiche Registrierung loggen (für Rate Limiting)
                    $sql_reg_log = "INSERT INTO register_logs (ip_address) VALUES (?)";
                    $stmt_reg_log = $conn->prepare($sql_reg_log);
                    $stmt_reg_log->bind_param("s", $ip_address);
                    $stmt_reg_log->execute();
                    $stmt_reg_log->close();

                    header("Location: auth.php?action=login&registered=1");
                    exit;
                } else {
                    $error_message = "Fehler bei der Registrierung: " . $conn->error;
                }
                $stmt_insert->close();
            }
            $stmt->close();
        }

    // --- LOGIN ---
    } elseif (isset($_POST['login'])) {

        $username_form = $_POST['username'] ?? '';
        $password_form = $_POST['password'] ?? ''; 

        if (empty($username_form) || empty($password_form)) {
            $error_message = "Bitte beide Felder ausfüllen.";
        } else {
            $sql = "SELECT id, username, password, is_admin FROM user WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username_form);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $hash_from_db = $user['password'];
                $user_id = $user['id'];

                //brute force lock
                $max_attempts = 5;
                $lockout_time = 15; // Minuten
                
                $sql_brute = "SELECT COUNT(*) as attempts FROM login_logs 
                              WHERE user_id = ? AND success = 0 
                              AND login_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)";
                $stmt_brute = $conn->prepare($sql_brute);
                $stmt_brute->bind_param("ii", $user_id, $lockout_time);
                $stmt_brute->execute();
                $brute_result = $stmt_brute->get_result()->fetch_assoc();
                $stmt_brute->close();

                if ($brute_result['attempts'] >= $max_attempts) {
                    $error_message = "Zu viele Fehlversuche. Dein Account ist für $lockout_time Minuten gesperrt.";
                } else {
                    // --- PASSWORT PRÜFUNG ---
                    $login_allowed = false;

                    if (password_get_info($hash_from_db)['algoName'] !== 'unknown') {
                        if (password_verify($password_form, $hash_from_db)) {
                            $login_allowed = true;
                        }
                    } 
                    else if ($password_form === $hash_from_db) {
                        $login_allowed = true;
                        $new_hash = password_hash($password_form, PASSWORD_DEFAULT);
                        $sql_update = "UPDATE user SET password = ? WHERE id = ?";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bind_param("si", $new_hash, $user_id);
                        $stmt_update->execute();
                        $stmt_update->close();
                    }

                    // --- LOGGING ---
                    $status = $login_allowed ? 1 : 0;
                    $ip_address = get_secure_ip();
                    
                    $sql_log = "INSERT INTO login_logs (user_id, ip_address, success) VALUES (?, ?, ?)";
                    $stmt_log = $conn->prepare($sql_log);
                    $stmt_log->bind_param("isi", $user_id, $ip_address, $status);
                    $stmt_log->execute();
                    $stmt_log->close();

                    if ($login_allowed) {
                        // Session befüllen
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['is_admin'] = $user['is_admin'];
                        $_SESSION['user_id']  = $user_id;

                        // WICHTIG: Hier nutzen wir wieder dein JavaScript für den Redirect!
                        $login_success = true;
                        $success_message = "Erfolgreich eingeloggt! Weiterleitung...";
                        $redirect_url = ($user['is_admin'] == 1) ? "admin.php" : "user.php";
                    } else {
                        $error_message = "Falsches Passwort.";
                    }
                } 
            } else {
                $error_message = "Benutzername nicht gefunden.";
            }
            $stmt->close();
        }
    }
}
$conn->close();

$action = $_GET['action'] ?? 'login';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo ($action === 'register') ? 'Registrieren' : 'Login'; ?></title>
    <link rel="stylesheet" href="./style/main.css">
    <style>
        body { text-align: center; }
        form { background: grey; border-radius: 0.75em; padding: 20px; max-width: 28em; margin: 20px auto; }
        input[type="text"], input[type="password"] { width: 90%; padding: 10px; margin-bottom: 10px; }
        button { background: #337ab7; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; }
        .error { color: #a94442; background: #f2dede; border: 1px solid #ebccd1; padding: 10px; display: inline-block; border-radius: 4px; margin-top: 10px; }
        .success { color: #3c763d; background: #dff0d8; border: 1px solid #d6e9c6; padding: 10px; display: inline-block; border-radius: 4px; margin-top: 10px; }
        .toggle-link { margin-top: 15px; }
        .underline { text-decoration: underline; color: white; }
    </style>
</head>
<body>

<?php include './template/navbar.php'; ?>

<?php if ($error_message): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="success"><?php echo $success_message; ?></div>
<?php endif; ?>

<div id="message_shown" class="message_hidden"></div>

<?php if ($action === 'register'): ?>

    <form action="auth.php?action=register" method="POST">
        <!-- CSRF Hidden Input -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
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
            Hast du schon einen Account? <a href="auth.php?action=login" style="color:white;">Hier einloggen</a>
        </div>
        <p>Get <a class="underline" href="index.php">back to start</a></p>
    </form>

<?php else: ?>

    <form action="auth.php?action=login" method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
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
            Noch kein Account? <a href="auth.php?action=register" style="color:white;">Hier registrieren</a>
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