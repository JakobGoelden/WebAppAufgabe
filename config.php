<?php
require_once("init.php");
// db inputs hardcoded. will be moved at the end
$servername = "localhost";
$username_db   = "root";
$password_db   = "";
$dbname     = "users";

$login_success = false;
$redirect_url = '';

// build db connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

