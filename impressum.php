<?php
// Header und Navbar einbinden (Pfade eventuell anpassen, falls nötig)

include_once("header.html"); // Falls vorhanden
include_once("template/navbar.php"); 

?>

<main class="legal-page-content" style="padding: 40px 20px; max-width: 800px; margin: 0 auto; color: #fff;">
    <h1>Impressum</h1>
    <p>Angaben gemäß § 5 TMG:</p>
    
    <h3>Betreiber der Website:</h3>
    <p>
        Max Mustermann<br>
        Musterstraße 123<br>
        12345 Musterstadt
    </p>

    <h3>Kontakt:</h3>
    <p>
        Telefon: +49 (0) 123 456789<br>
        E-Mail: info@webappname.de
    </p>

    <h3>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:</h3>
    <p>Max Mustermann (Anschrift wie oben)</p>
</main>

<?php
// Footer einbinden
include_once("template/footer.php");
?>