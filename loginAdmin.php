<?php
session_start(); // Start a session for maintaining login state

$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP is empty
$dbname = "pf-mis"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input (username and password from the login form)
$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare a SELECT query to get the stored hashed password for the user
$stmt = $conn->prepare("SELECT password FROM admin_user WHERE username = ?");
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the username parameter to the prepared statement
$stmt->bind_param("s", $user);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Fetch the user's hashed password
    $row = $result->fetch_assoc();
    $stored_hashed_password = $row['password'];

    // Verify the input password with the hashed password
    if (password_verify($pass, $stored_hashed_password)) {
        // Password is correct; redirect to the admin dashboard
        $_SESSION['username'] = $user;
        header("Location: XXX.php");
        exit();
    } else {
        // Invalid password
        echo "<script>
                alert('Invalid Username or Password');
                window.location.href = '1.2_Login Admin.html';
              </script>";
    }
} else {
    // No user found with the given username
    echo "<script>
            alert('Invalid Username or Password');
            window.location.href = '1.2_Login Admin.html';
          </script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
