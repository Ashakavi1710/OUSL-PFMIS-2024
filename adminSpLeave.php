<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "pf-mis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the employee number (assuming it's extracted from the folder structure)
$empNumber = basename(__DIR__); // Gets the folder name as empNumber

// Initialize variables
$selected_employee = "";
$selected_request = "";
$selected_status = "";
$employee_folder = "uploads/" . $empNumber; // Employee folder path
$files = [];

// Fetch leave requests where username matches $empNumber
$sql = "SELECT * FROM special_leave_requests WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $empNumber);
$stmt->execute();
$result = $stmt->get_result();

// Get data if found
if ($row = $result->fetch_assoc()) {
    $selected_employee = $row['username'];
    $selected_request = $row['request'];
    $selected_status = $row['status'];

    // Check if folder exists and list files
    if (is_dir($employee_folder)) {
        $files = array_diff(scandir($employee_folder), array('.', '..'));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave Requests</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
        h2 { text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0px 0px 10px #ddd; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .details { width: 50%; margin: 20px auto; padding: 20px; background: white; box-shadow: 0px 0px 10px #ddd; text-align: center; }
        .file-list { list-style: none; padding: 0; }
        .file-list li { margin: 5px 0; }
    </style>
</head>
<body>

<h2>Employee Leave Requests</h2>

<table>
    <tr>
        <th>Leave ID</th>
        <th>Employee ID</th>
        <th>Request</th>
        <th>Status</th>
    </tr>
    <?php
    // Display only records matching username = empNumber
    $sql = "SELECT LeaveID, username, request, status FROM special_leave_requests WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $empNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['LeaveID']}</td>
                <td>{$row['username']}</td>
                <td>{$row['request']}</td>
                <td>{$row['status']}</td>
            </tr>";
    }
    ?>
</table>

<?php if (!empty($selected_employee)) { ?>
    <div class="details">
        <h3>Employee Leave Details</h3>
        <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($selected_employee); ?></p>
        <p><strong>Request:</strong> <?php echo htmlspecialchars($selected_request); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($selected_status); ?></p>

        <?php if (!empty($files)) { ?>
            <h3>Files in Employee Folder:</h3>
            <ul class="file-list">
                <?php foreach ($files as $file) { ?>
                    <li><a href="<?php echo htmlspecialchars($employee_folder . '/' . $file); ?>" target="_blank"><?php echo htmlspecialchars($file); ?></a></li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p></p>
        <?php } ?>
    </div>
<?php } ?>

</body>
</html>

<?php
$conn->close();
?>
