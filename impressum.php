<?php
require_once("init.php");
require_once("functions.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressum – GuardX</title>
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
    <h1>Impressum</h1>
    <p>Angaben gemäß § 5 TMG:</p>
    
    <h3>Betreiber der Website:</h3>
    <p>
        Gruppe2<br>
        Coblitz-Allee<br>
        DHBW Mannheim
    </p>

    <h3>Kontakt:</h3>
    <p>
        Telefon: +49 123456789<br>
        E-Mail: test@test.de
    </p>

    <h3>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:</h3>
    <p>Gruppe2</p>
</main>

<?php
// Footer einbinden
include_once("template/footer.php");
?>