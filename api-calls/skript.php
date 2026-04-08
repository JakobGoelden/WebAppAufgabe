<?php
function sendApiRequest($email, $apiKey, $baseUrl) {

    $url = $baseUrl . urlencode($email);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $headers = [

        "hibp-api-key: $apiKey",
        "user-agent: MyCustomApp"

    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode === 200) {

        return json_decode($response, true);
    }

    elseif ($httpCode === 404){

        return [];

    } 
    
    else {

        return "Error: " . $httpCode;
    }
}

?>

