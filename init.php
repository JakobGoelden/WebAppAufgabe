<?php
// Falls die Session noch nicht gestartet wurde, starten wir sie hier zentral
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generiert EINEN globalen Token für die gesamte Sitzung des Users
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Zentrale Variablen definieren
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;
$username = $is_logged_in ? $_SESSION['username'] : null;

$timeout_duration = 1800; // 1800 Sekunden = 30 Minuten


if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
   
    session_unset();     
    session_destroy();   
    
    header("Location: auth.php"); 
    exit;
}

// Zeitstempel der letzten Aktivität bei jedem Seitenaufruf aktualisieren
$_SESSION['last_activity'] = time();
// -----------------------------------------------------------

// CSRF Token generieren
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}