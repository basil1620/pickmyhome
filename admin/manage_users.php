<?php
session_start();
include '../db/connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.html");
    exit();
}

// Optional: Delete user
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_stmt->bind_param("i", $user_id);
    $delete_stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Fetch users
$users = $conn->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #dc0000ff;
            --secondary: #FFE66D;
            --success: #4ECDC4;
            --danger: #FF6B6B;
            --warning: #FFBE0B;
            --light: #F7FFF7;
            --dark: #292F36;
            --gray: #6C757D;
            --accent: #843e20ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            padding: 40px;
            background: linear-gradient(135deg, #ada7a4b2 0%, #ec220cff 100%);
            min-height: 100vh;
        }
        
        h1 {
            text-align: center;
            color: var(--dark);
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
            animation: fadeInDown 0.6s ease-out;
        }
        
        h1::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
        }
        
        .back-btn {
            position: absolute;
            right: 40px;
            top: 30px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px) translateX(-50%);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateX(-50%);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        th, td {
            padding: 15px;
            border: 1px solid #EDEDED;
            text-align: left;
            transition: all 0.3s ease;
        }
        
        th {
            background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        tr:nth-child(even) {
            background-color: #F8F9FA;
        }
        
        tr:hover {
            background-color: #F1F3F5;
            transform: scale(1.005);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        
        .delete-btn {
            background: linear-gradient(135deg, var(--danger) 0%, #D62839 100%);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .delete-btn:hover {
            background: linear-gradient(135deg, #D62839 0%, #BA324F 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<a href="../admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<h1>MANAGE USERS</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                    <button class="delete-btn">Delete</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    // Add animation delay to table rows
    document.querySelectorAll('tbody tr').forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
    });
</script>

</body>
</html>