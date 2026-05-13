<?php
require_once("init.php");
?>

<!-- load jquery and jquery ui for the timeout dialog -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>


<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
<script>
    $(function() {
      // times. x = time until warning, y = time until auto logout
      const X_MIN = 15 * 60 * 1000; 
      const Y_SEK = 30 * 1000;      

      $("#dialog").dialog({
          autoOpen: false,
          modal: true,
          appendTo: "body",
          closeOnEscape: false,
          open: function() {
              console.log("dialog opened. logout timer running...");
              
              // store timer globally to clear it later
              window.logoutTimer = setTimeout(function() {
                  console.log("time's up. executing logout...");
                  window.location.assign('admin_logout.php'); 
              }, Y_SEK);
          },
          buttons: [
              {
                  text: "Ich bin noch da",
                  click: function() {
                      console.log("user is active. resetting...");
                      clearTimeout(window.logoutTimer);
                      $(this).dialog("close");
                      // use href to force get request instead of reload
                      window.location.href = window.location.pathname; 
                  }
              },
              {
                  text: "Logout",
                  click: function() {
                      console.log("manual logout clicked.");
                      window.location.assign('admin_logout.php');
                  }
              }
          ]
      });

      // trigger dialog after x_min
      setTimeout(function() {
          $("#dialog").dialog("open");
      }, X_MIN);
  })

</script>
<?php endif; ?>