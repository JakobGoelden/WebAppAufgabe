<?php
require_once("init.php");
require_once("functions.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGB – GuardX</title>
    <link rel="stylesheet" href="./style/main.css">
</head>
<body>
<?php
if (is_mobile()) {
    include './template/navbar_mobile.php';
} else {
    include './template/navbar.php';
}
?>


<main class="content" style="padding: 40px 20px; max-width: 800px; margin: 0 auto;">
    <h1>Allgemeine Geschäftsbedingungen (AGB)</h1>
    <p>Stand: Juni 2026</p>
    
    <h3>§1 Geltungsbereich</h3>
    <p>Für die Nutzung dieser WebApp gelten die folgenden Bedingungen</p>
    
    <h3>§2 Registrierung und Nutzung</h3>
    <p>Der Nutzer verpflichtet sich, bei der Registrierung wahrheitsgemäße Angaben zu machen</p>
</main>

<?php
include_once("template/footer.php");
?>