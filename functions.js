function handleSuccessfulLogin(targetUrl) {
  const message_shown = document.getElementById('message_shown');
  

  message_shown.classList.remove('message_hidden');
    
    setTimeout(() => {
      window.location.href = targetUrl; // Hier wird die Ziel-URL genutzt
    }, 200); // Wartet noch mal 0,2 Sekunden auf die Animation

  }