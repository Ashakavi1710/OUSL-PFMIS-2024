<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PF - MIS</title>
    <link rel="stylesheet" href="4.3_ManagerScreen_Style4.css" />
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <h1><img src="Profile.png" alt="Logo" height="100px" width="400px">PF - MIS</h1><br>
                <hr class="custom-line">
            </div>
        </div>
        <div class="sub-header">
            <h2>Manager's Screen</h2>
        </div><br>

        <div class="buttons">
            <?php
            // Directory containing employee folders
            $employeeDir = "employees/";

            // Check if the directory exists
            if (file_exists($employeeDir) && is_dir($employeeDir)) {
                // Open the directory
                if ($handle = opendir($employeeDir)) {
                    // Loop through the directory contents
                    while (($entry = readdir($handle)) !== false) {
                        if ($entry != "." && $entry != "..") {
                            // Display a button for each employee folder
                            echo "<button class='emp' data-page='$entry'>$entry</button>";
                        }
                    }
                    // Close the directory
                    closedir($handle);
                }
            } else {
                echo "<p>No employees found.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // JavaScript to navigate to employee pages
        document.addEventListener("DOMContentLoaded", () => {
            const buttons = document.querySelectorAll(".emp");
            buttons.forEach((button) => {
                button.addEventListener("click", () => {
                    const page = button.getAttribute("data-page");
                    window.location.href = `employees/${page}/Managerempdet.html`;
                });
            });
        });
    </script>
</body>
</html>
