<?php
// Start session
session_start();

// 1. Clear session variables
$_SESSION = [];
session_unset();
session_destroy();

// 2. Clear session cookie if used
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Prevent browser caching after logout
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 4. (Optional) Clear any extra cookies used
setcookie("user", "", time() - 3600, "/");
setcookie("role", "", time() - 3600, "/");

// 5. Redirect to home/login page (edit if needed)
header("Location: index.html");
exit;
?>
