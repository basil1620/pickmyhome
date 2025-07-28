<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Pick My Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #1a2a6c;
      --primary-light: #b21f1f;
      --secondary: #fdbb2d;
      --accent: #ff5e62;
      --dark: #2d3748;
      --light: #f7fafc;
      --text: #2d3748;
      --text-light: #718096;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      color: white;
      padding: 1.5rem 2.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 10;
    }
    
    .header-content h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      letter-spacing: 0.5px;
      background: linear-gradient(to right, #ff9966, #ff5e62);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }
    
    .header-content p {
      font-size: 0.9rem;
      opacity: 0.9;
      font-weight: 400;
      color: rgba(255,255,255,0.9);
    }
    
    .logout-btn {
      padding: 0.7rem 1.5rem;
      background: var(--secondary);
      color: var(--dark);
      border: none;
      border-radius: 30px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 15px rgba(253, 187, 45, 0.4);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .logout-btn:hover {
      background: #ff5e62;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 94, 98, 0.6);
      color: white;
    }
    
    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      padding: 2.5rem;
      flex: 1;
    }
    
    .card {
      background: white;
      padding: 1.8rem;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      border-left: 5px solid var(--primary);
      animation: cardEnter 0.5s ease-out forwards;
      opacity: 0;
    }
    
    @keyframes cardEnter {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .card:nth-child(1) { animation-delay: 0.1s; border-left-color: #1a2a6c; }
    .card:nth-child(2) { animation-delay: 0.2s; border-left-color: #b21f1f; }
    .card:nth-child(3) { animation-delay: 0.3s; border-left-color: #fdbb2d; }
    .card:nth-child(4) { animation-delay: 0.4s; border-left-color: #ff5e62; }
    .card:nth-child(5) { animation-delay: 0.5s; border-left-color: #8e2de2; }
    .card:nth-child(6) { animation-delay: 0.6s; border-left-color: #4a00e0; }
    .card:nth-child(7) { animation-delay: 0.7s; border-left-color: #ff416c; }
    
    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    
    .card h2 {
      font-size: 1.3rem;
      margin-bottom: 1.2rem;
      color: var(--primary);
      font-weight: 600;
      position: relative;
      padding-bottom: 0.8rem;
    }
    
    .card h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: var(--secondary);
      border-radius: 3px;
    }
    
    .card p {
      color: var(--text-light);
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }
    
    .card-btn {
      display: inline-block;
      padding: 0.7rem 1.8rem;
      background: linear-gradient(45deg, var(--primary), var(--primary-light));
      color: white;
      text-decoration: none;
      border-radius: 30px;
      font-weight: 500;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-size: 0.9rem;
      border: 2px solid transparent;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .card:hover .card-btn {
      background: white;
      color: var(--primary);
      border-color: var(--primary);
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
    
    footer {
      text-align: center;
      padding: 1.5rem;
      background: var(--dark);
      color: rgba(255,255,255,0.8);
      font-size: 0.9rem;
      margin-top: auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding: 1.5rem 1rem;
      }
      
      .dashboard {
        grid-template-columns: 1fr;
        padding: 1.5rem;
      }
      
      .card {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="header-content">
      <h1>PICK MY HOME</h1>
      <p>Admin Dashboard - Manage your property platform</p>
    </div>
    <a href="admin_logout.php" class="logout-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
      </svg>
      Logout
    </a>
  </header>

  <section class="dashboard">
    <div class="card">
      <h2>Manage Users</h2>
      <p>View, edit, and manage all user accounts</p>
      <a href="admin/manage_users.php" class="card-btn">Access Panel</a>
    </div>
    <div class="card">
      <h2>House Listings</h2>
      <p>Manage all house property listings</p>
      <a href="admin/manage_houses.php" class="card-btn">Manage Houses</a>
    </div>
    <div class="card">
      <h2>Flat Listings</h2>
      <p>Manage all apartment and flat listings</p>
      <a href="admin/manage_flats.php" class="card-btn">View Flats</a>
    </div>
    <div class="card">
      <h2>Customer Inquiries</h2>
      <p>Respond to customer questions and requests</p>
      <a href="admin/handle_requests.php" class="card-btn">View Requests</a>
    </div>
  
    <div class="card">
      <h2>Testimonials</h2>
      <p>Moderate and manage customer testimonials</p>
      <a href="admin/moderate_testimonials.php" class="card-btn">Moderate</a>
    </div>
    <div class="card">
      <h2>Payment Records</h2>
      <p>View and manage all payment transactions</p>
      <a href="admin/view_payments.php" class="card-btn">View Payments</a>
    </div>
    <div class="card">
      <h2>Property Submissions</h2>
      <p>Manage user-submitted properties</p>
      <a href="admin/manage_user_properties.php" class="card-btn">View Properties</a>
    </div>
  </section>

  <footer>
    &copy; 2025 Pick My Home. All rights reserved. Premium living experiences across Kerala.
  </footer>

  <script>
    // Add smooth hover effects
    document.querySelectorAll('.card').forEach(card => {
      card.addEventListener('mouseenter', () => {
        card.style.transition = 'all 0.25s ease-out';
      });
      
      card.addEventListener('mouseleave', () => {
        card.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
      });
    });
    
    // Add click animation
    document.querySelectorAll('.card-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
          this.style.transform = 'scale(1)';
          window.location.href = this.getAttribute('href');
        }, 150);
      });
    });
  </script>
</body>
</html>