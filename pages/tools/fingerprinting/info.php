<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/config.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: " . BASE_URL . "pages/auth.php");
    exit; 
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebApp Projekt</title>
    <link rel="stylesheet" href="<?= get_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= get_url('assets/css/navbar.css') ?>">

    <script>
        function show(value) {
            document.write(
                "<span class='text-red'>" +
                value +
                "</span>"
            );
        }

        const userAgent = <?php echo json_encode($_SERVER['HTTP_USER_AGENT']); ?>;
        const language = <?php echo json_encode($_SERVER['HTTP_ACCEPT_LANGUAGE']); ?>;

        const screenSize = screen.width + "x" + screen.height;
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const cpuCores = navigator.hardwareConcurrency;
        const deviceMemory = navigator.deviceMemory || "unknown";
        const colorDepth = screen.colorDepth;
        const doNotTrack = navigator.doNotTrack;

        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        ctx.textBaseline = "top";
        ctx.font = "16px Arial";
        ctx.fillText("fingerprint", 10, 10);
        const canvasFingerprint = canvas.toDataURL();

        const gl = document.createElement("canvas").getContext("webgl");
        let webglVendor = "Unavailable";
        let webglRenderer = "Unavailable";
        if (gl) {
            webglVendor = gl.getParameter(gl.VENDOR);
            webglRenderer = gl.getParameter(gl.RENDERER);
        }
    </script>
</head>
<body>
    <?php 
    if (is_mobile()) {
        include __DIR__ . '/../../../templates/navbar_mobile.php';
    } else {
        include __DIR__ . '/../../../templates/navbar.php';
    }
    ?>

    <h1>Browser Fingerprinting</h1>
    <br>

    <p>
    Viele Menschen denken, dass Cookies die wichtigste Methode sind, mit der Websites Nutzer im Internet verfolgen.
    Cookies sind zwar weit verbreitet, aber sie sind nur ein Teil des modernen Trackings.
    Cookies können in der Regel gelöscht, blockiert oder auf eine einzelne Website beschränkt werden.
    Browser-Fingerprinting funktioniert anders: Es sammelt Informationen über dein Gerät und deinen Browser, um ein Profil zu erstellen, das dich über viele Websites hinweg identifizieren kann – oft ohne etwas auf deinem Computer zu speichern.
    </p>

    <div class="info-block">
        <p>
        <b>Besonders einzigartige Fingerprinting-Signale:</b><br>
        Dies sind die wirksamsten Tracking-Methoden, da sie dein Gerät oft eindeutig identifizieren können – auch ohne Cookies oder Anmeldedaten. Sie nutzen feine Unterschiede in Hardware und Darstellung.
        </p>

        <p>
        <b>Canvas-Fingerprint:</b> (Deiner beginnt mit: <script>show(canvasFingerprint.substring(0, 50) + "...");</script>)
        Websites können deinen Browser dazu bringen, unsichtbare Grafiken zu zeichnen. Unterschiede bei Schriftarten, GPU, Treibern und Systemkonfiguration machen das Ergebnis sehr einzigartig.
        </p>

        <p>
        <b>WebGL-Hersteller:</b> (Deiner ist: <script>show(webglVendor);</script>) und
        <b>Renderer:</b> (Deiner ist: <script>show(webglRenderer);</script>)
        WebGL gibt detaillierte Informationen über die Grafikhardware preis, die dein Gerät und deine GPU-Konfiguration stark identifizieren können.
        </p>
    </div>

    <div class="info-block">
        <p>
        <b>Standortbezogene Signale:</b><br>
        Diese Werte verraten nicht direkt deine genaue Adresse, aber sie können deine Region eingrenzen und helfen, deine Aktivitäten über verschiedene Websites hinweg zu verknüpfen.
        </p>

        <p>
        <b>Sprache:</b> (Deine ist: <script>show(language);</script>)
        Die Spracheinstellung deines Browsers kann dein Land oder deine bevorzugte Region verraten und hilft beim Erstellen eines regionalen Profils.
        </p>

        <p>
        <b>Zeitzone:</b> (Deine ist: <script>show(timezone);</script>)
        Deine Zeitzone verrät oft deinen ungefähren geografischen Standort und kann genutzt werden, um Browsing-Aktivitäten über Sitzungen hinweg zu verknüpfen.
        </p>
    </div>

    <div class="info-block">
        <p>
        <b>Geräte- und technische Informationen:</b><br>
        Diese Werte beschreiben deinen Browser und deine Hardware. Auch wenn jeder einzelne Wert harmlos erscheint, ergeben sie zusammen einen detaillierten Fingerabdruck deines Geräts.
        </p>

        <p>
        <b>User Agent:</b> (Deiner ist: <script>show(userAgent);</script>)
        Dies verrät deinen Browser, dein Betriebssystem und deinen Gerätetyp und ist damit ein zentraler Bestandteil der meisten Tracking-Systeme.
        </p>

        <p>
        <b>Bildschirmauflösung:</b> (Deine ist: <script>show(screenSize);</script>)
        Deine Bildschirmgröße hilft, dein Gerät von anderen zu unterscheiden, und trägt zur Einzigartigkeit des Fingerprints bei.
        </p>

        <p>
        <b>CPU-Kerne:</b> (Deine Anzahl: <script>show(cpuCores);</script>)
        Die Anzahl der CPU-Kerne gibt Aufschluss über die Leistung deiner Hardware und die Geräteklasse.
        </p>

        <p>
        <b>Gerätespeicher:</b> (Deiner ist: <script>show(deviceMemory);</script>)
        Gibt deinen verfügbaren Arbeitsspeicher an und hilft zu erkennen, ob du ein günstiges oder hochwertiges Gerät nutzt.
        </p>

        <p>
        <b>Farbtiefe:</b> (Deine ist: <script>show(colorDepth);</script>)
        Ein kleines, aber messbares Anzeige-Merkmal, das beim Fingerprinting verwendet wird.
        </p>

        <p>
        <b>Do Not Track:</b> (Deiner ist: <script>show(doNotTrack);</script>)
        Eine Datenschutzeinstellung, die Tracking reduzieren soll, aber von den meisten Websites vollständig ignoriert wird.
        </p>
    </div>

    <p>
        Keiner dieser Werte allein kann dich garantiert identifizieren.
        Die Gefahr entsteht durch die Kombination.
        Wenn dutzende kleine Details gleichzeitig gesammelt werden, können sie einen Fingerabdruck erzeugen, der einzigartig genug ist, um dich über Websites hinweg zu verfolgen – auch ohne Cookies oder Anmeldung bei einem Konto.
    </p>
</body>