<?php
session_start();
session_unset();
session_destroy();

// Prevent caching of logged-in pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

header("Location: index.html"); // Redirect to login page
exit();
?>
