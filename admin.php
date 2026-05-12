<?php
// pull in session config and security headers.
require_once("init.php");

// login double check: kick out if session cookie is missing or invalid.
// prevents unauthorized access to the admin area.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    // header redirects to login/register page if session not set.
    header("Location: auth.php");
    exit; // kill script execution immediately.
}

// privilege check: verify if the user has admin rights (is_admin = 1).
// stops regular users from accessing restricted tools via direct url.
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // authenticated but lacks permission. redirect to safe index.
    header("Location: index.php");
    exit;
}

// if logic reaches this point, login and permissions are verified.
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
        h1 { color: #333; }
        strong { color: #d9534f; }
        a { color: #337ab7; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h1>Willkommen im Admin-Bereich!</h1>

<!-- 
    xss protection (cross-site scripting): htmlspecialchars() converts 
    special chars like < > into html entities (&lt; &gt;). 
    prevents malicious js code in usernames from executing in the browser. 
-->
<p>Hallo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>

<p>Du siehst diese Seite, weil du erfolgreich eingeloggt bist.</p>
<p>Hier könntest du jetzt geheime Admin-Funktionen einbauen.</p>

<br>

<!-- manual logout: calls script to destroy session on server. -->
<p><a href="./admin_logout.php">Ausloggen</a></p>

<br>

<!-- navigation link: session cookies remain active. -->
<p><a href="./index.php">Angemeldet bleiben und zurück zur Startseite</a></p>

</body>
</html>