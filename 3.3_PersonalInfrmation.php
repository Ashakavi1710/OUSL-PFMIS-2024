
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PF-MIS Personal Information</title>
    <style>
        body {
    margin: 0;
    font-family: Georgia, "Times New Roman", Times, serif;
    background-color: #d3b4a4;
  }
  
  .container {
    width: 90%;
    height: auto;
    padding: 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

.header {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 5px;
    width: 100%;
}

.logo {
    width: 100px;
    margin-right: 10px;
}

h1 {
    color: #862222;
    font-size: 4.5rem;
    margin-top: 5px;
    margin-bottom: 1px;
}

h2 {
    color: #606016;
    font-family: Georgia, "Times New Roman", Times, serif;
    margin: 5px 0;
    font-size: 3.0rem;
    line-height: 1.5;
    font-style: italic;
}

.form {
    margin-top: 20px;
    margin-bottom: 30px;
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two columns */
    gap: 20px; /* Space between rows and columns */
    column-gap: 150px;
}

.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

label {
    width: 150px;
    font-size: 16px;
    color: #a14b4b;
    font-weight: bold;
    margin-right: 10px;
    text-align: right;
}

input, select {
    width: 300px; /* Adjust width to fit the form layout */
    padding: 8px;
    border: 1px solid #a86b6b;
    border-radius: 5px;
    font-size: 14px;
}

.update-button {
    grid-column: span 2; /* Span the button across both columns */
    background-color: #a14b4b;
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 0 auto;
}

.update-button:hover {
    background-color: #7d3b3b;
}


.custom-line {
    border-color: #862222;
    border-width: 3px;
    width: 1500px;
}
</style>
    
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="/pf-mis/Profile.png" alt="PF-MIS Logo" class="logo">
            <h1>PF - MIS</h1>
        </div>
        <hr class="custom-line">

        <h2>Personal Information</h2>
        <form class="form" method="POST" action="insertPI.php">
    <div class="form-row">
        <label for="emp-id">EMP ID:</label>
        <input type="text" id="username" name="username" placeholder="Enter ID" required>
    </div>
    <div class="form-row">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter Name" required>
    </div>
    <div class="form-row">
        <label for="email">Email ID:</label>
        <input type="email" id="email" name="email" placeholder="Enter Email ID" required>
    </div>
    <div class="form-row">
        <label for="dob">DOB:</label>
        <input type="date" id="dob" name="dob" required>
    </div>
    <div class="form-row">
        <label for="position">Position:</label>
        <input type="text" id="position" name="position" placeholder="Enter Position" required>
    </div>
    <div class="form-row">
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
    </div>
    <div class="form-row">
        <label for="salary-code">Salary Code:</label>
        <input type="text" id="salary-code" name="salary_code" placeholder="Enter Salary Code" required>
    </div>
    <div class="form-row">
        <label for="civil-status">Civil Status:</label>
        <input type="text" id="civil-status" name="civil_status" placeholder="Enter Civil Status" required>
    </div>
    <div class="form-row">
        <label for="present-scale">Present Salary Scale:</label>
        <input type="text" id="present-scale" name="present_salary_scale" placeholder="Enter Present Salary Scale" required>
    </div>
    <div class="form-row">
        <label for="spouse-name">Spouse Name:</label>
        <input type="text" id="spouse-name" name="spouse_name" placeholder="Enter Spouse Name">
    </div>
    <div class="form-row">
        <label for="appointment-date">Appointment Date:</label>
        <input type="date" id="appointment-date" name="appointment_date">
    </div>
    <div class="form-row">
        <label for="contact-no">Contact No:</label>
        <input type="text" id="contact-no" name="contact_no" placeholder="Enter Contact No">
    </div>
    <div class="form-row">
        <label for="increment-date">Increment Date:</label>
        <input type="date" id="increment-date" name="increment_date">
    </div>
    <div class="form-row">
        <button type="submit" class="update-button">Update</button>
        <a href="view_certificates.php" class="btn">Verify Certificate</a>
    </div>
</form>

    </div>
</body>
</html>;
