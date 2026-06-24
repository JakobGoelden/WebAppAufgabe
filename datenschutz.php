<?php
require_once("init.php");
require_once("functions.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datenschutz – GuardX</title>
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

<main class="content content-narrow">
    <h1>Datenschutzerklärung</h1>
    <h2>1. Datenschutz auf einen Blick</h2>
    <h3>Allgemeine Hinweise</h3>
    <p>Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen</p>
    
    <h2>2. Datenerfassung auf unserer Website</h2>
    <p>Die Datenerarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten können Sie dem Impressum dieser Website entnehmen.</p>
</main>

<?php
include_once("template/footer.php");
?>