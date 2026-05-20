<?php
define('BASE_URL', '/webapp/');
// show errors. kill before going live
ini_set('display_errors', 1);
error_reporting(E_ALL);

// force session cookie to expire when browser closes
session_set_cookie_params(0);

// start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auto logout timeout in seconds (30 mins)
$timeout_duration = 1800; 

// check if user was inactive for too long
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    
    // clear and destroy session
    session_unset();    
    session_destroy();   

    // kick back to login
    header("Location: auth.php"); 
    exit;
}

// update last activity timestamp on every load
$_SESSION['last_activity'] = time();

// generate csrf token if it doesnt exist yet
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!-- load jquery and jquery ui for the timeout dialog -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
<script>
    $(function() {
      // times. x = time until warning, y = time until auto logout
      const X_MIN = 0.5 * 60 * 1000; 
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