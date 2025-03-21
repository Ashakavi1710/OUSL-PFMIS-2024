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
        // Store session data
        $_SESSION['username'] = $user;

        // Define the employee-specific folder path
        $empFolder = "employee/" . $user; // Example: employee/EMP1234

        // Check if the directory exists
        if (!is_dir($empFolder)) {
            if (!mkdir($empFolder, 0777, true)) {
                echo "<script>
                        alert('Employee folder not found and could not be created. Please contact the administrator.');
                        window.location.href = '1.2_Login Employee.html';
                      </script>";
                exit();
            }
        }
        
        // Redirect to the dashboard page
        header("Location: 1.3_Dashboard Admin.html");
        exit();
    } else {
        // Invalid password
        echo "<script>
                alert('Invalid Username or Password');
                window.location.href = '1.2_Login Employee.html';
              </script>";
    }
} else {
    // No user found with the given username
    echo "<script>
            alert('Invalid Username or Password');
            window.location.href = '1.2_Login Employee.html';
          </script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
