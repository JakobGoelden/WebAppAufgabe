<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Passwort Scanner</title>
    <link rel="stylesheet" href="../../style/main.css">
    <link rel="stylesheet" href="../../style/navbar.css">
    

    <script>
   
    function checkLive(str) {
        if (str.length == 0) { 
            document.getElementById("txtHint").innerHTML = "";
            return;
        }
        var xmlhttp = new XMLHttpRequest();     /*code logik um eine antwort zu warten , schickt anfrgaen an den server  */
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {                       /* übund schaut was fertig ist, überschriebt txthint  erwacht dne Zustand  */ 
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("POST", "check.php", true);    /* sendung an check.php */
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("password=" + encodeURIComponent(str));
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
<?php
require_once("../../init.php");
require_once("../../functions.php");
require_once("../../config.php");
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: " . BASE_URL . "auth.php");
    exit; 
}

if (is_mobile()) {
    include '../../template/navbar_mobile.php'; 
} else {
    include '../../template/navbar.php';        
} 

?>
    
<div class="content-narrow">
    <h3>API Test Dashboard (Live-Scanner)</h3>
    <br>

    <div class="input-container">
        <label>Gebe dein Passwort ein:</label><br>
        
        <!-- Das Auge-Feature im Wrapper -->
        <div class="password-wrapper">
            <input type="password" id="passwordField" onkeyup="checkLive(this.value)">
            <span id="toggleIcon" class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
    </div>

    <br>
    <div id="txtHint"></div>  <!-- txtHint als Platzhalter um zu wrten bis der php server was zurück bekommt -->
</div>

</body>
</html>