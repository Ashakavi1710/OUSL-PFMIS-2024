<?php
session_start(); // Start the session

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "pf-mis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("Please log in to view your leave requests.");
}

$logged_in_user = $_SESSION['username']; // Get the logged-in employee's username

// Handle Leave Request Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $employee_id = $logged_in_user; // Correct username
    $request = $_POST['request'];

    // Insert into Database with Auto-Increment Leave ID
    $sql = "INSERT INTO special_leave_requests (username, request, status) VALUES (?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $employee_id, $request); // Fixed incorrect variable
    if ($stmt->execute()) {
        echo "<script>alert('Leave request submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch leave requests **only for the logged-in user**
$sql = "SELECT LeaveID, username, request, status FROM special_leave_requests WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $logged_in_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave Request</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
        h2 { text-align: center; }
        form { background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px #ddd; max-width: 400px; margin: auto; }
        input, textarea, button { display: block; width: 100%; margin: 10px 0; padding: 10px; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0px 0px 10px #ddd; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
    </style>
</head>
<body>

<h2>Employee Leave Request</h2>
<form method="POST">
    <textarea name="request" placeholder="Reason for Leave" required></textarea>
    <button type="submit" name="submit_leave">Submit Request</button>
</form>

<h2>Your Leave Requests</h2>
<table>
    <tr>
        <th>Leave ID</th>
        <th>Employee Username</th>
        <th>Request</th>
        <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['LeaveID']; ?></td>
            <td><?php echo $row['username']; ?></td> <!-- Correct username shown -->
            <td><?php echo $row['request']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
