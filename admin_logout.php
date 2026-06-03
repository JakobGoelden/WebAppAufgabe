<?php
require_once("init.php");
//delete session variables
session_unset();

//kill session and declare faulty session cookie (or delete) in browser
session_destroy();

//redirect to login page
header("Location: index.php");
exit;
?>