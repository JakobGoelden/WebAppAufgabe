<?php
require_once("init.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WebApp Projekt</title>
    <link rel="stylesheet" href="./style/main.css">
</head>

<script src="functions.js"></script>

<body>
    <?php include './template/navbar.php'; ?>

    <div class="content">

        <h1>Lorem ipsum dolor sit amet</h1>
        <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat.</h2>
        
        <div name="Links" class="container">
            <div>
                <div name="Metadata" class="subsite" href="/include/metadata_stripping/metadata_stripping.php"></div>
            </div>
            <div>
                <div name="feature2" class="subsite"></div>
            </div>
            <div>
                <div name="feature3" class="subsite"></div>
            </div>
            <div>
                <div name="feature4" class="subsite"></div>
            </div>
        </div>
    </div>
                  
    <?php include './template/footer.php'; ?>
</body>
</html>