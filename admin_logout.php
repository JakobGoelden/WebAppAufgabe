<?php
//need to start session to handle cookies
session_start();

//delete session variables
// $_SESSION = array(); could be used too
session_unset();

//kill session and declare faulty session cookie (or delete) in browser
session_destroy();

//redirect to login page
header("Location: index.php");
exit;
?>