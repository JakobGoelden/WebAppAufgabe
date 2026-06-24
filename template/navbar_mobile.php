<?php

?>
<link rel="stylesheet" href="<?= get_url('style/navbar.css') ?>">
<script>var BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script src="<?= get_url('functions.js') ?>"></script>
<nav class="mobile-navbar">
    <div class="navbar-mobile-wrapper">
    
        <div class="navbar-mobile-header">
                <div class="nav-subsite" id="GuardX">
                    <a href="<?= get_url('index.php') ?>">GuardX</a>
                </div>
            
            <button id="hamburgerBtn" class="hamburger-icon">☰</button>
        </div>

        <div id="mobileMenu" class="mobile-links">
            
            <div class="nav-subsite">
                <a href="<?= get_url('include/metadata_stripping/metadata_stripping.php') ?>">Metadaten entfernen</a>
            </div>

            <div class="nav-subsite">
                <a href="<?= get_url('include/fingerprinting/info.php') ?>">Fingerprinting</a>
            </div>

            <div class="nav-subsite">
                <a href="<?= get_url('include/api-calls/skript.php') ?>">Passwort Checker</a>
            </div>
            

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="<?= get_url('admin.php') ?>" class="nav-button">System Admin</a>
                <?php endif; ?>
                
                <a href="<?= get_url('user.php') ?>" class="nav-button">Mein Bereich</a>
                <a href="<?= get_url('auth.php?action=logout') ?>" class="nav-button nav-button-logout">Logout</a>
                
            <?php else: ?>
                
                <a href="<?= get_url('auth.php') ?>" class="nav-button">Login</a>
                
            <?php endif; ?>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.getElementById("hamburgerBtn");
            const menu = document.getElementById("mobileMenu");

            if (btn && menu) {
                btn.addEventListener("click", function() {
                    menu.classList.toggle("show-menu");
                });
            }
        });
    </script>
    
</nav>

<div class="cyber-background">
    <div class="glow-box box-1"></div>
    <div class="glow-box box-2"></div>
    <div class="glow-box box-3"></div>
    <div class="glow-box box-4"></div>
    <div class="glow-box box-5"></div>
</div>