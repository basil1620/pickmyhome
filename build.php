<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Redirect to login
    exit();
}

// Optional: Prevent caching (back-button issue after logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Build Your Home - Pick My Home</title>
  <style>
    :root {
      --secondary: #3f37c9;
      --accent-1: #ff9f43;
      --accent-2: #2ecc71;
      --accent-3: #e74c3c;
      --dark: #1b263b;
      --light: #f8f9fa;
      --gray: #adb5bd;
  
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 40px;
      color: var(--dark);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
      background: url('building.jpg') center/cover no-repeat fixed;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
  
      z-index: -1;
    }

    .back-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: linear-gradient(135deg, var(--accent-3) 0%, #c0392b 100%);
      color: white;
      padding: 12px 25px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
      transition: all 0.3s ease;
      border: none;
      display: flex;
      align-items: center;
      gap: 8px;
      z-index: 10;
    }

    .back-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
    }

    .back-btn::before {
      content: "←";
      font-size: 18px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: var(--light);
      font-size: 2.5rem;
      position: relative;
      padding-bottom: 15px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    h1:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent-1));
      border-radius: 2px;
      box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
    }

    form {
      max-width: 750px;
      margin: 40px auto;
      background: rgba(101, 101, 101, 0.5); /* More transparent form */
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.8s ease-out;
      backdrop-filter: blur(10px); /* Increased blur for better effect */
      border: 1px solid rgba(255,255,255,0.4); /* Lighter border */
      position: relative;
      overflow: hidden;
    }

    form::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 8px;
      background: linear-gradient(90deg, 
        var(--primary) 0%, 
        var(--accent-1) 33%, 
        var(--accent-2) 66%, 
        var(--accent-3) 100%);
      animation: gradientBG 8s ease infinite;
      background-size: 400% 400%;
      opacity: 0.8; /* Slightly transparent gradient bar */
    }

    .form-section {
      margin-bottom: 35px;
      animation: slideUp 0.6s ease-out;
    }

    .form-section h2 {
      color: var(--accent-1);
      margin-bottom: 20px;
      font-size: 1.4rem;
      display: flex;
      align-items: center;
      gap: 10px;
      text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .form-section h2::before {
      content: '';
      display: inline-block;
      width: 8px;
      height: 25px;
      background: var(--accent-1);
      border-radius: 4px;
      opacity: 0.8;
    }

    .radio-group {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 15px;
    }

    .radio-option {
      display: none;
    }

    .radio-label {
      padding: 14px 28px;
      background: rgba(255, 255, 255, 0.8);
      border: 2px solid rgba(173, 181, 189, 0.5);
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      color: var(--dark);
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(5px);
    }

    .radio-option:checked + .radio-label {
      background: rgba(230, 86, 9, 0.8);
      color: white;
      border-color:rgba(186, 81, 24, 0.8);
      box-shadow: 0 6px 15px rgba(67, 97, 238, 0.2);
      transform: translateY(-2px);
    }

    .radio-label:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      background: rgba(255, 255, 255, 0.9);
    }

    .chip-container {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 15px;
    }

    .chip {
      padding: 12px 24px;
      background: rgba(255, 255, 255, 0.8);
      border: 2px solid rgba(173, 181, 189, 0.5);
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      backdrop-filter: blur(5px);
    }

    .chip:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      background: rgba(255, 255, 255, 0.9);
    }

    .chip.selected {
      background: rgba(230, 86, 9, 0.8);
      color: white;
      border-color:  rgba(230, 86, 9, 0.8);
      box-shadow: 0 6px 15px rgba(67, 97, 238, 0.2);
      transform: translateY(-2px);
    }

    button[type="submit"] {
      width: 100%;
      padding: 18px;
      font-size: 18px;
      border-radius: 30px;
      border: none;
      background: linear-gradient(135deg, rgba(220, 99, 33, 0.8) 0%, rgba(236, 129, 41, 0.8) 100%);
      color: white;
      margin-top: 30px;
      cursor: pointer;
      transition: all 0.4s ease;
      font-weight: 600;
      letter-spacing: 0.5px;
      box-shadow: 0 8px 20px rgba(228, 76, 0, 0.89);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(5px);
    }

    button[type="submit"]:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 25px rgba(204, 38, 12, 0.4);
      background: linear-gradient(135deg, rgba(168, 31, 7, 0.8) 0%, rgba(196, 36, 1, 0.8) 100%);
    }

    button[type="submit"]::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }

    button[type="submit"]:hover::after {
      left: 100%;
    }

    /* Floating particles */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      background: rgba(67, 97, 238, 0.15);
      border-radius: 50%;
      animation: float linear infinite;
      filter: blur(1px);
    }

    @keyframes float {
      0% { transform: translateY(100vh) rotate(0deg); }
      100% { transform: translateY(-100px) rotate(360deg); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from { 
        opacity: 0;
        transform: translateY(30px);
      }
      to { 
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      body {
        padding: 20px;
        background-attachment: scroll;
      }
      
      form {
        padding: 25px;
        backdrop-filter: blur(5px);
      }
      
      .radio-group, .chip-container {
        gap: 10px;
      }
      
      .radio-label, .chip {
        padding: 10px 15px;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <!-- Background Image is set in body CSS -->
  <div class="overlay"></div>

  <!-- Floating Particles -->
  <div class="particles"></div>

  <a href="selection_page.php" class="back-btn">Back to Selection</a>
  
  <h1>Find Your Dream Home</h1>
  
  <form action="build_result.php" method="post">
    <div class="form-section">
      <h2>Select Your Investment Range</h2>
      <div class="radio-group" id="budget-group">
        <input type="radio" id="budget-18-30" name="budget" value="18-30" class="radio-option" required onchange="updateOptions()">
        <label for="budget-18-30" class="radio-label">₹18 - ₹30 Lakhs</label>
        
        <input type="radio" id="budget-30-48" name="budget" value="30-48" class="radio-option" onchange="updateOptions()">
        <label for="budget-30-48" class="radio-label">₹30 - ₹48 Lakhs</label>
        
        <input type="radio" id="budget-48-70" name="budget" value="48-70" class="radio-option" onchange="updateOptions()">
        <label for="budget-48-70" class="radio-label">₹48 - ₹70 Lakhs</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Preferred Sq.ft</h2>
      <div class="radio-group" id="sqft-group">
        <input type="radio" id="sqft-default" name="sqft" value="" class="radio-option" disabled checked>
        <label for="sqft-default" class="radio-label">Select budget first</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Preferred number of BHK</h2>
      <div class="radio-group" id="bhk-group">
        <input type="radio" id="bhk-default" name="bhk" value="" class="radio-option" disabled checked>
        <label for="bhk-default" class="radio-label">Select budget first</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Preferred Location in Kerala</h2>
      <div class="chip-container" id="location-container">
        <div class="chip" data-value="Thiruvananthapuram">Thiruvananthapuram</div>
        <div class="chip" data-value="Kollam">Kollam</div>
        <div class="chip" data-value="Pathanamthitta">Pathanamthitta</div>
        <div class="chip" data-value="Alappuzha">Alappuzha</div>
        <div class="chip" data-value="Kottayam">Kottayam</div>
        <div class="chip" data-value="Idukki">Idukki</div>
        <div class="chip" data-value="Ernakulam">Ernakulam</div>
        <div class="chip" data-value="Thrissur">Thrissur</div>
        <div class="chip" data-value="Palakkad">Palakkad</div>
        <div class="chip" data-value="Malappuram">Malappuram</div>
        <div class="chip" data-value="Kozhikode">Kozhikode</div>
        <div class="chip" data-value="Wayanad">Wayanad</div>
        <div class="chip" data-value="Kannur">Kannur</div>
        <div class="chip" data-value="Kasaragod">Kasaragod</div>
      </div>
      <input type="hidden" id="location" name="location" required>
    </div>

    <button type="submit">🔍 Find My Perfect Home</button>
  </form>

  <script>
    // Create floating particles
    function createParticles() {
      const container = document.querySelector('.particles');
      const particleCount = 30;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random properties
        const size = Math.random() * 15 + 5;
        const posX = Math.random() * 100;
        const duration = Math.random() * 20 + 10;
        const delay = Math.random() * 5;
        const opacity = Math.random() * 0.3 + 0.1;
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}%`;
        particle.style.bottom = `-${size}px`;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.opacity = opacity;
        
        container.appendChild(particle);
      }
    }

    // Update options based on budget selection
    function updateOptions() {
      const budget = document.querySelector('input[name="budget"]:checked')?.value;
      const sqftGroup = document.getElementById('sqft-group');
      const bhkGroup = document.getElementById('bhk-group');

      // Clear previous options
      sqftGroup.innerHTML = '';
      bhkGroup.innerHTML = '';

      if (!budget) return;

      if (budget === '18-30') {
        addRadioOption(sqftGroup, 'sqft', '1200', '1200 Sq.ft (Compact Family Home)');
        addRadioOption(sqftGroup, 'sqft', '1500', '1500 Sq.ft (Spacious Family Home)');
        addRadioOption(bhkGroup, 'bhk', '1BHK', '1 BHK (Compact Living)');
        addRadioOption(bhkGroup, 'bhk', '2BHK', '2 BHK (Small Family)');
      } else if (budget === '30-48') {
        addRadioOption(sqftGroup, 'sqft', '1600', '1600 Sq.ft (Comfortable Living)');
        addRadioOption(sqftGroup, 'sqft', '1800', '1800 Sq.ft (Premium Space)');
        addRadioOption(bhkGroup, 'bhk', '2BHK', '2 BHK (Modern Family)');
        addRadioOption(bhkGroup, 'bhk', '3BHK', '3 BHK (Growing Family)');
      } else if (budget === '48-70') {
        addRadioOption(sqftGroup, 'sqft', '1800', '1800 Sq.ft (Luxury Living)');
        addRadioOption(sqftGroup, 'sqft', '2100', '2100 Sq.ft (Executive Home)');
        addRadioOption(bhkGroup, 'bhk', '2BHK', '2 BHK (Premium)');
        addRadioOption(bhkGroup, 'bhk', '3BHK', '3 BHK (Family Home)');
        addRadioOption(bhkGroup, 'bhk', '4BHK', '4 BHK (Large Family)');
      }
    }

    // Helper function to add radio options
    function addRadioOption(group, name, value, text) {
      const id = `${name}-${value}`;
      const radio = document.createElement('input');
      radio.type = 'radio';
      radio.id = id;
      radio.name = name;
      radio.value = value;
      radio.className = 'radio-option';
      radio.required = true;
      
      const label = document.createElement('label');
      label.htmlFor = id;
      label.className = 'radio-label';
      label.textContent = text;
      
      group.appendChild(radio);
      group.appendChild(label);
    }

    // Handle location chip selection
    document.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', function() {
        // Remove selected class from all chips
        document.querySelectorAll('.chip').forEach(c => c.classList.remove('selected'));
        
        // Add selected class to clicked chip
        this.classList.add('selected');
        
        // Update hidden input value
        document.getElementById('location').value = this.getAttribute('data-value');
      });
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
    });
  </script>
</body>
</html>