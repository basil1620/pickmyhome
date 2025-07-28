<?php
session_start();
include '../db/connection.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.html");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $land_size = $_POST['land_size'];
    $sqft = $_POST['sqft'];
    $bhk = $_POST['bhk'];
    $cost = $_POST['cost'];
    $district = $_POST['district'];

    // Upload files
    $uploadDir = '../uploads/';
    $image1 = basename($_FILES['image1']['name']);
    $image2 = basename($_FILES['image2']['name']);
    $image3 = basename($_FILES['image3']['name']);
    $image4 = basename($_FILES['image4']['name']);
    $pdf = basename($_FILES['blueprint_pdf']['name']);

    move_uploaded_file($_FILES['image1']['tmp_name'], $uploadDir . $image1);
    move_uploaded_file($_FILES['image2']['tmp_name'], $uploadDir . $image2);
    move_uploaded_file($_FILES['image3']['tmp_name'], $uploadDir . $image3);
    move_uploaded_file($_FILES['image4']['tmp_name'], $uploadDir . $image4);
    move_uploaded_file($_FILES['blueprint_pdf']['tmp_name'], $uploadDir . $pdf);

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO house_listings (title, land_size, sqft, bhk, cost, district, image1, image2, image3, image4, blueprint_pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiisssssss", $title, $land_size, $sqft, $bhk, $cost, $district, $image1, $image2, $image3, $image4, $pdf);
    $stmt->execute();
}

// Delete house
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM house_listings WHERE id = $id");
    header("Location: manage_houses.php");
    exit();
}

// Fetch all house listings
$result = $conn->query("SELECT * FROM house_listings ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage House Listings</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
            background: #f4f7f8;
        }
        h2 { text-align: center; }
        form {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        input, select {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background: #34495e;
            color: white;
        }
        .delete-btn {
            background: red;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
    <script>
        function updateOptions() {
            var priceRange = document.getElementById('cost').value;
            var bhkSelect = document.getElementById('bhk');
            var sqftSelect = document.getElementById('sqft');
            
            // Clear existing options
            bhkSelect.innerHTML = '<option value="">Select BHK</option>';
            sqftSelect.innerHTML = '<option value="">Select Sqft</option>';
            
            // Set options based on price range
            if (priceRange === "18-30") {
                // 18-30 lakhs options
                addOption(bhkSelect, "1", "1 BHK");
                addOption(bhkSelect, "2", "2 BHK");
                
                addOption(sqftSelect, "1200", "1200 sqft");
                addOption(sqftSelect, "1500", "1500 sqft");
            } else if (priceRange === "30-48") {
                // 30-48 lakhs options
                addOption(bhkSelect, "2", "2 BHK");
                addOption(bhkSelect, "3", "3 BHK");
                
                addOption(sqftSelect, "1600", "1600 sqft");
                addOption(sqftSelect, "1800", "1800 sqft");
            } else if (priceRange === "48-70") {
                // 48-70 lakhs options
                addOption(bhkSelect, "2", "2 BHK");
                addOption(bhkSelect, "3", "3 BHK");
                addOption(bhkSelect, "4", "4 BHK");
                
                addOption(sqftSelect, "1800", "1800 sqft");
                addOption(sqftSelect, "2100", "2100 sqft");
            }
        }
        
        function addOption(selectElement, value, text) {
            var option = document.createElement("option");
            option.value = value;
            option.text = text;
            selectElement.add(option);
        }
    </script>
</head>
<body>

<a class="back-link" href="../admin_dashboard.php">⬅ Back to Admin Dashboard</a>
<h2>Upload House Listing</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="House Title" required>
    <input type="number" name="land_size" placeholder="Land Size (in cent)" required>
    
    <select id="cost" name="cost" onchange="updateOptions()" required>
        <option value="">Select Price Range</option>
        <option value="18-30">18-30 Lakhs</option>
        <option value="30-48">30-48 Lakhs</option>
        <option value="48-70">48-70 Lakhs</option>
    </select>
    
    <select id="bhk" name="bhk" required>
        <option value="">Select BHK</option>
        <!-- Options will be populated by JavaScript -->
    </select>
    
    <select id="sqft" name="sqft" required>
        <option value="">Select Sqft</option>
        <!-- Options will be populated by JavaScript -->
    </select>
    
    <select name="district" required>
        <option value="">Select District</option>
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

    <label>Upload 4 Images</label>
    <input type="file" name="image1" required>
    <input type="file" name="image2" required>
    <input type="file" name="image3" required>
    <input type="file" name="image4" required>

    <label>Upload Blueprint (PDF)</label>
    <input type="file" name="blueprint_pdf" accept="application/pdf" required>

    <input type="submit" value="Add House">
</form>

<h2>All House Listings</h2>
<table>
    <tr>
        <th>Title</th>
        <th>Location</th>
        <th>BHK</th>
        <th>Land Size</th>
        <th>Sqft</th>
        <th>Price</th>
        <th>Blueprint</th>
        <th>Delete</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['district']) ?></td>
        <td><?= $row['bhk'] ?> BHK</td>
        <td><?= $row['land_size'] ?> cent</td>
        <td><?= $row['sqft'] ?> sqft</td>
        <td>
            <?php 
                if ($row['cost'] === "18-30") echo "18-30 Lakhs";
                elseif ($row['cost'] === "30-48") echo "30-48 Lakhs";
                elseif ($row['cost'] === "48-70") echo "48-70 Lakhs";
                else echo $row['cost'];
            ?>
        </td>
        <td><a href="../uploads/<?= $row['blueprint_pdf'] ?>" download>Download</a></td>
        <td><a class="delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>