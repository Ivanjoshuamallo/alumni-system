<?php
include 'connection.php';

// Handling form submission to insert new alumni data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data from POST request
    $alumniId = $_POST['alumni_id'];
    $lastName = $_POST['last_name'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $college = $_POST['college'];
    $department = $_POST['department'];
    $section = $_POST['section'];
    $yearGraduated = $_POST['year_graduated'];
    $contactNumber = $_POST['contact_number'];
    $personalEmail = $_POST['personal_email'];

    // Additional form fields for the second table (2024-2025-ed)
    $employment = $_POST['employment'];
    $employmentStatus = $_POST['employment_status'];
    $pastOccupation = $_POST['past_occupation'];
    $presentOccupation = $_POST['present_occupation'];
    $nameOfEmployer = $_POST['name_of_employer'];
    $addressOfEmployer = $_POST['address_of_employer'];
    $yearsInPresentEmployer = $_POST['years_in_present_employer'];
    $typeOfEmployer = $_POST['type_of_employer'];
    $majorLineOfBusiness = $_POST['major_line_of_business'];
    $jobRelatedToProgram = $_POST['job_related_to_program'];
    $programCurriculumRelevant = $_POST['program_curriculum_relevant'];
    $timeToFirstJob = $_POST['time_to_first_job'];

    // Start a database transaction to ensure both inserts are executed
    $conn->begin_transaction();

    try {
        // Step 1: Insert into the `2024-2025` table
        $query = "INSERT INTO `2024-2025` (alumni_id, last_name, first_name, middle_name, college, department, section, year_graduated, contact_number, personal_email)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssss", $alumniId, $lastName, $firstName, $middleName, $college, $department, $section, $yearGraduated, $contactNumber, $personalEmail);

        // Execute the first query
        if ($stmt->execute()) {
            // Now insert into the `2024-2025-ed` table using the same alumni_id
            $query2 = "INSERT INTO `2024-2025-ed` (alumni_id, employment, employment_status, past_occupation, present_occupation, name_of_employer, address_of_employer, 
                      years_in_present_employer, type_of_employer, major_line_of_business, job_related_to_program, program_curriculum_relevant, time_to_first_job) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("issssssssssss", $alumniId, $employment, $employmentStatus, $pastOccupation, $presentOccupation, $nameOfEmployer, $addressOfEmployer, 
            $yearsInPresentEmployer, $typeOfEmployer, $majorLineOfBusiness, $jobRelatedToProgram, $programCurriculumRelevant, $timeToFirstJob);

            // Execute the second query
            if ($stmt2->execute()) {
                // Commit the transaction if both inserts are successful
                $conn->commit();

                // Redirect to all_information.php after successful insertion
                header("Location: all_information.php");
                exit(); // Always call exit() after header redirection to stop further script execution
            } else {
                // Rollback the transaction if the second insert fails
                $conn->rollback();
                echo "<p>Error inserting into the 2024-2025-ed table: " . $conn->error . "</p>";
            }
        } else {
            // Rollback the transaction if the first insert fails
            $conn->rollback();
            echo "<p>Error adding alumni to 2024-2025 table: " . $conn->error . "</p>";
        }
    } catch (Exception $e) {
        // Rollback if any exception occurs
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>


    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Alumni Information</title>
    <link rel="stylesheet" href="add.css">
</head>
<body>

<header>
    <div class="logo-container">
        <img src="images/plmun-logo.png" alt="PLMUN Logo" class="logo">
    </div>
    <div class="hamburger-container">
        <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>
    </div>
</header>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <button class="closebtn" onclick="toggleSidebar()">&times;</button>
    </div>
    <div class="sidebar-links">
        <a href="home.php">Home</a>
        <a href="index.php">Alumni Information</a>
        <a href="employment.php">Employment Details</a>
        <a href="all_information.php">All Information</a>
        <a href="add_alumni.php">Add Alumni</a>
        <a href="upload.php">Upload Alumni</a>
        <a href="edit_alumni.php">Edit Alumni</a>
        <a href="manage_account.php">Account</a>
    </div>
</div>

<div id="content">
    <div class="container">
        <h2>Add Alumni Information</h2>
        <form method="POST" action="add_alumni.php">
            <!-- Alumni Basic Information -->
            <label for="alumni_id">Alumni ID:</label>
            <input type="number" id="alumni_id" name="alumni_id" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name">

            <label for="college">College:</label>
            <select id="college" name="college" required onchange="updateDepartments()">
                <option value="">Select College</option>
                <option value="CAS">College of Arts and Sciences (CAS)</option>
                <option value="CBA">College of Business Administration (CBA)</option>
                <option value="College of Accountancy">College of Accountancy</option>
                <option value="CCJ">College of Criminal Justice (CCJ)</option>
                <option value="CITCS">College of Information Technology and Computer Studies (CITCS)</option>
                <option value="COM">College of Medicine (COM)</option>
                <option value="CTE">College of Teacher Education</option>
                <option value="IPPG">Institute of Public Policy and Governance</option>
                <option value="ISW">Institute of Social Work</option>
            </select>

            <label for="department">Department/Program:</label>
            <select id="department" name="department" required>
                <option value="">-- Select Department --</option>
            </select>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section">

            <label for="year_graduated">Year Graduated:</label>
            <input type="number" id="year_graduated" name="year_graduated" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number">

            <label for="personal_email">Personal Email:</label>
            <input type="email" id="personal_email" name="personal_email">

            <!-- Alumni Employment Details -->
            <h3>Employment Details</h3>
            <label for="employment">Employment:</label>
            <select id="employment" name="employment" onchange="toggleEmploymentDetails()">
                <option value="">Select Employment Status</option>
                <option value="Employed">Employed</option>
                <option value="Self-Employed">Self-Employed</option>
                <option value="Actively looking for a job">Actively looking for a job</option>
                <option value="Never been employed">Never been employed</option>
            </select>

            <div id="employment-details" style="display: none;">
    <!-- Employment Status Dropdown -->
    <label for="employment_status">Employment Status:</label>
    <select id="employment_status" name="employment_status">
        <option value="">-- Select Employment Status --</option>
        <option value="Regular/ Permanent">Regular/ Permanent</option>
        <option value="Casual">Casual</option>
        <option value="Contractual">Contractual</option>
        <option value="Temporary">Temporary</option>
        <option value="Part-Time Seeking Full-Time Employment">Part-Time Seeking Full-Time Employment</option>
        <option value="Part-Time but not seeking Full-Time Employment">Part-Time but not seeking Full-Time Employment</option>
        <option value="Other">Other</option>
    </select>

    <!-- Type of Employer Dropdown -->
    <label for="type_of_employer">Type of Employer / Organization:</label>
    <select id="type_of_employer" name="type_of_employer">
        <option value="">-- Select Type of Employer --</option>
        <option value="Private">Private</option>
        <option value="Government">Government</option>
        <option value="Non-Government Organization (NGO)">Non-Government Organization (NGO)</option>
        <option value="Non-Profit Organization">Non-Profit Organization</option>
        <option value="Other">Other</option>
    </select>

    <!-- Is your job related to the program? -->
    <label for="job_related_to_program">Is your current job related to the program you took up in PLMun?</label>
    <select id="job_related_to_program" name="job_related_to_program">
        <option value="">-- Select Answer --</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>

    <!-- Is the program curriculum relevant to your current job? -->
    <label for="program_curriculum_relevant">Is your program curriculum relevant to your current job?</label>
    <select id="program_curriculum_relevant" name="program_curriculum_relevant">
        <option value="">-- Select Answer --</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>

    <!-- Other employment fields -->
    <label for="past_occupation">Past Occupation:</label>
    <input type="text" id="past_occupation" name="past_occupation">

    <label for="present_occupation">Present Occupation:</label>
    <input type="text" id="present_occupation" name="present_occupation">

    <label for="name_of_employer">Name of Employer:</label>
    <input type="text" id="name_of_employer" name="name_of_employer">

    <label for="address_of_employer">Address of Employer:</label>
    <input type="text" id="address_of_employer" name="address_of_employer">

    <label for="years_in_present_employer">Years in Present Employer:</label>
    <input type="text" id="years_in_present_employer" name="years_in_present_employer">

    <label for="major_line_of_business">Major Line of Business:</label>
    <input type="text" id="major_line_of_business" name="major_line_of_business">

    <label for="time_to_first_job">How long did it take you to get your first job?</label>
    <input type="text" id="time_to_first_job" name="time_to_first_job">
</div>


            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        sidebar.style.width = (sidebar.style.width === "250px") ? "0" : "250px";
    }

    function toggleEmploymentDetails() {
        var employmentStatus = document.getElementById("employment").value;
        var employmentDetails = document.getElementById("employment-details");

        if (employmentStatus === "Employed") {
            employmentDetails.style.display = "block";
        } else {
            employmentDetails.style.display = "none";
            clearEmploymentDetails();
        }
    }

    function clearEmploymentDetails() {
        document.getElementById("employment_status").value = "";
        document.getElementById("type_of_employer").value = "";
        document.getElementById("job_related_to_program").value = "";
        document.getElementById("program_curriculum_relevant").value = "";
        document.getElementById("past_occupation").value = "";
        document.getElementById("present_occupation").value = "";
        document.getElementById("name_of_employer").value = "";
        document.getElementById("address_of_employer").value = "";
        document.getElementById("years_in_present_employer").value = "";
        document.getElementById("major_line_of_business").value = "";
        document.getElementById("time_to_first_job").value = "";
    }

    function updateDepartments() {
        var college = document.getElementById("college").value;
        var departmentSelect = document.getElementById("department");

        // Clear previous department options
        departmentSelect.innerHTML = "<option value=''>-- Select Department --</option>";

        var departments = {
            "CAS": ["Bachelor of Arts in Communication", "Bachelor of Science in Psychology"],
            "CBA": ["Bachelor of Science in Business Administration - Major in Human Resource Development Management", "Bachelor of Science in Business Administration - Major in Marketing Management", "Bachelor of Science in Business Administration - Major in Operations Management"],
            "College of Accountancy": ["Bachelor of Science in Accountancy"],
            "CCJ": ["Bachelor of Science in Criminology"],
            "CITCS": ["Bachelor of Science in Computer Science", "Bachelor of Science in Information Technology", "Associate in Computer Technology"],
            "COM": ["Doctor of Medicine"],
            "CTE": ["Bachelor of Elementary Education (BEEd) General Elementary Education", "Bachelor of Secondary Education (BSEd) Major in Science", "Major in English", "Major in Social Science"],
            "IPPG": ["Bachelor of Public Administration", "Bachelor of Arts in Political Science"],
            "ISW": ["Bachelor of Science in Social Work"]
        };

        if (college in departments) {
            departments[college].forEach(function(department) {
                var option = document.createElement("option");
                option.value = department;
                option.text = department;
                departmentSelect.appendChild(option);
            });
        }
    }
</script>

</body>
</html>
