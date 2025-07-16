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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pick Mode - Pick My Home</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

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
      filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));
    }

    .logout {
      background: #e74c3c;
      border: none;
      padding: 10px 25px;
      color: white;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.4s ease;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .logout:hover {
      background: #c0392b;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .logout::after {
      content: "→";
      font-size: 18px;
      transition: transform 0.3s ease;
    }

    .logout:hover::after {
      transform: translateX(3px);
    }

    h2 {
      margin: 40px 0 20px;
      font-size: 2.8rem;
      color: #ffffff;
      z-index: 1;
      position: relative;
      text-align: center;
      opacity: 0;
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
      border: 1px solid rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
      transform: translateY(50px);
      opacity: 0;
      animation: cardEntry 0.8s ease forwards;
    }

    .option-card:nth-child(1) { animation-delay: 0.4s; }
    .option-card:nth-child(2) { animation-delay: 0.6s; }
    .option-card:nth-child(3) { animation-delay: 0.8s; }

    .option-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: #3498db;
    }

    .option-card:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .option-card h3 {
      font-size: 1.8rem;
      margin: 25px 0 20px;
      color: #2c3e50;
      position: relative;
      font-weight: 600;
    }

    .option-card p {
      color: #7f8c8d;
      font-size: 1rem;
      line-height: 1.7;
      margin-bottom: 30px;
    }

    .option-card a {
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .emoji {
      font-size: 70px;
      margin-bottom: 20px;
      display: inline-block;
      transition: transform 0.5s ease;
    }

    .option-card:hover .emoji {
      animation: bounce 1s ease;
    }

    .btn-explore {
      background: #171736;
      color: white;
      border: none;
      padding: 14px 30px;
      border-radius: 50px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.4s ease;
      box-shadow: 0 5px 15px rgba(243, 244, 245, 0.3);
      margin-top: 15px;
      position: relative;
      overflow: hidden;
      font-size: 1rem;
      letter-spacing: 0.5px;
    }

    .btn-explore::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }

    .btn-explore:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(189, 192, 193, 0.4);
    }

    .btn-explore:hover::before {
      left: 100%;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      25% { transform: translateY(-15px); }
      50% { transform: translateY(0); }
      75% { transform: translateY(-7px); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    @keyframes cardEntry {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
      .card-container {
        gap: 30px;
      }
      .option-card {
        width: 300px;
      }
    }

    @media (max-width: 768px) {
      h2 {
        font-size: 2.2rem;
        margin: 30px 0 15px;
      }
      .card-container {
        flex-direction: column;
        align-items: center;
      }
      .option-card {
        width: 90%;
        max-width: 400px;
      }
    }
  </style>
</head>
<script>
  if (window.performance && window.performance.navigation.type === 2) {
    // If user pressed back button
    window.location.href = 'index.html';
  }
</script>
<body>

  <header>
    <div class="logo">Pick My Home</div>
    <a href="build.html"></a>
    <button type="button" class="logout" onclick="window.location.href='logout.php'">Logout</button>
  </header>

  <h2>What Would You Like To Do?</h2>

  <div class="card-container">
    <!-- Buy Home -->
    <div class="option-card">
      <a href="build.php">
        <div class="emoji">🏠</div>
        <h3>Buy Our Homes</h3>
        <p>Explore our premium collection of Pick My Home properties - beautifully designed, high-quality homes built by our expert team with attention to every detail.</p>
        <button class="btn-explore">View Homes</button>
      </a>
    </div>

    <!-- Buy Flat -->
    <div class="option-card">
      <a href="flat.php">
        <div class="emoji">🏢</div>
        <h3>Buy Our Flats</h3>
        <p>Discover our selection of modern apartments featuring premium finishes and amenities, all built to Pick My Home's exacting standards.</p>
        <button class="btn-explore">View Flats</button>
      </a>
    </div>

    <!-- Sell Property -->
    <div class="option-card">
      <a href="sell.html">
        <div class="emoji">🔄</div>
        <h3>Sell Your Property</h3>
        <p>Let us renovate and sell your property for maximum value. Our expert team handles everything from design to final sale with guaranteed results.</p>
        <button class="btn-explore">Sell Now</button>
      </a>
    </div>
  </div>

  <script>
    // Add ripple effect to buttons
    document.querySelectorAll('.btn-explore, .logout').forEach(button => {
      button.addEventListener('click', function(e) {
        let x = e.clientX - e.target.getBoundingClientRect().left;
        let y = e.clientY - e.target.getBoundingClientRect().top;
        
        let ripple = document.createElement('span');
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        this.appendChild(ripple);
        
        setTimeout(() => {
          ripple.remove();
        }, 1000);
      });
    });

  </script>
</body>
</html>