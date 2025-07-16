<?php
session_start(); // ✅ Start session at the beginning

// Step 1: Connect to the database
include("db/connection.php");

// Step 2: Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Step 3: Get form values and sanitize
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Step 4: Validations

    // Name must contain only letters and spaces, minimum 3 characters
    if (!preg_match("/^[a-zA-Z ]{3,}$/", $name)) {
        echo "<script>alert('❌ Name must only contain letters and spaces (min 3 characters).'); window.history.back();</script>";
        exit();
    }

    // Email must be in valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('❌ Invalid email format!'); window.history.back();</script>";
        exit();
    }

    // Password must be at least 6 characters, with at least one letter and one number
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password)) {
        echo "<script>alert('❌ Password must be at least 6 characters and contain both letters and numbers.'); window.history.back();</script>";
        exit();
    }

    // Password and confirm password must match
    if ($password !== $confirm) {
        echo "<script>alert('❌ Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // Step 5: Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ Email already registered!'); window.history.back();</script>";
        exit();
    }

    // Step 6: Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user'; // default role

    // Step 7: Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // ✅ Auto-login the user with session
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_name'] = $name;

        echo "<script>alert('✅ Registered successfully!'); window.location.href = 'selection_page.php';</script>";
    } else {
        echo "<script>alert('❌ Registration failed! Try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
