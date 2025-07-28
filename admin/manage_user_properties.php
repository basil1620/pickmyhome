<?php
session_start();
include '../db/connection.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.html");
    exit();
}

// Verify property
if (isset($_GET['verify']) && is_numeric($_GET['verify'])) {
    $property_id = $_GET['verify'];
    $stmt = $conn->prepare("UPDATE user_submitted_properties SET status = 'Verified', decline_reason = NULL WHERE id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    header("Location: manage_user_properties.php");
    exit();
}

// Decline property
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['decline_id'], $_POST['reason'])) {
    $property_id = $_POST['decline_id'];
    $reason = trim($_POST['reason']);

    $stmt = $conn->prepare("UPDATE user_submitted_properties SET status = 'Declined', decline_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $property_id);
    $stmt->execute();
    header("Location: manage_user_properties.php");
    exit();
}

$query = "SELECT u.name, p.* FROM user_submitted_properties p JOIN users u ON p.user_id = u.id ORDER BY p.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Property Submissions</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #771010c9;
            --secondary: #FFE66D;
            --success: #4ECDC4;
            --danger: #FF6B6B;
            --warning: #FFBE0B;
            --light: #F7FFF7;
            --dark: #292F36;
            --gray: #000000ff;
            --accent: #ea0c00ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            padding: 40px;
            background: linear-gradient(135deg, #e9915eff 0%, #022f5c51 100%);
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        th, td {
            padding: 15px;
            border: 1px solid #fffdfdff;
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
            background-color: #ffffffff;
        }
        
        tr:hover {
            background-color: #F1F3F5;
            transform: scale(1.005);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .verify-btn { 
            background: linear-gradient(135deg, var(--success) 0%, #1A936F 100%);
        }
        
        .verify-btn:hover {
            background: linear-gradient(135deg, #1A936F 0%, #114B5F 100%);
        }
        
        .verify-btn:disabled { 
            background: #000000ff
            cursor: not-allowed;
        }
         .btn:disabled {
            background: #ff8260ff; /* New color - Bootstrap's secondary gray */
            color: white;
            opacity: 0.8;
            cursor: not-allowed;
        }
        
        .decline-btn { 
            background: linear-gradient(135deg, var(--danger) 0%, #D62839 100%);
            margin-left: 8px;
        }
        
        .decline-btn:hover {
            background: linear-gradient(135deg, #D62839 0%, #BA324F 100%);
        }
        
        .decline-form {
            display: none;
            margin-top: 10px;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .decline-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            resize: vertical;
            min-height: 80px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        
        .decline-form textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(107, 75, 255, 0.2);
            outline: none;
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
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        }
        
        .status-verified {
            color: #1A936F;
            font-weight: 500;
        }
        
        .status-declined {
            color: #D62839;
            font-weight: 500;
        }
        
        .status-pending {
            color: #FF9500;
            font-weight: 500;
        }
    </style>
</head>
<body>

<a href="../admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
<h1>User Property Submissions</h1>

<table>
    <tr>
        <th>User</th>
        <th>Title</th>
        <th>Type</th>
        <th>Location</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['property_type']) ?></td>
            <td><?= htmlspecialchars($row['district']) ?>, <?= htmlspecialchars($row['city']) ?></td>
            <td>₹<?= htmlspecialchars($row['price']) ?></td>
            <td class="status-<?= strtolower(str_replace(' ', '-', $row['status'])) ?>"><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <?php if ($row['status'] === 'Not Verified'): ?>
                    <a href="?verify=<?= $row['id'] ?>"><button class="btn verify-btn">Verify</button></a>
                    <button class="btn decline-btn" onclick="showDeclineForm(<?= $row['id'] ?>)">Decline</button>

                    <form method="POST" class="decline-form" id="decline-form-<?= $row['id'] ?>">
                        <input type="hidden" name="decline_id" value="<?= $row['id'] ?>">
                        <textarea name="reason" placeholder="Reason for decline" required></textarea><br>
                        <button type="submit" class="btn decline-btn" style="margin-top:5px;">Submit</button>
                    </form>
                <?php else: ?>
                    <button class="btn" disabled><?= $row['status'] ?></button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
    function showDeclineForm(id) {
        const form = document.getElementById('decline-form-' + id);
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }
</script>

</body>
</html>