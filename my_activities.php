<?php
session_start();
include("db/connection.php");

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch properties submitted by the logged-in user
$query = $conn->prepare("SELECT * FROM user_submitted_properties WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Activities - Pick My Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
    :root {
      --primary: #ff3700ca;
      --secondary: #0026ff93;
      --accent: #ff4000ff;
      --dark: #ff0000ff;
      --light: #f8f9fa;
      --success: #0c4bd44a;
      --warning: #f72585;
      --danger: #f8961e;
      --info: #7209b7;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    header {
      background: linear-gradient(to right, var(--dark), var(--secondary));
      color: white;
      padding: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }

    header::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(to right, var(--accent), var(--success));
      animation: rainbow 8s linear infinite;
    }

    @keyframes rainbow {
      0% {background-position: 0% 50%;}
      50% {background-position: 100% 50%;}
      100% {background-position: 0% 50%;}
    }

    h1 {
      font-size: 24px;
      font-weight: 600;
      margin: 0;
      animation: fadeInDown 0.8s;
    }

    .back-btn {
      background: var(--accent);
      padding: 12px 24px;
      color: white;
      text-decoration: none;
      border-radius: 30px;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      animation: fadeInRight 0.8s;
    }

    .back-btn:hover {
      background: var(--secondary);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .container {
      max-width: 900px;
      margin: 30px auto;
      padding: 30px;
      background: white;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      border-radius: 15px;
      animation: fadeIn 0.6s ease-out;
      transition: transform 0.3s;
    }

    .container:hover {
      transform: translateY(-5px);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      animation: fadeInUp 0.8s;
    }

    th, td {
      border: 1px solid #e0e0e0;
      padding: 15px;
      text-align: left;
      transition: all 0.2s;
    }

    th {
      background-color: var(--primary);
      color: white;
      font-weight: 500;
      position: sticky;
      top: 0;
    }

    tr:nth-child(even) {
      background-color: #f8fafc;
    }

    tr:hover {
      background-color: #f1f5f9;
      transform: scale(1.01);
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    td:hover {
      background-color: #e9ecef;
    }

    .verified {
      color: #2ecc71;
      font-weight: 600;
      position: relative;
      padding-left: 20px;
    }

    .verified::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: #2ecc71;
      animation: pulse 2s infinite;
    }

    .not-verified {
      color: #e74c3c;
      font-weight: 600;
      position: relative;
      padding-left: 20px;
    }

    .not-verified::before {
      content: '!';
      position: absolute;
      left: 0;
      color: #e74c3c;
    }

    .declined {
      color: var(--danger);
      font-weight: 600;
      position: relative;
      padding-left: 20px;
    }

    .declined::before {
      content: '✗';
      position: absolute;
      left: 0;
      color: var(--danger);
    }

    .decline-message {
      background-color: #fff5f5;
      padding: 12px;
      margin-top: 8px;
      border-left: 4px solid var(--danger);
      font-size: 14px;
      color: #333;
      border-radius: 0 4px 4px 0;
      animation: fadeIn 0.5s;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    h2 {
      color: var(--dark);
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
    }

    h2::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50px;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
    }

    p {
      color: #666;
      font-size: 16px;
      animation: fadeIn 0.8s;
    }
  </style>
</head>
<body>

<header>
  <h1>Welcome, <?php echo htmlspecialchars($user_name); ?> — Your Property Activities</h1>
  <a href="selection_page.php" class="back-btn">⬅ Back to Selection Page</a>
</header>

<div class="container">
  <h2>Submitted Properties</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>Title</th>
        <th>Property Type</th>
        <th>District</th>
        <th>City</th>
        <th>BHK</th>
        <th>Price</th>
        <th>Status</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr class="animate__animated animate__fadeIn">
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['property_type']); ?></td>
          <td><?php echo htmlspecialchars($row['district']); ?></td>
          <td><?php echo htmlspecialchars($row['city']); ?></td>
          <td><?php echo htmlspecialchars($row['bhk']); ?></td>
          <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
          <td class="<?php 
            echo $row['status'] === 'Verified' ? 'verified' : 
                 ($row['status'] === 'Declined' ? 'declined' : 'not-verified'); 
          ?>">
            <?php echo htmlspecialchars($row['status']); ?>
            <?php if ($row['status'] === 'Declined' && !empty($row['decline_reason'])): ?>
              <div class="decline-message">
                <strong>Reason:</strong> <?php echo htmlspecialchars($row['decline_reason']); ?>
              </div>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No properties submitted yet.</p>
  <?php endif; ?>

</div>

<script>
  // Add animation to table rows on hover
  document.querySelectorAll('tr').forEach(row => {
    row.addEventListener('mouseenter', () => {
      row.classList.add('animate__animated', 'animate__pulse');
    });
    row.addEventListener('mouseleave', () => {
      row.classList.remove('animate__animated', 'animate__pulse');
    });
  });
</script>

</body>
</html>