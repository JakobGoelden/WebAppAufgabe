function handleSuccessfulLogin(targetUrl) {
  const message_shown = document.getElementById('message_shown');
  

  message_shown.classList.remove('message_hidden');
    
    setTimeout(() => {
      window.location.href = targetUrl; 
    }, 200); // wait for 0.2 secs for animation

  }