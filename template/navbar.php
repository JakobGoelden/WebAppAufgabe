<?php

?>
<link rel="stylesheet" href="./style/navbar.css">
<script src="../functions.js"></script>
<nav class="navbar">
    <div class="nav-subsite">
        <a href="../../index.php">WebAppName</a>
    </div>

    <div class="nav-subsite">
        <a href="../../include/metadata_stripping/metadata_stripping.php">Metadaten entfernen</a>
    </div>

    <div class="nav-subsite">
        <a href="../../include/fingerprinting/info.php">Fingerprinting</a>
    </div>

    <div class="nav-subsite">
        <a href="../../include/api-calls/skript.php">Passwort Checker</a>
    </div>

    <div class="nav-subsite">
        <a href="index.php">Subsite4</a>
    </div>
    <!-- different mode if logged in or logged out -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <a href="auth.php" class="nav-button">Mein Bereich</a>
    <?php else: ?>
        <a href="auth.php" class="nav-button">Login</a>
    <?php endif; ?> 
    
</nav>

<div class="cyber-background">
    <div class="glow-box box-1"></div>
    <div class="glow-box box-2"></div>
    <div class="glow-box box-3"></div>
    <div class="glow-box box-4"></div>
    <div class="glow-box box-5"></div>
</div>