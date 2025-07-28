<?php
session_start();

// Optional: prevent caching to avoid back button issues
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pick Mode - Pick My Home</title>
  <style>
    /* Keep all your existing styles */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body {
      min-height: 100vh;
      background: #f5f7fa;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url('welcome.jpg.jpg') no-repeat center center/cover;
      opacity: 1;
      z-index: 0;
    }
    header {
      background: #2c3e50;
      color: white;
      padding: 20px 5%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1;
      position: relative;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      animation: slideDown 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .logo {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 1px;
      color: white;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .logo::before {
      content: "🏡";
      font-size: 32px;
    }
    .header-buttons {
      display: flex;
      gap: 15px;
    }
    .logout, .activities {
      background: #c35902ff;
      border: none;
      padding: 10px 20px;
      color: white;
      border-radius: 50px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .logout:hover, .activities:hover {
      background: #c0392b;
      transform: translateY(-2px);
    }
    h2 {
      margin: 40px 0 20px;
      font-size: 2.5rem;
      color: #ffffff;
      z-index: 1;
      position: relative;
      text-align: center;
      animation: fadeIn 1s ease 0.3s forwards;
    }
    h2::after {
      content: "";
      display: block;
      width: 80px;
      height: 4px;
      background: #3498db;
      margin: 15px auto 0;
      border-radius: 2px;
    }
    .card-container {
      display: flex;
      gap: 40px;
      justify-content: center;
      flex-wrap: wrap;
      z-index: 1;
      position: relative;
      padding: 0 5%;
      margin: 30px 0 80px;
    }
    .option-card {
      background: white;
      padding: 40px 30px;
      width: 350px;
      text-align: center;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: pointer;
      position: relative;
      transform: translateY(50px);
      opacity: 0;
      animation: cardEntry 0.8s ease forwards;
    }
    .option-card:nth-child(1) { animation-delay: 0.4s; }
    .option-card:nth-child(2) { animation-delay: 0.6s; }
    .option-card:nth-child(3) { animation-delay: 0.8s; }
    .option-card h3 { font-size: 1.8rem; margin: 25px 0 20px; color: #2c3e50; font-weight: 600; }
    .option-card p { color: #7f8c8d; font-size: 1rem; margin-bottom: 30px; }
    .option-card a { text-decoration: none; color: inherit; display: block; }
    .emoji { font-size: 70px; margin-bottom: 20px; }
    .btn-explore {
      background: #171736;
      color: white;
      padding: 14px 30px;
      border: none;
      border-radius: 50px;
      font-weight: 500;
      cursor: pointer;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes cardEntry { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    @media (max-width: 768px) {
      .card-container { flex-direction: column; align-items: center; }
      .option-card { width: 90%; max-width: 400px; }
    }
  </style>
</head>
<script>
  if (window.performance && window.performance.navigation.type === 2) {
    window.location.href = 'index.html';
  }
</script>
<body>
  <header>
    <div class="logo">Pick My Home</div>
    <div class="header-buttons">
      <button class="activities" onclick="window.location.href='my_activities.php'">🛒 View My Activities</button>
      <button class="logout" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </header>

  <h2><?php echo htmlspecialchars($user_name); ?>, what are you looking for?</h2>

  <div class="card-container">
    <div class="option-card">
      <a href="build.php">
        <div class="emoji">🏠</div>
        <h3>Buy Our Homes</h3>
        <p>Explore our premium collection of Pick My Home properties - beautifully designed, high-quality homes built by our expert team with attention to every detail.</p>
        <button class="btn-explore">View Homes</button>
      </a>
    </div>
    <div class="option-card">
      <a href="flat.php">
        <div class="emoji">🏢</div>
        <h3>Buy Our Flats</h3>
        <p>Discover our selection of modern apartments featuring premium finishes and amenities, all built to Pick My Home's exacting standards.</p>
        <button class="btn-explore">View Flats</button>
      </a>
    </div>
    <div class="option-card">
      <a href="sell.php">
        <div class="emoji">🔄</div>
        <h3>Sell Your Property</h3>
        <p>Let us renovate and sell your property for maximum value. Our expert team handles everything from design to final sale with guaranteed results.</p>
        <button class="btn-explore">Sell Now</button>
      </a>
    </div>
  </div>
</body>
</html>
