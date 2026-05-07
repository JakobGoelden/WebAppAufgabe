<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


session_set_cookie_params(0);

// Session starten (falls noch nicht passiert)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout_duration = 1800; 


if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    
    session_unset();    
    session_destroy();   

    header("Location: auth.php"); 
    exit;
}


$_SESSION['last_activity'] = time();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}