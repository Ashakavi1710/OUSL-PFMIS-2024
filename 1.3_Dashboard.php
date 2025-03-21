<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('You must log in first!');
            window.location.href = '1.2_Login Employee.html';
          </script>";
    exit();
}

// Get the logged-in username
$user = $_SESSION['username'];

// Define the path to the user's folder
$empFolder = "employee/" . $user; // Example: employee/EMP1234

echo "<h2>Welcome, $user</h2>";
echo "<h3>Your Files:</h3>";

// Check if the directory exists and list the files
if (is_dir($empFolder)) {
    $files = scandir($empFolder);
    $files = array_diff($files, array('.', '..')); // Remove default entries

    if (count($files) > 0) {
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li><a href='$empFolder/$file' target='_blank'>$file</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No files found in your folder.</p>";
    }
} else {
    echo "<p>Your folder does not exist. Please contact the administrator.</p>";
}

// Logout button
echo "<br><a href='logout.php'>Logout</a>";
?>
