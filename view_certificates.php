<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("Error: You must be logged in to view certificates.");
}

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "pf-mis";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user
$username = $_SESSION['username'];

// Handle verification when the button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_certificate'])) {
    $certificate_name = $_POST['certificate_name'];

    // Update verification status
    $sql = "UPDATE certificates SET verification_status = 'Verified' WHERE username = ? AND certificate_name = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $username, $certificate_name);
        if ($stmt->execute()) {
            // Refresh the page to show updated status
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error verifying certificate: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch certificates
$sql = "SELECT certificate_name, certificate_data, verification_status FROM certificates WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($certName, $certData, $status);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Certificates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto; /* Centers the table */
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 500px;
        }
        .verified {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Uploaded Certificates</h2>

<table>
    <tr>
        <th>Certificate Name</th>
        <th>Certificate</th>
        <th>Verification Status</th>
        <th>Action</th>
    </tr>

    <?php
    while ($stmt->fetch()) {
        // Convert binary data to base64
        $base64Image = base64_encode($certData);

        echo "<tr>
                <td>$certName</td>
                <td><img src='data:image/jpeg;base64,$base64Image' width='200'></td>
                <td class='" . ($status === 'Verified' ? "verified" : "") . "'>$status</td>
                <td>";

        // Show verify button if not already verified
        if ($status !== 'Verified') {
            echo "<form method='POST' action=''>
                    <input type='hidden' name='certificate_name' value='$certName'>
                    <input type='submit' name='verify_certificate' value='Verify'>
                  </form>";
        } else {
            echo "<span class='verified'>✔ Verified</span>";
        }

        echo "</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
