<?php
function sendApiRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); #schater ob ankommt
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $headers = ["user-agent: Security Guard"];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);      #fragt nach erfolg
    curl_close($ch);                            #schliest session 
    return ['code' => $httpCode, 'data' => $response];
}
#cUrl int() startet die session 
#-> 
#ich nutzte cUrl  $ch um Daten von einer anderen Website aubzurufen 
# CURLOPT_RETURNTRANSFER sorgt für speicherung als string 
# timeut verhindert das die Seite einfriert, falls api request langsamer ist 






function checkPasswordLeak($password) {
    $hash = strtoupper(sha1($password));
    $prefix = substr($hash, 0, 5);
    $suffix = substr($hash, 5);
    $url = "https://api.pwnedpasswords.com/range/" . $prefix;
    $result = sendApiRequest($url);
    
    if ($result['code'] === 200) {
        if (strpos($result['data'], $suffix) !== false) {
            return "GEFUNDEN: Das Passwort ist unsicher!";
        } else {
            return "SICHER: Kein Leak gefunden.";
        }
    }
    return "Check läuft...";

        #   bereitet die daten vor, dann geht es in die sendapi funktion 
    #passwort wird gehashed 
    #passwort wird nur gehashed als api call geschickt
    # dann werden die gelakten Password, welche mit dem Hash angefangen zurückgegbeben , nur die ersten 5 zeichen werden genommen 
    # respone ist dann die liste mit den suffixen #
    # lokal wird dann mit strpos($result) verglichen 
    # substr extrahiert ein teil string aus meinem url , nur bis position 5 


}

if (isset($_POST['password'])) {
    $userInput = $_POST['password'];
    #form box abfang

    if (!empty($userInput)) {
        
        $leakErgebnis = checkPasswordLeak($userInput);
        $isLeaked = (strpos($leakErgebnis, 'GEFUNDEN') !== false);

        
        $length  = strlen($userInput) >= 8;
        $upper   = preg_match('/[A-Z]/', $userInput);
        $lower   = preg_match('/[a-z]/', $userInput);
        $number  = preg_match('/[0-9]/', $userInput);
        $special = preg_match('/[@$!%*?&]/', $userInput);

      #jeder Operator speichert true oder false und wird dann verglichen 
      # dynamisches bearbeiten der eingabe 
        $allCriteriaMet = ($length && $upper && $lower && $number && $special);

        $cGreen = "#4CAF50";
        $cRed   = "#f44336";

       
        $leakBoxColor = $isLeaked ? $cRed : $cGreen;
        echo "<div style='padding: 15px; margin-bottom: 10px; border: 1px solid white; color: white; background: rgba(0,0,0,0.3); border-left: 10px solid $leakBoxColor;'>";
        echo "<strong>Datenbank-Check (Leaks):</strong><br>";
        echo "<span style='color: $leakBoxColor;'>$leakErgebnis</span>";
        echo "</div>";
    #tenärer operator
       
        $strengthBoxColor = $allCriteriaMet ? $cGreen : $cRed;
        echo "<div style='padding: 15px; border: 1px solid white; color: white; background: rgba(0,0,0,0.3); border-left: 10px solid $strengthBoxColor;'>";
        echo "<strong>Passwort-Stärke (Anforderungen):</strong><br><br>";
        
        echo "<span style='color: " . ($length ? $cGreen : $cRed) . ";'>● Mindestens 8 Zeichen</span><br>";
        echo "<span style='color: " . ($upper ? $cGreen : $cRed) . ";'>● Großbuchstaben</span><br>";
        echo "<span style='color: " . ($lower ? $cGreen : $cRed) . ";'>● Kleinbuchstaben</span><br>";
        echo "<span style='color: " . ($number ? $cGreen : $cRed) . ";'>● Zahlen</span><br>";
        echo "<span style='color: " . ($special ? $cGreen : $cRed) . ";'>● Sonderzeichen (@$!%*?&)</span>";
        echo "</div>";
    }
    # formatierungen und nutzten vin ternären operatoren um es dynamisch zu gestalten 
}
?>