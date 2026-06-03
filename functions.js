function handleSuccessfulLogin(targetUrl) {
    const msgDiv = document.getElementById("message_shown");
    if (msgDiv) {
        msgDiv.innerText = "Erfolgreich eingeloggt! Weiterleitung...";
        msgDiv.className = "message_shown"; 
    }
    
    setTimeout(() => {
      window.location.href = targetUrl; 
    }, 1500); 
}

document.querySelectorAll('.subsite').forEach(item => {
    item.addEventListener('click', function() {
        const target = this.getAttribute('href');
        if (target) {
            window.location.href = window.location.origin + "/webapp/" + target.replace(/^\//, "");
        }
    });
});

$(document).ready(function() {
    // Prüft nur, ob das Element im HTML steht. Wenn ja, starte Timer.
    if ($("#timeoutModal").length > 0) {
        startSessionTimers();
    }
});

function startSessionTimers() {
    const warnAfter = 3000; // Test: 3 Sekunden
    const logoutAfter = 10000; // Test: 10 Sekunden

    clearTimeout(window.warningTimeout);
    clearTimeout(window.logoutTimeout);

    // Timer 1: Warnung
    window.warningTimeout = setTimeout(() => {
        if ($("#timeoutModal").length > 0) {
            // HIER ist der Fix: Wir initialisieren und öffnen es gleichzeitig!
            $("#timeoutModal").dialog({
                modal: true,
                width: 400,
                draggable: false, 
                resizable: false, 
                buttons: {
                    "Bleiben": function() {
                        fetch('keep_alive.php'); 
                        $(this).dialog("destroy"); // Reißt das Fenster sauber ab
                        startSessionTimers(); // Startet die 3 Sekunden von vorn
                    },
                    "Ausloggen": function() {
                        window.location.href = 'auth.php?action=logout';
                    }
                }
            });
        }
    }, warnAfter);

    // Timer 2: Rauswurf
    window.logoutTimeout = setTimeout(() => {
        window.location.href = 'auth.php?action=logout';
    }, logoutAfter);
}

document.addEventListener("DOMContentLoaded", function() {
    const hamburgerBtn = document.getElementById("hamburgerBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener("click", function() {
            mobileMenu.classList.toggle("show-menu");
        });
    }
});