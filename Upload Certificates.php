<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("Error: You must be logged in to upload certificates.");
}

// Get the username from session
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "pf-mis";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted and files are available
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['certificates'])) {
    $files = $_FILES['certificates'];
    $certificate_names = isset($_POST['certificate_name']) ? $_POST['certificate_name'] : []; // Get input values

    foreach ($files['name'] as $key => $filename) {
        $tempName = $files['tmp_name'][$key];
        $fileError = $files['error'][$key];

        // Validate file type (JPEG only)
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($fileExtension !== 'jpeg' && $fileExtension !== 'jpg') {
            echo "Only JPEG files are allowed for certificate upload.<br>";
            continue;
        }

        // Read the image as binary data
        $imgData = file_get_contents($tempName);

        // Get certificate name from input field
        $certificateName = isset($certificate_names[$key]) ? $certificate_names[$key] : "Unnamed Certificate";

        // Insert into the database using the username
        $verification_status = "Pending";  // Default status
        $sql = "INSERT INTO certificates (username, certificate_name, certificate_data, verification_status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Use "b" for binary data
            $stmt->bind_param("sssb", $username, $certificateName, $imgData, $verification_status);
            
            if ($stmt->execute()) {
                echo "Certificate '$certificateName' uploaded successfully!<br>";
            } else {
                echo "Database error: " . $stmt->error . "<br>";
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error . "<br>";
        }
    }
}

$conn->close();
?>
