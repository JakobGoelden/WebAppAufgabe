<?php
require_once("init.php");
require_once("functions.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GuardX</title>
    <link rel="stylesheet" href="./style/main.css">
</head>

<script src="functions.js"></script>

<body>
    <?php include './template/navbar.php'; ?>

    <div class="content">

        <h1>Web Security & Privacy Toolkit</h1>
        <h2>Ein modulares Dashboard zur Erkennung digitaler Fingerabdrücke, Bereinigung von Metadaten und Validierung von Sicherheits-Credentials.</h2>
        
        <div class="container">

            <div class="subsite">
                <svg class="subsite_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100" height="100">
                    <rect x="3" y="3" width="18" height="18" rx="3" fill="#1E293B" stroke="#334155" stroke-width="1" />
                    <circle cx="16.5" cy="8.5" r="2" fill="#10B981" />
                    <path d="M11 21l4.5-5.5L21 21Z" fill="#0F172A" />
                    <path d="M3 21l6-8 5 6.5L11 21Z" fill="#475569" />
                </svg>
                <div class="subsite_content">
                    <h2 class="subsite_header">Metadaten-Bereiniger</h2>
                    <p class="subsite_text">
                        Schütze deine Privatsphäre, indem du versteckte EXIF-Daten, GPS-Koordinaten und Kamera-Infos aus deinen Bildern entfernst, bevor du sie teilst.
                    </p>
                </div>
            </div>

            <div class="subsite">
                <svg class="subsite_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100" height="100">
                    <rect x="3" y="3" width="18" height="18" rx="3" fill="#1E293B" stroke="#334155" stroke-width="1" />
                    <g fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M 12 18.2 L 12 14.2" stroke="#10B981" />
                        <path d="M 10.6 18.2 L 10.6 14.2 A 1.4 1.8 0 0 1 13.4 14.2 L 13.4 16.0" stroke="#10B981" />
                        <path d="M 9.2 18.2 L 9.2 14.2 A 2.8 3.6 0 0 1 14.8 14.2 L 14.8 15.0" stroke="#10B981" /> 
                        <path d="M 7.8 18.2 L 7.8 14.2 A 4.2 5.4 0 0 1 16.2 14.2 L 16.2 14.6" stroke="#475569" />
                        <path d="M 16.2 16.3 L 16.2 18.2" stroke="#475569" />
                        <path d="M 6.4 18.2 L 6.4 14.2 A 5.6 7.2 0 0 1 15.2 8.0" stroke="#475569" />
                        <path d="M 17.6 11.5 L 17.6 16.5" stroke="#475569" />
                        <path d="M 5.0 16.0 L 5.0 14.2 A 7.0 9.0 0 0 1 14.0 5.2" stroke="#475569" />
                    </g>
                </svg>
                <div class="subsite_content">
                    <h2 class="subsite_header">Browser Fingerprinting</h2>
                    <p class="subsite_text">
                        Finde heraus, wie einzigartig dein Browser-Schnittstellen-Profil für Tracker ist. Analysiere, welche Spuren du beim Surfen im Netz hinterlässt.
                    </p>
                </div>
            </div>
    
            <div class="subsite">
                <svg class="subsite_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100" height="100">
                    <rect x="3" y="3" width="18" height="18" rx="3" fill="#1E293B" stroke="#334155" stroke-width="1" />
                    <path d="M 8.5 11.5 L 8.5 8.0 A 3.5 3.5 0 0 1 15.5 8.0 L 15.5 9.2" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <rect x="6.5" y="11.5" width="11" height="7" rx="1.5" fill="#10B981" />
                    <circle cx="12" cy="14.2" r="0.9" fill="#1E293B" />
                    <path d="M 12 14.2 L 12 16.5" stroke="#1E293B" stroke-width="1.2" stroke-linecap="round" />
                </svg>
                <div class="subsite_content">
                    <h2 class="subsite_header">Live Passwort Checker</h2>
                    <p class="subsite_text">
                        Überprüfe die Stärke deiner Passwörter in Echtzeit. Teste sie gegen bekannte Datenlecks, ohne die Sicherheit zu gefährden.
                    </p>
                </div>
            </div>
        </div>     
    </div>
                  
    <?php include './template/footer.php'; ?>

</body>
</html>