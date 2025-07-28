<?php // Show flat images and inquiry form ?><?php
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
  <title>Find Your Perfect Flat - Pick My Home</title>
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
      background: url('flat.jpg') center/cover no-repeat fixed;
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
      background: rgba(101, 101, 101, 0.5);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.8s ease-out;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.4);
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
      opacity: 0.8;
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
  <div class="overlay"></div>
  <div class="particles"></div>

  <a href="selection_page.php" class="back-btn">Back to Selection</a>
  
  <h1>Find Your Perfect Flat</h1>
  
  <form action="flat_result.php" method="post">
    <div class="form-section">
      <h2>Select Your Budget Range</h2>
      <div class="radio-group" id="budget-group">
        <input type="radio" id="budget-15-25" name="budget" value="15-25" class="radio-option" required onchange="updateOptions()">
        <label for="budget-15-25" class="radio-label">₹15 - ₹25 Lakhs</label>
        
        <input type="radio" id="budget-25-40" name="budget" value="25-40" class="radio-option" onchange="updateOptions()">
        <label for="budget-25-40" class="radio-label">₹25 - ₹40 Lakhs</label>
        
        <input type="radio" id="budget-40-60" name="budget" value="40-60" class="radio-option" onchange="updateOptions()">
        <label for="budget-40-60" class="radio-label">₹40 - ₹60 Lakhs</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Preferred Flat Size</h2>
      <div class="radio-group" id="size-group">
        <input type="radio" id="size-default" name="size" value="" class="radio-option" disabled checked>
        <label for="size-default" class="radio-label">Select budget first</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Number of Bedrooms</h2>
      <div class="radio-group" id="bedrooms-group">
        <input type="radio" id="bedrooms-default" name="bedrooms" value="" class="radio-option" disabled checked>
        <label for="bedrooms-default" class="radio-label">Select budget first</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Preferred Floor Level</h2>
      <div class="radio-group" id="floor-group">
        <input type="radio" id="floor-default" name="floor" value="" class="radio-option" disabled checked>
        <label for="floor-default" class="radio-label">Select budget first</label>
      </div>
    </div>

    <div class="form-section">
      <h2>Preferred Amenities</h2>
      <div class="chip-container" id="amenities-container">
        <div class="chip" data-value="parking">Parking</div>
        <div class="chip" data-value="gym">Gym</div>
        <div class="chip" data-value="pool">Swimming Pool</div>
        <div class="chip" data-value="garden">Garden</div>
        <div class="chip" data-value="playarea">Children's Play Area</div>
    
      </div>
      <input type="hidden" id="amenities" name="amenities">
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

    <button type="submit">🔍 Find My Perfect Flat</button>
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
      const sizeGroup = document.getElementById('size-group');
      const bedroomsGroup = document.getElementById('bedrooms-group');
      const floorGroup = document.getElementById('floor-group');

      // Clear previous options
      sizeGroup.innerHTML = '';
      bedroomsGroup.innerHTML = '';
      floorGroup.innerHTML = '';

      if (!budget) return;

      if (budget === '15-25') {
        addRadioOption(sizeGroup, 'size', '500-700', '500-700 Sq.ft (Compact)');
        addRadioOption(sizeGroup, 'size', '700-900', '700-900 Sq.ft (Standard)');
        addRadioOption(bedroomsGroup, 'bedrooms', '1', '1 BHK');
        addRadioOption(bedroomsGroup, 'bedrooms', '2', '2 BHK');
        addRadioOption(floorGroup, 'floor', 'low', 'Lower Floors (1-3)');
        addRadioOption(floorGroup, 'floor', 'mid', 'Middle Floors (4-7)');
      } else if (budget === '25-40') {
        addRadioOption(sizeGroup, 'size', '800-1000', '800-1000 Sq.ft (Spacious)');
        addRadioOption(sizeGroup, 'size', '1000-1200', '1000-1200 Sq.ft (Premium)');
        addRadioOption(bedroomsGroup, 'bedrooms', '2', '2 BHK');
        addRadioOption(bedroomsGroup, 'bedrooms', '3', '3 BHK');
        addRadioOption(floorGroup, 'floor', 'mid', 'Middle Floors (4-7)');
        addRadioOption(floorGroup, 'floor', 'high', 'Higher Floors (8-12)');
      } else if (budget === '40-60') {
        addRadioOption(sizeGroup, 'size', '1100-1300', '1100-1300 Sq.ft (Luxury)');
        addRadioOption(sizeGroup, 'size', '1300-1500', '1300-1500 Sq.ft (Executive)');
        addRadioOption(bedroomsGroup, 'bedrooms', '3', '3 BHK');
        addRadioOption(bedroomsGroup, 'bedrooms', '4', '4 BHK');
        addRadioOption(floorGroup, 'floor', 'high', 'Higher Floors (8-12)');
        
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

    // Handle chip selection for amenities
    document.querySelectorAll('#amenities-container .chip').forEach(chip => {
      chip.addEventListener('click', function() {
        this.classList.toggle('selected');
        updateAmenitiesInput();
      });
    });

    // Handle location chip selection
    document.querySelectorAll('#location-container .chip').forEach(chip => {
      chip.addEventListener('click', function() {
        // Remove selected class from all location chips
        document.querySelectorAll('#location-container .chip').forEach(c => c.classList.remove('selected'));
        
        // Add selected class to clicked chip
        this.classList.add('selected');
        
        // Update hidden input value
        document.getElementById('location').value = this.getAttribute('data-value');
      });
    });

    // Update amenities hidden input with selected values
    function updateAmenitiesInput() {
      const selected = [];
      document.querySelectorAll('#amenities-container .chip.selected').forEach(chip => {
        selected.push(chip.getAttribute('data-value'));
      });
      document.getElementById('amenities').value = selected.join(',');
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
    });
  </script>
</body>
</html>