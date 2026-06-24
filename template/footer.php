<?php
include_once("config.php");
?>

<footer class="site-footer">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> GuardX. Alle Rechte vorbehalten.</p>
        <nav class="footer-links">
            <a href="<?= get_url('impressum.php') ?>">Impressum</a>
            <a href="<?= get_url('datenschutz.php') ?>">Datenschutz</a>
            <a href="<?= get_url('agb.php') ?>">AGB</a>
        </nav>
    </div>
</footer>

<div id="message_shown" class="message_hidden"></div>

<?php if (isset($login_success) && $login_success): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ruft DEINE CSS-Funktion auf, nicht Bootstrap
            handleSuccessfulLogin("<?php echo $redirect_url; ?>");
        });
    </script>
<?php endif; ?>

</body>
</html>