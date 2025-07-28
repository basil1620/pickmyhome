<?php
session_start();
include("db/connection.php");

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Get user inputs from POST
$land_size = $_POST['land_size'] ?? '';
$budget = $_POST['budget'] ?? '';
$sqft = $_POST['sqft'] ?? '';
$bhk = $_POST['bhk'] ?? '';
$location = $_POST['location'] ?? '';

// Convert budget range to actual values for query
$min_budget = 0;
$max_budget = 0;

switch($budget) {
    case "18-30":
        $min_budget = 1800000;
        $max_budget = 3000000;
        break;
    case "30-48":
        $min_budget = 3000000;
        $max_budget = 4800000;
        break;
    case "48-70":
        $min_budget = 4800000;
        $max_budget = 7000000;
        break;
    default:
        // Handle other cases if needed
        break;
}

// Fetch matching houses from the house_listings table (admin-added houses)
$stmt = $conn->prepare("SELECT * FROM house_listings WHERE 
                        bhk = ? AND 
                        cost BETWEEN ? AND ? AND 
                        sqft >= ? AND 
                        land_size <= ? AND 
                        district = ?");
$stmt->bind_param("iiiiss", $bhk, $min_budget, $max_budget, $sqft, $land_size, $location);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Matching Houses - Pick My Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f6fc;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .house-listing {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .house-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            padding: 16px;
            width: 300px;
        }

        .house-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 5px;
        }

        .house-card h3 {
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .house-card p {
            margin: 5px 0;
            color: #555;
        }

        .download-btn {
            display: inline-block;
            margin-top: 10px;
            background: #2c3e50;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
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
</head>
<body>

<a class="back-link" href="build.php">← Back to Search</a>
<h2>Matching House Plans</h2>

<div class="house-listing">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="house-card">
                <img src="../uploads/<?php echo htmlspecialchars($row['image1']); ?>" alt="House Image">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><strong>BHK:</strong> <?php echo $row['bhk']; ?></p>
                <p><strong>Sq Ft:</strong> <?php echo $row['sqft']; ?></p>
                <p><strong>Land Size:</strong> <?php echo $row['land_size']; ?> cent</p>
                <p><strong>Price:</strong> 
                    <?php 
                        if ($row['cost'] >= 1800000 && $row['cost'] <= 3000000) echo "18-30 Lakhs";
                        elseif ($row['cost'] > 3000000 && $row['cost'] <= 4800000) echo "30-48 Lakhs";
                        elseif ($row['cost'] > 4800000 && $row['cost'] <= 7000000) echo "48-70 Lakhs";
                        else echo "₹" . number_format($row['cost']);
                    ?>
                </p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['district']); ?></p>
                <a href="../uploads/<?php echo $row['blueprint_pdf']; ?>" class="download-btn" download>Download Blueprint</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No matching house plans found. Try adjusting your filters.</p>
    <?php endif; ?>
</div>

</body>
</html>