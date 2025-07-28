<?php
session_start();
include("db/connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('❌ Please login first.'); window.location.href='index.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $property_type = $_POST['property_type'];
    $title = trim($_POST['title']);
    $district = $_POST['district'];
    $city = $_POST['city'];
    $bhk = $_POST['bhk'];
    $price = $_POST['price'];
    $land_size_or_floor = $_POST['land_size_or_floor'];
    $description = trim($_POST['description']);
    $status = "Not Verified";

    // File upload paths
    $upload_dir = "uploads/properties/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Upload images
    $image_names = [];
    for ($i = 1; $i <= 5; $i++) {
        $field = 'image' . $i;
        if (!empty($_FILES[$field]['name'])) {
            $img_name = time() . "_" . basename($_FILES[$field]['name']);
            $target_file = $upload_dir . $img_name;
            move_uploaded_file($_FILES[$field]['tmp_name'], $target_file);
            $image_names[] = $img_name;
        } else {
            $image_names[] = NULL;
        }
    }

    // Upload floor plan PDF (optional)
    $floorplan_pdf = NULL;
    if (!empty($_FILES['floorplan_pdf']['name'])) {
        $pdf_name = time() . "_" . basename($_FILES['floorplan_pdf']['name']);
        $pdf_target = $upload_dir . $pdf_name;
        move_uploaded_file($_FILES['floorplan_pdf']['tmp_name'], $pdf_target);
        $floorplan_pdf = $pdf_name;
    }

    // Extract image names for database (assuming you're using 4 images)
    $image1_name = $image_names[0] ?? NULL;
    $image2_name = $image_names[1] ?? NULL;
    $image3_name = $image_names[2] ?? NULL;
    $image4_name = $image_names[3] ?? NULL;
    $floorplan_pdf_name = $floorplan_pdf;

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO user_submitted_properties 
        (user_id, property_type, title, district, city, bhk, price, land_size_or_floor, description, image1, image2, image3, image4, floorplan_pdf, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssssssss", 
        $user_id, $property_type, $title, $district, $city, $bhk, 
        $price, $land_size_or_floor, $description, 
        $image1_name, $image2_name, $image3_name, $image4_name, 
        $floorplan_pdf_name, $status);

    if ($stmt->execute()) {
        // Store success message in session for thank_you.php
        $_SESSION['success_message'] = "✅ Property submitted successfully! Admin will verify it shortly.";
        header("Location: thanks.php");
        exit();
    } else {
        echo "<script>alert('❌ Failed to submit property.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.history.back();</script>";
}
?>