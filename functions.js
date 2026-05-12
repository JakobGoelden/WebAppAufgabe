function handleSuccessfulLogin(targetUrl) {
  const message_shown = document.getElementById('message_shown');
  

  message_shown.classList.remove('message_hidden');
    
    setTimeout(() => {
      window.location.href = targetUrl; 
    }, 200); // wait for 0.2 secs for animation

  }

document.querySelectorAll('.subsite').forEach(item => {
    item.addEventListener('click', function() {
        const target = this.getAttribute('href');
        if (target) {
            window.location.href = window.location.origin + "/webapp/" + target.replace(/^\//, "");
        }
    });
});