<?php

function callApi($email) {

    $apiKey = "Dein_API_Key"; // Ersetze dies durch deinen tatsächlichen API-Schlüssel
    $url = "https://haveibeenpwned.com/api/v3/breachedaccount/" .urlencode($email);



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $headers = [
        "hibp-api-key: $apiKey",
        "User-Agent: YourAppName" // Ersetze "YourAppName" durch den Namen deiner Anwendung
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;

}












?>


