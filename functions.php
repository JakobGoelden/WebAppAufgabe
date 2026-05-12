<?php

/**
 * helper function: checks if an ip address is inside a specific range (cidr).
 * example: is "192.168.1.5" part of "192.168.1.0/24"?
 * @param string $ip    the ip to check (e.g. from the user)
 * @param string $range the allowed range (e.g. cloudflare ip list)
 * @return bool         true if ip is in range, false if not
 */
function ip_in_range($ip, $range) {
    // if range has no slash (e.g. "127.0.0.1"), just check if they are identical
    if (strpos($range, '/') === false) {
        return $ip === $range;
    }

    // split the range into ip and the "mask" (the number after /)
    list($subnet, $bits) = explode('/', $range);

    // convert ips to binary format (computer readable 0s and 1s)
    $ip_binary = inet_pton($ip);
    $subnet_binary = inet_pton($subnet);

    // error check: if conversion failed or ip versions don't match (ipv4 vs ipv6), fail
    if ($ip_binary === false || $subnet_binary === false || strlen($ip_binary) !== strlen($subnet_binary)) {
        return false;
    }

    // calculate how many full bytes we need to compare
    $bytes = $bits / 8;

    // compare the full bytes (the main network part)
    for ($i = 0; $i < floor($bytes); $i++) {
        if ($ip_binary[$i] !== $subnet_binary[$i]) {
            return false;
        }
    }

    // compare the remaining bits (the specific sub-part) if necessary
    $remaining_bits = $bits % 8;
    if ($remaining_bits > 0) {
        $mask = 0xff << (8 - $remaining_bits);
        // bitwise comparison
        if ((ord($ip_binary[$i]) & $mask) !== (ord($subnet_binary[$i]) & $mask)) {
            return false;
        }
    }

    return true; // match found!
}

/**
 * main function: gets the real ip address of the user.
 * it handles proxies and cloudflare securely to prevent ip spoofing.
 * @return string the most secure ip address found
 */
function get_secure_ip() {
    // start with the direct connection ip (always safe, but might be cloudflare's ip)
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // --- configuration ---

    // 1. official cloudflare ip ranges (updated 2024)
    // we trust headers only if the request comes from these ips.
    $cf_ranges = array(
        '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
        '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
        '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
        '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
        // ipv6 ranges
        '2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32',
        '2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32'
    );

    // 2. trusted local proxies (e.g. load balancers)
    // for localhost, we trust 127.0.0.1. add your load balancer ip here later if needed.
    $trusted_proxies = array('127.0.0.1', '::1');

    // --- logic ---

    // scenario a: cloudflare
    // check if the request has the cloudflare header
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $is_cf_server = false;

        // security check: does the request actually come from a cloudflare server?
        // we check if remote_addr is in the official list ($cf_ranges)
        foreach ($cf_ranges as $range) {
            if (ip_in_range($_SERVER['REMOTE_ADDR'], $range)) {
                $is_cf_server = true;
                break; // found it, stop searching
            }
        }

        // only use the header if it really is cloudflare
        if ($is_cf_server) {
            // validate: is the header content really an ip? (prevent code injection)
            if (filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
        }
    }

    // scenario b: standard proxy (load balancer)
    // check if the request comes from a trusted local proxy (like localhost)
    elseif (in_array($_SERVER['REMOTE_ADDR'], $trusted_proxies)) {

        // check for the standard forwarding header
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // this header can contain a list: "clientip, proxy1, proxy2"
            // we want the first one (the client)
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $candidate = trim($ips[0]);

            // validate: is it really an ip?
            if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                return $candidate;
            }
        }
    }

    // scenario c: direct connection (default)
    // if no trusted proxy/cloudflare was found, use the direct ip.
    return $_SERVER['REMOTE_ADDR'];
}
?>