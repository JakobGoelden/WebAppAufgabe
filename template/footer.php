<?php 
include_once("config.php")
?>
<div>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat.

</div>

<div id="message_shown" class="message_hidden"></div>



<?php if (isset($login_success) && $login_success): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ruft DEINE CSS-Funktion auf, nicht Bootstrap
            handleSuccessfulLogin("<?php echo $redirect_url; ?>");
        });
    </script>
<?php endif; ?>

