<?php
define('BASE_URL', '/webapp/');
// show errors. kill before going live
ini_set('display_errors', 1);
error_reporting(E_ALL);

// force session cookie to expire when browser closes
session_set_cookie_params(0);

// start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auto logout timeout in seconds (30 mins)
$timeout_duration = 1800; 

// check if user was inactive for too long
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    
    // clear and destroy session
    session_unset();    
    session_destroy();   

    // kick back to login
    header("Location: auth.php"); 
    exit;
}

// update last activity timestamp on every load
$_SESSION['last_activity'] = time();

// generate csrf token if it doesnt exist yet
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}