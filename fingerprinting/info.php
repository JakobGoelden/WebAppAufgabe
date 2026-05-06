<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WebApp Projekt</title>
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/navbar.css">
</head>
<body>
    <?php include '../template/navbar.php'; ?>

    <h1>Browser Fingerprinting</h1>
    <br>

    <!-- ToDo: How far these simple ones already narrow it down -->
    <?php
        echo "User Agent: ";
        echo $_SERVER['HTTP_USER_AGENT'];
        echo "<br>";
        echo "Language: ";
        echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        echo "<br>";
    ?>
    <script>
    document.write("Screen: " + screen.width + "x" + screen.height + "<br>");
    document.write("Timezone: " + Intl.DateTimeFormat().resolvedOptions().timeZone + "<br>");
    document.write("Platform: " + navigator.platform + "<br>");
    document.write("Cookies enabled: " + navigator.cookieEnabled + "<br>");
    document.write("CPU cores: " + navigator.hardwareConcurrency + "<br>");
    document.write("Device memory: " + (navigator.deviceMemory || "unknown") + "<br>");
    document.write("Color depth: " + screen.colorDepth + "<br>");
    document.write("Do Not Track: " + navigator.doNotTrack + "<br>");
    </script>

    <!-- ToDo: Explain why canvas fingerprinting is so bad -->
    <script>
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    ctx.textBaseline = "top";
    ctx.font = "16px Arial";
    ctx.fillText("fingerprint", 10, 10);

    const data = canvas.toDataURL();
    document.write("Canvas fingerprint: " + data.substring(0, 50) + "...<br>");
    </script>

    <!-- ToDo: Tell users, that this is not possible with privacy browsers -->
    <script>
    const canvas = document.createElement("canvas");
    const gl = canvas.getContext("webgl");

    if (gl) {
        document.write("WebGL Vendor: " + gl.getParameter(gl.VENDOR) + "<br>");
        document.write("WebGL Renderer: " + gl.getParameter(gl.RENDERER) + "<br>");
    }
    </script>
</body>