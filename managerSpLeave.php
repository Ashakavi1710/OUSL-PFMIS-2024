<?php
session_start(); // Start session

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "pf-mis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status']) && isset($_POST['LeaveID'])) {
    $leave_id = $_POST['LeaveID']; // Get leave ID
    $status = $_POST['update_status']; // 'Approved' or 'Not Approved'

    // Update status in the database
    $sql = "UPDATE special_leave_requests SET status=? WHERE LeaveID=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }
    
    $stmt->bind_param("si", $status, $leave_id); // Bind parameters

    if ($stmt->execute()) {
        $_SESSION['message'] = "Leave request updated to $status!";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to refresh
        exit(); // Ensure script stops executing after redirect
    } else {
        $_SESSION['message'] = "Error updating leave request: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch leave requests
$sql = "SELECT LeaveID, username, request, status FROM special_leave_requests";
$result = $conn->query($sql);

// Check if query failed
if (!$result) {
    die("Error fetching leave requests: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employee Leave Requests</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
        h2 { text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0px 0px 10px #ddd; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        button { padding: 8px 12px; margin: 5px; border: none; cursor: pointer; }
        .approve-btn { background: green; color: white; }
        .reject-btn { background: red; color: white; }
    </style>
</head>
<body>

<?php
// Show success/error message if available
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Remove message after displaying
}
?>

<h2>Manage Employee Leave Requests</h2>
<table>
    <tr>
        <th>Leave ID</th>
        <th>Employee ID</th>
        <th>Request</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['LeaveID']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['request']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="LeaveID" value="<?php echo htmlspecialchars($row['LeaveID']); ?>">
                    <button type="submit" name="update_status" value="Approved" class="approve-btn">Approve</button>
                    <button type="submit" name="update_status" value="Not Approved" class="reject-btn">Reject</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
<?php $conn->close(); ?>
