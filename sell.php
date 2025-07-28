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
  <title>Sell Your Property - Pick My Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary: #470707ff;
      --primary-dark: #b11515ff;
      --primary-light: #E9E8FF;
      --secondary: #FF6584;
      --accent: #FFA84C;
      --success: #4CC9F0;
      --dark: #2B2D42;
      --light: #F8F9FA;
      --gray: #EDF2F7;
      --dark-gray: #4A5568;
      --white: #FFFFFF;
      --border: #E2E8F0;
      --card-bg: rgba(255, 255, 255, 0.95);
      --shadow: 0 10px 30px rgba(108, 99, 255, 0.15);
      --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      color: var(--dark);
      min-height: 100vh;
      background: linear-gradient(135deg, #ffffffff 0%, #ff3c00ac 100%);
      position: relative;
      overflow-x: hidden;
    }

    .bg-pattern {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: radial-gradient(var(--primary-light) 1px, transparent 1px);
      background-size: 30px 30px;
      opacity: 0.4;
      z-index: -1;
    }

    .back-btn {
      position: absolute;
      top: 40px;
      left: 1250px;
      background: var(--white);
      color: var(--dark);
      padding: 12px 25px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: var(--shadow);
      transition: var(--transition);
      border: none;
      display: flex;
      align-items: center;
      gap: 8px;
      z-index: 10;
      font-size: 0.95rem;
    }

    .back-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 20px rgba(108, 99, 255, 0.2);
      background: var(--primary);
      color: var(--white);
    }

    .back-btn i {
      margin-right: 8px;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 80px 20px 40px;
    }

    h2 {
      text-align: center;
      margin-bottom: 40px;
      color: var(--dark);
      position: relative;
      padding-bottom: 15px;
      font-weight: 700;
      font-size: 2.8rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: fadeIn 0.8s ease-out;
    }

    h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      border-radius: 4px;
    }

    .form-container {
      display: flex;
      justify-content: center;
      margin-bottom: 60px;
      animation: slideUp 0.8s ease-out;
    }

    form {
      width: 100%;
      max-width: 900px;
      background: var(--card-bg);
      padding: 50px;
      border-radius: 20px;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
      transition: var(--transition);
      border: 1px solid rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(10px);
    }

    form::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(108, 99, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
      z-index: -1;
      animation: rotate 20s linear infinite;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 30px;
    }

    .form-group {
      margin-bottom: 30px;
      position: relative;
      animation: fadeInUp 0.6s ease-out forwards;
    }

    .form-group.full-width {
      grid-column: span 2;
    }

    .form-group label {
      display: block;
      margin-bottom: 12px;
      font-weight: 600;
      color: var(--primary-dark);
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group .input-wrapper {
      position: relative;
    }

    .form-group .input-wrapper i {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary);
      font-size: 1.1rem;
      transition: var(--transition);
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 16px 20px 16px 50px;
      border-radius: 12px;
      border: 2px solid var(--gray);
      background: var(--white);
      font-size: 1rem;
      transition: var(--transition);
      font-family: 'Poppins', sans-serif;
      color: var(--dark);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .form-group textarea {
      min-height: 140px;
      resize: vertical;
      padding-left: 20px;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(108, 99, 255, 0.2);
    }

    .form-group select {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236C63FF'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 20px center;
      background-size: 15px;
      padding-right: 50px;
    }

    .file-upload-wrapper {
      position: relative;
      margin-bottom: 15px;
    }

    .file-upload-wrapper input[type="file"] {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    .file-upload-label {
      display: block;
      padding: 16px 20px;
      border-radius: 12px;
      border: 2px dashed var(--gray);
      background: var(--white);
      text-align: center;
      transition: var(--transition);
      font-size: 0.95rem;
      color: var(--dark-gray);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .file-upload-label:hover {
      border-color: var(--primary);
      background: var(--primary-light);
      color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .file-upload-label i {
      margin-right: 10px;
      color: var(--primary);
      font-size: 1.1rem;
    }

    .note {
      font-size: 0.85rem;
      color: var(--accent);
      font-style: italic;
      margin-top: 8px;
    }

    .submit-btn {
      width: 100%;
      padding: 18px;
      font-size: 1.1rem;
      border-radius: 12px;
      border: none;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: var(--white);
      margin-top: 30px;
      cursor: pointer;
      transition: var(--transition);
      font-weight: 600;
      letter-spacing: 0.5px;
      box-shadow: 0 5px 20px rgba(108, 99, 255, 0.3);
      position: relative;
      overflow: hidden;
      font-family: 'Poppins', sans-serif;
      text-transform: uppercase;
    }

    .submit-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(108, 99, 255, 0.4);
      background: linear-gradient(90deg, var(--primary-dark), #FF4D6D);
    }

    .submit-btn:active {
      transform: translateY(1px);
    }

    .submit-btn::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        90deg,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.4) 50%,
        rgba(255, 255, 255, 0) 100%
      );
      transform: translateX(-100%);
      transition: 0.6s;
    }

    .submit-btn:hover::after {
      transform: translateX(100%);
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
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

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Floating elements */
    .floating-elements {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
      overflow: hidden;
    }

    .floating-element {
      position: absolute;
      border-radius: 50%;
      background: rgba(108, 99, 255, 0.1);
      border: 1px solid rgba(108, 99, 255, 0.15);
      animation: float linear infinite;
    }

    @keyframes float {
      0% {
        transform: translateY(100vh) translateX(0) rotate(0deg);
      }
      100% {
        transform: translateY(-100px) translateX(calc(var(--random-x) * 100px)) rotate(360deg);
      }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        padding: 70px 15px 30px;
      }

      h2 {
        font-size: 2.2rem;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .form-group.full-width {
        grid-column: span 1;
      }

      form {
        padding: 30px;
      }

      .back-btn {
        top: 20px;
        left: 20px;
        padding: 10px 20px;
        font-size: 0.85rem;
      }
    }

    @media (max-width: 480px) {
      h2 {
        font-size: 1.8rem;
      }

      .form-group input[type="text"],
      .form-group input[type="number"],
      .form-group select,
      .form-group textarea {
        padding: 14px 15px 14px 45px;
      }

      .form-group .input-wrapper i {
        font-size: 1rem;
        left: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="bg-pattern"></div>
  <div class="floating-elements" id="floatingElements"></div>

  <a href="selection_page.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

  <div class="container">
    <h2>List Your Property</h2>
    <div class="form-container">
      <form action="submit_property.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
          <div class="form-group">
            <label for="property_type">Property Type</label>
            <div class="input-wrapper">
              <i class="fas fa-home"></i>
              <select name="property_type" required>
                <option value="">--Select--</option>
                <option value="House">House</option>
                <option value="Flat">Flat</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="bhk">BHK Configuration</label>
            <div class="input-wrapper">
              <i class="fas fa-bed"></i>
              <select name="bhk" required>
                <option value="">--Select--</option>
                <option value="1BHK">1 BHK</option>
                <option value="2BHK">2 BHK</option>
                <option value="3BHK">3 BHK</option>
                <option value="4BHK">4 BHK</option>
                <option value="4+BHK">4+ BHK</option>
              </select>
            </div>
          </div>

          <div class="form-group full-width">
            <label for="title">Property Title</label>
            <div class="input-wrapper">
              <i class="fas fa-pen"></i>
              <input type="text" name="title" required placeholder="Ex: Modern 2BHK House in Ernakulam">
            </div>
          </div>

          <div class="form-group">
            <label for="district">District</label>
            <div class="input-wrapper">
              <i class="fas fa-map-marker-alt"></i>
              <select name="district" required>
                <option value="">--Select District--</option>
                <option value="Thiruvananthapuram">Thiruvananthapuram</option>
                <option value="Kollam">Kollam</option>
                <option value="Pathanamthitta">Pathanamthitta</option>
                <option value="Alappuzha">Alappuzha</option>
                <option value="Kottayam">Kottayam</option>
                <option value="Idukki">Idukki</option>
                <option value="Ernakulam">Ernakulam</option>
                <option value="Thrissur">Thrissur</option>
                <option value="Palakkad">Palakkad</option>
                <option value="Malappuram">Malappuram</option>
                <option value="Kozhikode">Kozhikode</option>
                <option value="Wayanad">Wayanad</option>
                <option value="Kannur">Kannur</option>
                <option value="Kasaragod">Kasaragod</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="city">City / Town</label>
            <div class="input-wrapper">
              <i class="fas fa-city"></i>
              <input type="text" name="city" required placeholder="Ex: Ernakulam">
            </div>
          </div>

          <div class="form-group">
            <label for="price">Expected Price (₹)</label>
            <div class="input-wrapper">
              <i class="fas fa-rupee-sign"></i>
              <input type="number" name="price" required placeholder="Ex: 4500000">
            </div>
          </div>

          <div class="form-group">
            <label for="land_size_or_floor">Land Size (cents) / Floor</label>
            <div class="input-wrapper">
              <i class="fas fa-ruler-combined"></i>
              <input type="text" name="land_size_or_floor" required placeholder="Ex: 5 cents / 3rd floor">
            </div>
          </div>

          <div class="form-group full-width">
            <label for="description">Property Description</label>
            <textarea name="description" placeholder="Describe your property (features, amenities, location advantages, etc.)"></textarea>
          </div>

          <div class="form-group full-width">
            <label>Upload Property Images (min 1, max 4)</label>
            <div class="file-upload-wrapper">
              <input type="file" name="image1" accept="image/*" required id="image1">
              <label for="image1" class="file-upload-label"><i class="fas fa-camera"></i> Choose Main Image</label>
            </div>
            <div class="file-upload-wrapper">
              <input type="file" name="image2" accept="image/*" id="image2">
              <label for="image2" class="file-upload-label"><i class="fas fa-images"></i> Additional Image 1 (Optional)</label>
            </div>
            <div class="file-upload-wrapper">
              <input type="file" name="image3" accept="image/*" id="image3">
              <label for="image3" class="file-upload-label"><i class="fas fa-images"></i> Additional Image 2 (Optional)</label>
            </div>
            <div class="file-upload-wrapper">
              <input type="file" name="image4" accept="image/*" id="image4">
              <label for="image4" class="file-upload-label"><i class="fas fa-images"></i> Additional Image 3 (Optional)</label>
            </div>
          </div>

          <div class="form-group full-width">
            <label for="floorplan_pdf">Upload Floor Plan (PDF)</label>
            <div class="file-upload-wrapper">
              <input type="file" name="floorplan_pdf" accept="application/pdf" id="floorplan_pdf">
              <label for="floorplan_pdf" class="file-upload-label"><i class="fas fa-file-pdf"></i> Choose PDF File (Optional)</label>
            </div>
            <p class="note">Note: Maximum file size 5MB</p>
          </div>
        </div>

        <button class="submit-btn" type="submit">
          <i class="fas fa-paper-plane"></i> Submit Property Listing
        </button>
      </form>
    </div>
  </div>

  <script>
    // Create floating elements
    const floatingContainer = document.getElementById('floatingElements');
    const elementCount = 20;
    
    for (let i = 0; i < elementCount; i++) {
      const element = document.createElement('div');
      element.classList.add('floating-element');
      
      // Random size between 5px and 25px
      const size = Math.random() * 20 + 5;
      element.style.width = `${size}px`;
      element.style.height = `${size}px`;
      
      // Random position
      element.style.left = `${Math.random() * 100}vw`;
      element.style.setProperty('--random-x', Math.random() * 2 - 1);
      
      // Random animation duration between 15s and 40s
      const duration = Math.random() * 25 + 15;
      element.style.animationDuration = `${duration}s`;
      
      // Random delay
      element.style.animationDelay = `${Math.random() * 15}s`;
      
      // Random opacity
      element.style.opacity = Math.random() * 0.3 + 0.1;
      
      // Random shape (circle or rounded square)
      if (Math.random() > 0.7) {
        element.style.borderRadius = '8px';
      }
      
      floatingContainer.appendChild(element);
    }

    // Add animation delay to form groups
    document.querySelectorAll('.form-group').forEach((group, index) => {
      group.style.animationDelay = `${index * 0.1}s`;
    });

    // Update file input labels when files are selected
    document.querySelectorAll('input[type="file"]').forEach(input => {
      input.addEventListener('change', function() {
        if (this.files.length > 0) {
          const label = this.nextElementSibling;
          label.innerHTML = `<i class="${label.querySelector('i').className}"></i> ${this.files[0].name}`;
          label.style.color = 'var(--primary-dark)';
          label.style.borderColor = 'var(--primary)';
          label.style.backgroundColor = 'var(--primary-light)';
        }
      });
    });

    // Add focus effects to form elements
    document.querySelectorAll('input, select, textarea').forEach(element => {
      element.addEventListener('focus', function() {
        this.parentElement.parentElement.querySelector('label').style.color = 'var(--secondary)';
        this.parentElement.querySelector('i').style.color = 'var(--secondary)';
      });
      
      element.addEventListener('blur', function() {
        this.parentElement.parentElement.querySelector('label').style.color = 'var(--primary-dark)';
        this.parentElement.querySelector('i').style.color = 'var(--primary)';
      });
    });
  </script>
</body>
</html>