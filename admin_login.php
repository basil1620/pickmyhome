<?php
session_start();

$name = $_POST['name'];
$password = $_POST['password'];

if ($name === "pickmyhome" && $password === "admin000") {
    $_SESSION['admin_logged_in'] = true; // ✅ Set session
    header("Location: admin_dashboard.php"); // Must be .php to use session
    exit();
} else {
    echo "<script>alert('❌ Invalid admin credentials!'); window.history.back();</script>";
    exit();
}
?>
