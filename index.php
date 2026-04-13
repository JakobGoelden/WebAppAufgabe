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

    <div class="cyber-background">
        <div class="glow-box box-1"></div>
        <div class="glow-box box-2"></div>
        <div class="glow-box box-3"></div>
        <div class="glow-box box-4"></div>
        <div class="glow-box box-5"></div>
    </div>

    <div class="content">
                <h1>Placeholder</h1>
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

    <div name="Links" class="container">
        <div name="feature1" class="subsite"></div>
        <div name="feature2" class="subsite"></div>
    </div>
                  
        <div class="content">
                <h1>Placeholder</h1>
                <p>Try <a class="underline">a PHP form</a> to test dynamic input.</p>
                <p>Try <a class="underline">Subsite</a> to test links</p>
        </div>
    

    <?php include './template/footer.php'; ?>
</body>
</html>