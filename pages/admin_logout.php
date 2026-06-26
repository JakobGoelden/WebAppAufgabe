<?php
require_once __DIR__ . '/../includes/init.php';
//delete session variables
session_unset();

//kill session and declare faulty session cookie (or delete) in browser
session_destroy();

//redirect to login page
header("Location: " . BASE_URL . "index.php");
exit;
?>