<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the logged-in username

// Database connection details
$DB_HOST = "localhost";
$DB_USER = "root"; // Default user in XAMPP
$DB_PASS = ""; // Default password in XAMPP
$DB_NAME = "employee_requests";

// Connect to database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle request submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requestText = trim($_POST["request_text"]);

    if (!empty($requestText)) {
        // Insert request into the database
        $stmt = $conn->prepare("INSERT INTO requests (username, request_text) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $requestText);
        if ($stmt->execute()) {
            echo "<script>alert('Request sent successfully!'); window.location.href='requests.php';</script>";
        } else {
            echo "<script>alert('Error sending request!'); window.history.back();</script>";
        }
        $stmt->close();
        exit;
    } else {
        echo "<script>alert('Request cannot be empty!'); window.history.back();</script>";
        exit;
    }
}

// Fetch requests from database
$result = $conn->query("SELECT request_text, created_at FROM requests WHERE username = '$username' ORDER BY created_at DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <style>
       body {
                margin: 0;
                font-family:Georgia, 'Times New Roman', Times, serif;
                background-color: #d3b4a4;
            }

            .container {
                width: 80%;
                height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                text-align: center;
            }

            .button-container, .button-container2 {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 100px;
                margin-top: 100px;
            }

            .header {
                display: flex;
                align-items: center;
                justify-content: left;
                margin-bottom: 20px;
                margin-left: 50px;
            }

            .logo img {
                width: 100px;
                margin-right: 10px;
            }

            h1 {
                color: #862222;
                font-size: 4.5rem;
                margin-top: 5px;
                margin-bottom: 1px;
                text-align: center;
            }

            h2 {
                color: #606016;
                font-size: 3rem;
                margin-top: 20px;
                margin-bottom: 1px;
                text-align: center;
            }   

            .requests-container {
                width: 90%;
                margin-left: 100px;
                font-size: 30px;
            }

            .main-content {
                flex: 1;
                background-color: #f2e5e5;
                width: 100%;
            }

</style>
</head>
<body>

<h2>Requests for <?php echo htmlspecialchars($username); ?></h2>

<div class="requests-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>Request:</strong> " . htmlspecialchars($row["request_text"]);
        }
    } else {
        echo "<p>No requests found.</p>";
    }
    ?>
</div>

</body>
</html>
