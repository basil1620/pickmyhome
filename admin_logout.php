<?php
// Start session
session_start();

// Clear admin session flag
unset($_SESSION['admin_logged_in']);
$_SESSION = [];
session_unset();
session_destroy();

// Prevent caching (to block back-button access)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Optional: Clear session cookie if needed
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Show logout success message and redirect to login page
echo "<script>
    alert('✅ Successfully logged out!');
    window.location.href = 'index.html';
</script>";
exit();
?>
