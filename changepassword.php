<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$dbname = "pf-mis"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: 1.2_Login Manager.html");
    exit();
}

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];  // Now editable
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Prepare a SELECT query to get the stored hashed password
    $stmt = $conn->prepare("SELECT password FROM admin_user WHERE username = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['password'];

        // Verify old password
        if (password_verify($old_pass, $stored_hashed_password)) {
            // Check if new passwords match
            if ($new_pass === $confirm_pass) {
                // Hash new password
                $new_hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_stmt = $conn->prepare("UPDATE admin_user SET password = ? WHERE username = ?");
                $update_stmt->bind_param("ss", $new_hashed_password, $user);

                if ($update_stmt->execute()) {
                    echo "<script>
                        alert('Password changed successfully');
                        window.location.href = '1.2_Login Admin.html'; // Redirect to dashboard
                        </script>";
                } else {
                    echo "<script>alert('Error updating password');</script>";
                }
                $update_stmt->close();
            } else {
                echo "<script>alert('New passwords do not match');</script>";
            }
        } else {
            echo "<script>alert('Incorrect old password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password</title>
<style>
/* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #d3b4a4;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #c52e2e;
    margin-top: 20px;
}

form {
    width: 100%;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

label {
    font-size: 16px;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

input[type="submit"] {
    width: 100%;
    padding: 14px;
    background-color: #862222;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #6b1919;
}

.alert {
    color: red;
    text-align: center;
    margin: 10px 0;
}

/* Responsive styles */
@media (max-width: 600px) {
    form {
        padding: 15px;
    }
}
</style>
</head>
<body>
<h2>Change Password</h2>
<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" required><br><br>

    <label for="old_password">Old Password:</label>
    <input type="password" id="old_password" name="old_password" required><br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>

    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Change Password">
</form>
</body>
</html>
