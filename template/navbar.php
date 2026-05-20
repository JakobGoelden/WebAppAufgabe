<?php

?>
<link rel="stylesheet" href="./style/navbar.css">
<nav class="navbar">
    <div class="nav-left">
        <a href="index.php">WebAppName</a>
    </div>

    <div class="nav-subsite">
        <a href="./include/metadata_stripping.php">Metadaten entfernen</a>
    </div>

    <div class="nav-subsite">
        <a href="index.php">Subsite2</a>
    </div>

    <div class="nav-subsite">
        <a href="index.php">Subsite3</a>
    </div>

    <div class="nav-subsite">
        <a href="index.php">Subsite4</a>
    </div>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <a href="admin.php" class="nav-button">System Admin</a>
            <a href="user.php" class="nav-button">Mein Bereich</a>
        <?php else: ?>
            <a href="user.php" class="nav-button">Mein Bereich</a>
        <?php endif; ?>

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