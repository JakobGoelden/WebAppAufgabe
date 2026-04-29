<?php
// init.php - Wird auf jeder Seite ganz oben eingebunden

// Session starten, falls noch nicht passiert
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zentrale Variablen definieren
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;
$username = $is_logged_in ? $_SESSION['username'] : null;

