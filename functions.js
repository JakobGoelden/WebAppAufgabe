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
setTimeout(function() {
    document.getElementById('p').style.display = 'block'; // Zeige Warnbalken

    setTimeout(function() {
        window.location.href = 'admin_logout.php'; // Logout nach Y Sekunden
    }, 30000); // 30 Sekunden
    
}, 60000); // 10 Minuten (600.000 ms)
