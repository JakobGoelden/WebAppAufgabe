<?php

/**
 * Helper function: Checks if an IP address is inside a specific range (CIDR).
 * Example: Is "192.168.1.5" part of "192.168.1.0/24"?
 * * @param string $ip    The IP to check (e.g. from the user)
 * @param string $range The allowed range (e.g. Cloudflare IP list)
 * @return bool         True if IP is in range, False if not
 */
function ip_in_range($ip, $range) {
    // If range has no slash (e.g. "127.0.0.1"), just check if they are identical
    if (strpos($range, '/') === false) {
        return $ip === $range;
    }

    // Split the range into IP and the "mask" (the number after /)
    list($subnet, $bits) = explode('/', $range);

    // Convert IPs to binary format (computer readable 0s and 1s)
    $ip_binary = inet_pton($ip);
    $subnet_binary = inet_pton($subnet);

    // Error check: If conversion failed or IP versions don't match (IPv4 vs IPv6), fail
    if ($ip_binary === false || $subnet_binary === false || strlen($ip_binary) !== strlen($subnet_binary)) {
        return false;
    }

    // Calculate how many full bytes we need to compare
    $bytes = $bits / 8;

    // Compare the full bytes (the main network part)
    for ($i = 0; $i < floor($bytes); $i++) {
        if ($ip_binary[$i] !== $subnet_binary[$i]) {
            return false;
        }
    }

    // Compare the remaining bits (the specific sub-part) if necessary
    $remaining_bits = $bits % 8;
    if ($remaining_bits > 0) {
        $mask = 0xff << (8 - $remaining_bits);
        // Bitwise comparison
        if ((ord($ip_binary[$i]) & $mask) !== (ord($subnet_binary[$i]) & $mask)) {
            return false;
        }
    }

    return true; // Match found!
}

/**
 * Main function: Gets the real IP address of the user.
 * It handles Proxies and Cloudflare securely to prevent IP Spoofing.
 * * @return string The most secure IP address found
 */
function get_secure_ip() {
    // Start with the direct connection IP (always safe, but might be Cloudflare's IP)
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // --- CONFIGURATION ---

    // 1. Official Cloudflare IP Ranges (Updated 2024)
    // We trust headers ONLY if the request comes from these IPs.
    $cf_ranges = array(
        '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
        '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
        '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
        '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
        // IPv6 Ranges
        '2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32',
        '2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32'
    );

    // 2. Trusted Local Proxies (e.g. Load Balancers)
    // For Localhost, we trust 127.0.0.1. Add your Load Balancer IP here later if needed.
    $trusted_proxies = array('127.0.0.1', '::1');

    // --- LOGIC ---

    // SCENARIO A: Cloudflare
    // Check if the request has the Cloudflare header
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $is_cf_server = false;

        // SECURITY CHECK: Does the request actually come from a Cloudflare Server?
        // We check if REMOTE_ADDR is in the official list ($cf_ranges)
        foreach ($cf_ranges as $range) {
            if (ip_in_range($_SERVER['REMOTE_ADDR'], $range)) {
                $is_cf_server = true;
                break; // Found it, stop searching
            }
        }

        // Only use the header if it really is Cloudflare
        if ($is_cf_server) {
            // Validate: Is the header content really an IP? (Prevent Code Injection)
            if (filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
        }
    }

    // SCENARIO B: Standard Proxy (Load Balancer)
    // Check if the request comes from a trusted local proxy (like localhost)
    elseif (in_array($_SERVER['REMOTE_ADDR'], $trusted_proxies)) {

        // Check for the standard forwarding header
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // This header can contain a list: "ClientIP, Proxy1, Proxy2"
            // We want the first one (the Client)
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $candidate = trim($ips[0]);

            // Validate: Is it really an IP?
            if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                return $candidate;
            }
        }
    }

    // SCENARIO C: Direct Connection (Default)
    // If no trusted proxy/Cloudflare was found, use the direct IP.
    return $_SERVER['REMOTE_ADDR'];
}
?>