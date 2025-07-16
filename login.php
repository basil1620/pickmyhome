<?php
session_start(); // ✅ Start session at the top
include 'db/connection.php';

$name = $_POST['name'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // ✅ Store session values
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email']; // Optional if needed later
        $_SESSION['user_role'] = $user['role'];   // Optional if you use roles

        // ✅ Redirect to user selection page
        header("Location: selection_page.php");
        exit();
    } else {
        echo "<script>
            alert('Incorrect password.');
            window.location.href = 'index.html';
        </script>";
        exit();
    }
} else {
    echo "<script>
        alert('User not found.');
        window.location.href = 'index.html';
    </script>";
    exit();
}
?>
