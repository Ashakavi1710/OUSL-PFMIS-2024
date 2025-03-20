<?php
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

// Get user input (username and password from the registration form)
$user = $_POST['username'];
$pass = $_POST['password'];

// Hash the password using password_hash() before storing it
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Prepare the query to insert the user into the database
$stmt = $conn->prepare("INSERT INTO admin_user (username, password) VALUES (?, ?)");
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the username and hashed password parameters to the prepared statement
$stmt->bind_param("ss", $user, $hashed_password);

// Execute the query
if ($stmt->execute()) {
    echo "User registered successfully!";
} else {
    echo "Error registering user: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
