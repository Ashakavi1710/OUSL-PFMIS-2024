<?php
session_start();
$empNumber = "EMP12345";
$_SESSION['empNumber'] = $empNumber;


// Redirect after storing the session variable

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    
}

?>
<a href="3.3_PersonalInformation.php?emp_id=<?php echo $emp_id; ?>">View Personal Information</a>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PF - MIS</title>
    <link rel="stylesheet" href="3.1_Admin_Style15.css" />
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">
            <h1><img src="/pf-mis/Profile.png" alt="Logo" height="100px" width="400px"> PF - MIS</h1><br>
            <hr class="custom-line">
        </div>
    </div>

    <div class="sub-header">
        <h2>Admin</h2>
    </div><br>

    <!-- Employee Buttons -->
    <div class="buttons" id="employeeButtons">
        <?php
        // Employee Directory
        $employeeDir = "employees/";

        if (!file_exists($employeeDir)) {
            mkdir($employeeDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Display all employee buttons
        if ($handle = opendir($employeeDir)) {
            while (($entry = readdir($handle)) !== false) {
                if ($entry != "." && $entry != "..") {
                    echo "<button class='emp' data-page='$entry'>$entry</button>";
                }
            }
            closedir($handle);
        }

        // Handle the creation of a new employee page
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['empNumber'])) {
            $empNumber = htmlspecialchars($_POST['empNumber']); // Sanitize input
            $empFolder = $employeeDir . "$empNumber";
            
            // Check if the folder already exists
            if (!file_exists($empFolder)) {
                createEmployeePage($empNumber); // Create a new page and folder
                
                // Dynamically add the button for the newly created employee
                echo "<button class='emp' data-page='$empNumber'>$empNumber</button>";
            } else {
                echo "<p>Employee Page for $empNumber already exists!</p>";
            }
        }
        ?>
        
    </div>

    <!-- Form to Add a New Employee -->
    <div class="add-employee-form">
        <form method="POST" action="">
            <label for="empNumber">Enter Employee Number:</label>
            <input type="text" id="empNumber" name="empNumber" required />
            <button type="submit">Create Employee Page</button>
        </form>
    </div>

    <div class="Register">
        <a href="Register Admin.html" style="text-decoration: none;">
            <button type="button">Register Admin</button>
        </a>
        <a href="Register Employee.html" style="text-decoration: none;">
            <button type="button">Register Employee</button>
        </a>
        <a href="Register Manager.html" style="text-decoration: none;">
            <button type="button">Register Manager</button>
        </a>
    </div>

    <script>
        // JavaScript for navigation
        document.addEventListener("DOMContentLoaded", () => {
            const buttons = document.querySelectorAll(".emp");
            buttons.forEach((button) => {
                button.addEventListener("click", () => {
                    const page = button.getAttribute("data-page");
                    window.location.href = `employees/${page}/personal-details.html`;
                });
            });
        });
    </script>

    <?php
    // Function to create a new employee folder and page with logo
    function createEmployeePage($empNumber) {
        $empFolder = "employees/$empNumber"; // Create folder for the employee
        if (!file_exists($empFolder)) {
            mkdir($empFolder, 0777, true); // Create the folder if it doesn't exist
        }
    
        // Specify the path for the logo image
        $logoPath = "/pf-mis/Profile.png";  // Assuming logo is in the parent directory

        // Create the HTML file for the employee's personal details page with logo
        $pageContent = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1.0' />
            <title>PF - MIS - $empNumber</title>
            <style>
            body {
                margin: 0;
                font-family:Georgia, 'Times New Roman', Times, serif;
                background-color: #d3b4a4;
            }

            .container {
                width: 100%;
                height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                text-align: center;
            }

            .button-container, .button-container2 {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 100px;
                margin-top: 100px;
            }

            .header {
                display: flex;
                align-items: center;
                justify-content: left;
                margin-bottom: 20px;
                margin-left: 50px;
            }

            .logo img {
                width: 100px;
                margin-right: 10px;
            }

            h1 {
                color: #862222;
                font-size: 4.5rem;
                margin-top: 5px;
                margin-bottom: 1px;
                text-align: center;
            }

            h2 {
                color: #606016;
                font-size: 3rem;
                margin-top: 20px;
                margin-bottom: 1px;
                text-align: center;
            }   

            .dashboard-container {
                width: 90%;
            }

            .main-content {
                flex: 1;
                background-color: #f2e5e5;
                width: 100%;
            }

            .dashboard-btn {
                width: 380px;
                height: 100px;
                background-color: #f5f1f1;
                border: 2px solid #8b1e1e;
                border-radius: 10px;
                font-size: 32px;
                color: #8b1e1e;
                font-weight: bold;
                cursor: pointer;
                font-style: italic;
                font-family: Georgia, 'Times New Roman', Times, serif;
            }

            .dashboard-btn:hover {
                background-color: #bb7664;
                border-color: #c52e2e;
            }

            .custom-line {
                border-color: #862222;
                width: 1625px;
                border-width: 3px;
            }
            </style>
        </head>
        <body>
            <div class='dashboard-container'>
                <div class='logo'>
                    <h1><img src='$logoPath' alt='Logo' height='100px' width='400px'>PF - MIS</h1><br>
                    <hr class='custom-line'>
                    <h2 class='h2'>$empNumber</h2>
                    <div class='button-container'>
                        <button class='dashboard-btn' onclick='window.location.href=\"3.3_PersonalInfrmation.php?emp=$empNumber\"'>Personal Details</button>
                        <button class='dashboard-btn' onclick='window.location.href=\"adminSpLeave.php\"'>Special Leave</button>
                        <button class='dashboard-btn' onclick='window.location.href=\"employee_requests.php\"'>Employee Request</button>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        file_put_contents("$empFolder/personal-details.html", $pageContent);
    
            
        file_put_contents("$empFolder/personal-details.html", $pageContent);
        $sourceFile = 'C:\xampp\htdocs\pf-mis\3.3_PersonalInfrmation.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/3.3_PersonalInfrmation.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\3.4_SpecialLeave.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/3.4_SpecialLeave.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\4.1_Increment.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/4.1_Increment.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\employee_requests.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/employee_requests.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\insertPI.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/insertPI.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\Managerempdet.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/Managerempdet.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\Received Other Request.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/Received Other Request.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\4.4_Received Increment Request.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/4.4_Received Increment Request.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\Received SL Request.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/Received SL Request.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\Profile.png';  // Path to the file you want to read from
        $destinationFile = "$empFolder/Profile.png";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\1.3_Dashboard.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/1.3_Dashboard.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\1.3_Dashboard Admin.html';  // Path to the file you want to read from
        $destinationFile = "$empFolder/1.3_Dashboard Admin.html";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\DisplayPI.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/DisplayPI.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\Upload Certificates.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/Upload Certificates.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\view_certificates.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/view_certificates.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\empsendrequest.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/empsendrequest.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\adminSpLeave.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/adminSpLeave.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\empSpLeave.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/empSpLeave.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);

        $sourceFile = 'C:\xampp\htdocs\pf-mis\managerSpLeave.php';  // Path to the file you want to read from
        $destinationFile = "$empFolder/managerSpLeave.php";  // Destination file
        $pageContent = file_get_contents($sourceFile);
        file_put_contents($destinationFile, $pageContent);
    }
    
    ?>
    
    
</body>
</html>
