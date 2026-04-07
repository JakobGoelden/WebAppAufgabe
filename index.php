<?php
require_once("init.php");
?>
<!DOCTYPE html>
<html lang="en">
<!-- <meta http-equiv="refresh" content="5; URL=http://localhost/test.html" > -->
<head>
    <meta charset="UTF-8">
    <title>Möööööritz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>


    <!--<p style="display: flex; direction: rtl;" ><a class="admin" href="auth.php">Admin</a></p>-->
    <h1>MyProject.local is running on Fedora Apache + PHP</h1>
    <p>Current server time: <strong><span id="Uhr">Laedt...</span></strong></p>


    <p>Try <a class="underline" href="form.php">a PHP form</a> to test dynamic input.</p>
    <p>Try <a class="underline" href="test.php">Subsite</a> to test links</p>
    <br>

    <script src="script.js"></script>
</body>
</html>

