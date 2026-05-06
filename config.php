<?php

?>

<!-- Ersetze deinen fehlerhaften link-Tag durch diesen: -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>


<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
<script>
    $(function() {
      // Testzeiten (X = Zeit bis Dialog, Y = Zeit im Dialog bis Kick)
      const X_MIN = 0.1 * 60 * 1000; 
      const Y_SEK = 30 * 1000;      

      $("#dialog").dialog({
          autoOpen: false,
          modal: true,
          appendTo: "body",
          closeOnEscape: false,
          open: function() {
              console.log("Dialog geöffnet. Logout-Timer läuft...");
              
              // Wichtig: Timer auf einer globalen oder klar definierten Variable
              window.logoutTimer = setTimeout(function() {
                  console.log("Zeit abgelaufen! Logout wird ausgeführt...");
                  window.location.assign('admin_logout.php'); 
              }, Y_SEK);
          },
          buttons: [
              {
                  text: "Ich bin noch da",
                  click: function() {
                      console.log("User ist noch da. Reset...");
                      clearTimeout(window.logoutTimer);
                      $(this).dialog("close");
                      // Nutze href = href statt reload, um sicherzugehen, dass es ein GET-Request ist
                      window.location.href = window.location.pathname; 
                  }
              },
              {
                  text: "Logout",
                  click: function() {
                      console.log("Manueller Logout geklickt.");
                      window.location.assign('admin_logout.php');
                  }
              }
          ]
      });

      // Start-Verzögerung
      setTimeout(function() {
          $("#dialog").dialog("open");
      }, X_MIN);
  })

</script>
<?php endif; ?>

