<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.html"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Pick My Home</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }
    body {
      background: #f4f7f8;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    header {
      background: #2c3e50;
      color: white;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      margin-bottom: 5px;
      font-size: 28px;
    }
    .back-home {
      padding: 10px 15px;
      background: #e74c3c;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      transition: 0.3s ease;
    }
    .back-home:hover {
      background: #c0392b;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      padding: 40px;
      flex: 1;
    }
    .card {
      background: white;
      padding: 25px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
    .card h2 {
      font-size: 20px;
      margin-bottom: 15px;
      color: #2c3e50;
    }
    .card a {
      display: inline-block;
      padding: 10px 18px;
      background: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.3s;
    }
    .card a:hover {
      background: #2e86c1;
    }
    footer {
      text-align: center;
      padding: 20px;
      background: #ecf0f1;
      font-size: 14px;
      color: #777;
    }
  </style>
</head>
<body>
  <header>
    <div>
      <h1>Admin Dashboard</h1>
      <p>Welcome, Admin! Manage the Pick My Home platform.</p>
    </div>
    <a href="admin_logout.php" class="back-home">⬅ Logout</a>
  </header>

  <section class="dashboard">
    <div class="card">
      <h2>Manage Users</h2>
      <a href="admin/manage_users.php">Go to Users</a>
    </div>
    <div class="card">
      <h2>Manage House Listings</h2>
      <a href="admin/manage_houses.php">Go to Houses</a>
    </div>
    <div class="card">
      <h2>Manage Flat Listings</h2>
      <a href="admin/manage_flats.php">Go to Flats</a>
    </div>
    <div class="card">
      <h2>Respond to Inquiries</h2>
      <a href="admin/handle_requests.php">View Requests</a>
    </div>
    <div class="card">
      <h2>Manage Testimonials</h2>
      <a href="admin/moderate_testimonials.php">Moderate</a>
    </div>
    <div class="card">
      <h2>Manage Payments</h2>
      <a href="admin/view_payments.php">Payment Records</a>
    </div>
  </section>

  <footer>
    &copy; 2025 Pick My Home. All rights reserved.
  </footer>
</body>
</html>
