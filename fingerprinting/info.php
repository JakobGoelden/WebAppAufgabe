<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WebApp Projekt</title>
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/navbar.css">
    <style>
        .block {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .red {
            color: red;
            font-weight: bold;
        }
    </style>

    <script>
        function show(value) {
            document.write(
                "<span style='color:red;font-weight:bold'>" +
                value +
                "</span>"
            );
        }

        const userAgent = <?php echo json_encode($_SERVER['HTTP_USER_AGENT']); ?>;
        const language = <?php echo json_encode($_SERVER['HTTP_ACCEPT_LANGUAGE']); ?>;

        const screenSize = screen.width + "x" + screen.height;
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const cpuCores = navigator.hardwareConcurrency;
        const deviceMemory = navigator.deviceMemory || "unknown";
        const colorDepth = screen.colorDepth;
        const doNotTrack = navigator.doNotTrack;

        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        ctx.textBaseline = "top";
        ctx.font = "16px Arial";
        ctx.fillText("fingerprint", 10, 10);
        const canvasFingerprint = canvas.toDataURL();

        const gl = document.createElement("canvas").getContext("webgl");
        let webglVendor = "Unavailable";
        let webglRenderer = "Unavailable";
        if (gl) {
            webglVendor = gl.getParameter(gl.VENDOR);
            webglRenderer = gl.getParameter(gl.RENDERER);
        }
    </script>
</head>
<body>
    <?php include '../template/navbar.php'; ?>

    <h1>Browser Fingerprinting</h1>
    <br>

    <p>
    Many people think cookies are the main way websites track users online. 
    While cookies are widely used, they are only one part of modern tracking. 
    Cookies can usually be deleted, blocked, or restricted to a single website. 
    Browser fingerprinting is different: it collects information about your device and browser to create a profile that can identify you across many websites, often without storing anything on your computer.
    </p>

    <div class="block">
        <p>
        <b>Highly unique fingerprinting signals:</b><br>
        These are the most powerful tracking methods because they can often uniquely identify your device even without cookies or login data. They rely on subtle hardware and rendering differences.
        </p>

        <p>
        <b>Canvas Fingerprint:</b> (Yours starts with: <script>show(canvasFingerprint.substring(0, 50) + "...");</script>)
        Websites can force your browser to draw invisible graphics. Differences in fonts, GPU, drivers, and system configuration make the result highly unique.
        </p>

        <p>
        <b>WebGL Vendor:</b> (Yours is: <script>show(webglVendor);</script>) and 
        <b>Renderer:</b> (Yours is: <script>show(webglRenderer);</script>)
        WebGL exposes detailed graphics hardware information that can strongly identify your device and GPU setup.
        </p>
    </div>

    <div class="block">
        <p>
        <b>Location-related signals:</b><br>
        These values do not directly reveal your exact address, but they can narrow down your region and help correlate your activity across different websites.
        </p>

        <p>
        <b>Language:</b> (Yours is: <script>show(language);</script>)
        Your browser language setting can reveal your country or preferred region and helps build a regional profile.
        </p>

        <p>
        <b>Timezone:</b> (Yours is: <script>show(timezone);</script>)
        Your timezone often reveals your general geographic location and can be used to link browsing activity across sessions.
        </p>
    </div>

    <div class="block">
        <p>
        <b>Device and technical information:</b><br>
        These values describe your browser and hardware setup. While each value seems harmless alone, together they create a detailed fingerprint of your device.
        </p>

        <p>
        <b>User Agent:</b> (Yours is: <script>show(userAgent);</script>)
        This reveals your browser, operating system, and device type, making it a core part of most tracking systems.
        </p>

        <p>
        <b>Screen Resolution:</b> (Yours is: <script>show(screenSize);</script>)
        Your screen size helps distinguish your device from others and contributes to fingerprint uniqueness.
        </p>

        <p>
        <b>CPU Cores:</b> (Yours is: <script>show(cpuCores);</script>)
        The number of CPU cores gives insight into your hardware performance and device class.
        </p>

        <p>
        <b>Device Memory:</b> (Yours is: <script>show(deviceMemory);</script>)
        Reports your available RAM, helping identify whether you're on a low-end or high-end device.
        </p>

        <p>
        <b>Color Depth:</b> (Yours is: <script>show(colorDepth);</script>)
        A small but still measurable display characteristic used in fingerprinting.
        </p>

        <p>
        <b>Do Not Track:</b> (Yours is: <script>show(doNotTrack);</script>)
        A privacy setting intended to reduce tracking, but most websites ignore it completely.
        </p>
    </div>

    <p>
        None of these values alone are guaranteed to identify you. 
        The danger comes from combining them together. 
        When dozens of small details are collected at once, they can create a fingerprint unique enough to track you across websites, even without cookies or logging into an account.
    </p>
    </script>
</body>