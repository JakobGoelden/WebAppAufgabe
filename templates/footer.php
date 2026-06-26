<?php
include_once __DIR__ . '/../includes/config.php';
?>

<footer class="site-footer">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> GuardX. Alle Rechte vorbehalten.</p>
        <nav class="footer-links">
            <a href="<?= get_url('pages/impressum.php') ?>">Impressum</a>
            <a href="<?= get_url('pages/datenschutz.php') ?>">Datenschutz</a>
            <a href="<?= get_url('pages/agb.php') ?>">AGB</a>
        </nav>
    </div>
</footer>

<div id="message_banner" class="message-banner-hidden"></div>

<?php if (isset($login_success) && $login_success): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            handleSuccessfulLogin("<?php echo $redirect_url; ?>");
        });
    </script>
<?php endif; ?>

</body>
</html>