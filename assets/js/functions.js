function showMessage(text, type) {
    const msgDiv = document.getElementById("message_banner");
    if (!msgDiv) return;
    msgDiv.innerText = text;
    msgDiv.className = "message-banner" + (type === "error" ? " message-error" : "");
    setTimeout(() => {
        msgDiv.className = "message-banner-hidden";
    }, 4000);
}

function handleSuccessfulLogin(targetUrl) {
    showMessage("Erfolgreich eingeloggt! Weiterleitung...", "success");
    setTimeout(() => {
        window.location.href = targetUrl;
    }, 1500);
}

document.querySelectorAll('.subsite').forEach(item => {
    item.addEventListener('click', function() {
        const target = this.getAttribute('href');
        if (target) {
            var baseUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
            window.location.href = window.location.origin + baseUrl + target.replace(/^\//, "");
        }
    });
});

$(document).ready(function() {
    if ($("#timeoutModal").length > 0) {
        startSessionTimers();
    }
});

function startSessionTimers() {
    const warnAfter = 600000; // 10 Minuten
    const logoutAfter = 720000; // 12 Minuten

    clearTimeout(window.warningTimeout);
    clearTimeout(window.logoutTimeout);

    window.warningTimeout = setTimeout(() => {
        if ($("#timeoutModal").length > 0) {
            $("#timeoutModal").dialog({
                modal: true,
                width: 400,
                draggable: false, 
                resizable: false, 
                buttons: {
                    "Bleiben": function() {
                        fetch(BASE_URL + 'pages/keep_alive.php');
                        $(this).dialog("destroy");
                        startSessionTimers();
                    },
                    "Ausloggen": function() {
                        window.location.href = BASE_URL + 'pages/auth.php?action=logout';
                    }
                }
            });
        }
    }, warnAfter);

    window.logoutTimeout = setTimeout(() => {
        window.location.href = BASE_URL + 'pages/auth.php?action=logout';
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