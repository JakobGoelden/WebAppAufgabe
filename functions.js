function handleSuccessfulLogin(targetUrl) {
  const message_shown = document.getElementById('message_shown');
  
  // Hier war noch "toast", ist jetzt korrigiert zu "message_shown"
  message_shown.classList.remove('message_hidden');

  setTimeout(() => {
    
    message_shown.classList.add('message_hidden');
    
    setTimeout(() => {
      window.location.href = targetUrl; // Hier wird die Ziel-URL genutzt
    }, 500); // Wartet noch mal 0,5 Sekunden auf die Animation

  }, 3000); // 3 Sekunden warten
}
/*
function popup() {
    // Einstellungen
    const X_MIN = 0.5 * 60 * 1000; // 10 Minuten bis zum Dialog
    const Y_SEK = 30 * 1000;      // 30 Sekunden bis zum Logout

    // Initialisiere den Dialog
    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        closeOnEscape: false,
        open: function() {
            // Sobald der Dialog aufgeht, startet der Todes-Timer
            window.logoutTimer = setTimeout(function() {
                window.location.href = 'admin_logout.php';
            }, Y_SEK);
        },
        buttons: {
            "Ich bin noch da": function() {
                clearTimeout(window.logoutTimer); // Logout stoppen
                $(this).dialog("close");
                location.reload(); // Seite neu laden, um PHP-Session zu refreshen
            },
            "Logout": function() {
                window.location.href = 'admin_logout.php';
            }
        }
    });

    // Der Inaktivitäts-Timer startet beim Laden der Seite
    setTimeout(function() {
        $("#dialog").dialog("open");
    }, X_MIN);
};
*/