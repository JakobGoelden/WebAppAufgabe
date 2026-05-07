<?php
require_once("init.php");
//Login double check for entry

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {

    //header redirects to login/regist page if Session cookie not set
    header("Location: auth.php");
    exit; //exit script
}

// Prüfen, ob der User Admin ist (lockerer Check mit != statt !==)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // logged in but no Admin
    header("Location: index.php");
    exit;
}

//if statement not triggered --> login was good you can see the page
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
<!--prevents XSS with special chars, without this <script>alert('gehackt')</script> wouldnt be displayed but EXECUTED -->
<p>Hallo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>

<p>Du siehst diese Seite, weil du erfolgreich eingeloggt bist.</p>
<p>Hier könntest du jetzt geheime Admin-Funktionen einbauen.</p>

<br>
<!--redirect to logout pagey -->
<p><a href="./admin_logout.php">Ausloggen</a></p>

<br>
<!-- redirect to Homepage session cookies remain-->
<p><a href="./index.php">Angemeldet bleiben und zurück zur Startseite</a></p>

</body>
</html>