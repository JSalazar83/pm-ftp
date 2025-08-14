<?php
/**
 * FTP/FTPS Step-by-Step Diagnostic
 * --------------------------------
 * Usage (edit config below) or via CLI:
 *   php ftp_diagnose.php host=ftp.example.com user=myuser pass=mypass port=21 ssl=0 dir=/ remote=upload_test.txt
 *
 * It will:
 * 1) Validate PHP environment (extensions, functions)
 * 2) Resolve DNS and show IPv4/IPv6 results
 * 3) Try plain FTP (or FTPS if ssl=1) with timeout
 * 4) Login and query FEAT/SYST/PWD
 * 5) Try PASV on, list directory, and upload a small test file
 * 6) If PASV fails, try active mode
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

/* ---------- Configuration (override via CLI args) ---------- */
$cfg = [
    'host'   => 'constructora2d.com.mx',
    'user'   => 'constructora2d',
    'pass'   => "7cj4q]6\.8'@W[3zUMR_",
    'port'   => 21,        // 21 for FTP/explicit FTPS, 990 for implicit FTPS (not supported by ext/ftp)
    'ssl'    => 1,         // 0 = FTP, 1 = FTPS (explicit TLS via ftp_ssl_connect)
    'timeout'=> 30,        // seconds
    'dir'    => '/',       // remote directory to test
    'remote' => 'upload_test.txt', // remote test filename
    'size'   => 32 * 1024, // bytes for the temp upload (32 KB)
];

parse_str(implode('&', array_slice($argv ?? [], 1)), $cli);
$cfg = array_merge($cfg, array_intersect_key($cli, $cfg));

/* ---------- Helpers ---------- */
function say($level, $msg) { echo sprintf("[%s] %s\n", $level, $msg); }
function ok($msg) { say(' OK ', $msg); }
function info($msg) { say('INFO', $msg); }
function warn($msg) { say('WARN', $msg); }
function fail($msg) { say('FAIL', $msg); }

function require_ext($ext) {
    if (!extension_loaded($ext)) {
        fail("PHP extension '$ext' is not loaded.");
        exit(1);
    }
}

function dns_lookup($host) {
    $records = @dns_get_record($host, DNS_A + DNS_AAAA);
    if ($records === false || empty($records)) {
        warn("dns_get_record failed or returned empty. Trying gethostbynamel...");
        $ips = @gethostbynamel($host);
        if ($ips) {
            foreach ($ips as $ip) { info("A  record: $ip"); }
        } else {
            fail("DNS resolution failed for $host");
        }
        return;
    }
    foreach ($records as $r) {
        if (isset($r['type'], $r['ip']) && $r['type'] === 'A')      info("A  record: {$r['ip']}");
        if (isset($r['type'], $r['ipv6']) && $r['type'] === 'AAAA') info("AAAA record: {$r['ipv6']}");
    }
}

/* ---------- 1) Environment checks ---------- */
info("PHP: " . PHP_VERSION);
require_ext('ftp');
ok("FTP extension present.");
if ($cfg['ssl']) {
    if (!function_exists('ftp_ssl_connect')) {
        fail("FTPS requested but ftp_ssl_connect() is unavailable. Ensure OpenSSL and ext/ftp with SSL support.");
        exit(1);
    } else {
        ok("FTPS support detected (ftp_ssl_connect available).");
    }
}
if (!function_exists('dns_get_record')) {
    warn("dns_get_record() not available; DNS diagnostics will be limited.");
}

/* ---------- 2) DNS resolution ---------- */
info("Resolving host: {$cfg['host']}");
dns_lookup($cfg['host']);

/* ---------- 3) Connect ---------- */
$connectFn = $cfg['ssl'] ? 'ftp_ssl_connect' : 'ftp_connect';
info(($cfg['ssl'] ? 'FTPS' : 'FTP') . " connect to {$cfg['host']}:{$cfg['port']} (timeout {$cfg['timeout']}s) ...");

$conn = @$connectFn($cfg['host'], (int)$cfg['port'], (int)$cfg['timeout']);
if (!$conn) {
    fail("php_connect_nonb() / connect step failed. Common causes: firewall, wrong host/port, IPv6 issues, or FTPS required.");
    fail("Try: forcing IPv4 (use the A record IP), checking outbound port 21, and testing with an external FTP client.");
    exit(1);
}
ok("Connected.");

/* ---------- 4) Login ---------- */
info("Logging in as '{$cfg['user']}' ...");
$loggedIn = @ftp_login($conn, (string)$cfg['user'], (string)$cfg['pass']);

if (!$loggedIn) {
    fail("Login failed. Verify username/password and whether the server requires FTPS.");
    @ftp_close($conn);
    exit(1);
}
ok("Logged in.");

/* Optional: set timeout for subsequent operations if available */
if (function_exists('ftp_set_option')) {
    @ftp_set_option($conn, FTP_TIMEOUT_SEC, (int)$cfg['timeout']);
}

/* Capabilities */
@ftp_raw($conn, 'FEAT');
$feat = @ftp_raw($conn, 'FEAT');
if ($feat) {
    info("Server FEAT:");
    foreach ($feat as $line) echo "      $line\n";
}
$syst = @ftp_systype($conn);
if ($syst) info("SYST: $syst");

$pwd = @ftp_pwd($conn);
if ($pwd !== false) info("PWD: $pwd");

/* ---------- 5) Change to target directory ---------- */
info("Changing directory to '{$cfg['dir']}' ...");
if (!@ftp_chdir($conn, (string)$cfg['dir'])) {
    warn("Could not change to '{$cfg['dir']}'. Will attempt operations in '$pwd' instead.");
}

/* ---------- 6) Try PASV first ---------- */
info("Enabling passive mode ...");
if (!@ftp_pasv($conn, true)) {
    warn("Could not enable passive mode (ftp_pasv returned false). Will still attempt.");
} else {
    ok("Passive mode enabled.");
}

/* List */
info("Listing directory (PASV) ...");
$nlst = @ftp_nlist($conn, '.');
if ($nlst === false) {
    warn("Directory listing failed in PASV. This often indicates firewall/NAT issues with the passive port range.");
} else {
    ok("Directory listing succeeded (PASV). Showing up to 10 entries:");
    foreach (array_slice($nlst, 0, 10) as $i => $name) echo "      [$i] $name\n";
}

/* Upload test (PASV) */
$tmpFile = tempnam(sys_get_temp_dir(), 'ftp_');
$bytes = max(1024, (int)$cfg['size']);
$payload = str_repeat("X", $bytes);
file_put_contents($tmpFile, $payload);
info("Uploading test file (~{$bytes} bytes) to '{$cfg['remote']}' (PASV) ...");
$put = @ftp_put($conn, (string)$cfg['remote'], $tmpFile, FTP_BINARY);
if ($put) {
    ok("Upload succeeded in PASV mode.");
    // Clean up remote file to avoid clutter (comment out if you want it left there)
    @ftp_delete($conn, (string)$cfg['remote']);
} else {
    warn("Upload failed in PASV mode. Will try ACTIVE mode next.");
}

/* ---------- 7) Try ACTIVE mode if needed ---------- */
if (!$put || $nlst === false) {
    info("Switching to ACTIVE mode ...");
    if (!@ftp_pasv($conn, false)) {
        warn("Could not disable passive mode (ACTIVE).");
    } else {
        ok("Active mode set.");
    }

    info("Listing directory (ACTIVE) ...");
    $nlstA = @ftp_nlist($conn, '.');
    if ($nlstA === false) {
        warn("Directory listing failed in ACTIVE mode too.");
    } else {
        ok("Directory listing succeeded (ACTIVE). Showing up to 10 entries:");
        foreach (array_slice($nlstA, 0, 10) as $i => $name) echo "      [$i] $name\n";
    }

    info("Uploading test file (ACTIVE) ...");
    $putA = @ftp_put($conn, (string)$cfg['remote'], $tmpFile, FTP_BINARY);
    if ($putA) {
        ok("Upload succeeded in ACTIVE mode.");
        @ftp_delete($conn, (string)$cfg['remote']);
    } else {
        fail("Upload failed in ACTIVE mode too.");
        fail("Likely causes: firewall blocking passive range (PASV) and/or data connections (ACTIVE), NAT issues, or server requires FTPS.");
    }
}

/* ---------- Cleanup ---------- */
@unlink($tmpFile);
@ftp_close($conn);
ok("Done.");
