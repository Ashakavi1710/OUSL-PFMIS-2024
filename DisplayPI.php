<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "pf-mis"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("Access Denied. Please log in.");
}

$username = $_SESSION['username']; // Get logged-in username

// Fetch employee details based on username from the personal_info table
$sql = "SELECT * FROM personal_info WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No records found for user: " . htmlspecialchars($username);
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PF - MIS</title>
    <link rel="stylesheet" href="1.4_EmpDetails_Style3.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <h1>
                    <img src="Profile.png" alt="Logo" height="100px" width="400px">
                    PF - MIS
                </h1>
                <hr class="custom-line">
            </div>
        </div>

        <div class="form">
            <div class="column">
                <p><strong>EMP NO :- <?php echo htmlspecialchars($row['username']); ?></strong></p>
                <p>Name :- <?php echo htmlspecialchars($row['name']); ?></p>
                <p>DOB :- <?php echo htmlspecialchars($row['dob']); ?></p>
                <p>Gender :- <?php echo ucfirst(htmlspecialchars($row['gender'])); ?></p>
                <p>Civil Status :- <?php echo htmlspecialchars($row['civil_status']); ?></p>
                <p>Spouse Name :- <?php echo htmlspecialchars($row['spouse_name']); ?></p>
                <p>Contact No :- <?php echo htmlspecialchars($row['contact_no']); ?></p>
                <p>Email ID :- <?php echo htmlspecialchars($row['email']); ?></p>
                <p>Position :- <?php echo htmlspecialchars($row['position']); ?></p>
                <p>Salary Code :- <?php echo htmlspecialchars($row['salary_code']); ?></p>
                <p>Present Salary Scale :- <?php echo htmlspecialchars($row['present_salary_scale']); ?></p>
                <p>Appointment Date :- <?php echo htmlspecialchars($row['appointment_date']); ?></p>
                <p>Increment Date :- <?php echo htmlspecialchars($row['increment_date']); ?></p>
            </div>
        </div>
        <form method="POST" action="Upload Certificates.php" enctype="multipart/form-data">
        <label for="certificates">Upload Certificates (only .jpeg or .jpg):</label>
        <input type="file" name="certificates[]" id="certificates" multiple accept="image/jpeg, image/jpg" required><br><br>
    
        <label for="certificate_name">Certificate Name:</label><br>
        <input type="text" name="certificate_name[]" placeholder="Enter certificate name" required><br><br>
    
        <input type="submit" value="Upload Certificates">
        </form>
        <br><a href="logout.php">Logout</a> <!-- Logout Button -->
    </div>
</body>
</html>
