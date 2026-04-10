<?php
require_once("init.php");?>
<p><?php echo $is_logged_in; ?></p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WebApp Projekt</title>
    <link rel="stylesheet" href="./style/main.css">
</head>
<body>
    <?php include './template/navbar.php'; ?>

    <div name="Titel">
        <h1>Titel</h1>
    </div>

    <div name="Links" class="container">
        <div>
            <div name="feature1" class="subsite"></div>
        </div>
        <div>
            <div name="feature2" class="subsite"></div>
        </div>
        <div>
            <div name="feature1" class="subsite"></div>
        </div>
        <div>
            <div name="feature2" class="subsite"></div>
        </div>
        <div>
            <div name="feature1" class="subsite"></div>
        </div>
        <div>
            <div name="feature2" class="subsite"></div>
        </div>
    </div>

    <?php include './template/footer.php'; ?>
</body>
</html>