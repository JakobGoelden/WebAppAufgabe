<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Passwort Scanner</title>
    <link rel="stylesheet" href="../../style/main.css">
    <link rel="stylesheet" href="../../style/navbar.css">
    
    <style>
        /* Container für das Input-Feld und das Auge */
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 1000; /* Damit es immer über dem Input liegt */
            user-select: none;
            font-size: 20px;
        }

        #passwordField {
            padding-right: 40px; /* Platz für das Auge lassen */
        }
    </style>

    <script>
   
    function checkLive(str) {
        if (str.length == 0) { 
            document.getElementById("txtHint").innerHTML = "";
            return;
        }
        var xmlhttp = new XMLHttpRequest();     /*code logik um eine antwort zu warten  */
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("POST", "check.php", true);    /* sendung an check.php */
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("password=" +s encodeURIComponent(str));
    }
    /*nutzten der ajax logik, bei jedem einzelnen Userinput wird die Funktion aufgerufen */ 
    /* xmlhhtprequest nimmt das passwort und schaut bei check.php nach den angaben */
   /* document.getElementById("txtHint").innerHTML macht js das egrbensis auf die seite, live aktualliserung */
    function togglePassword() {
        var pField = document.getElementById("passwordField");
        var pIcon = document.getElementById("toggleIcon");
        
        if (pField.type === "password") {
            pField.type = "text";
            pIcon.innerHTML = "🔒"; 
        } else {
            pField.type = "password";
            pIcon.innerHTML = "👁️"; 
        }
    }
    </script>
</head>
<body>

<?php include '../../template/navbar.php'; ?>
    
<div style="padding: 20px; max-width: 800px; margin: auto;">
    <h3>API Test Dashboard (Live-Scanner)</h3>
    <br>

    <div class="input-container">
        <label>Gebe dein Passwort ein:</label><br>
        
        <!-- Das Auge-Feature im Wrapper -->
        <div class="password-wrapper" style="position: relative; z-index: 999;">
            <input type="password" id="passwordField" onkeyup="checkLive(this.value)" 
                   style="position: relative; padding: 10px; width: 100%; background: #222; color: white; border: 1px solid #444;">
            <span id="toggleIcon" class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
    </div>

    <br>
    <div id="txtHint"></div>
</div>

</body>
</html>