<?php
include 'connection.php';

// Check if 'id' is provided in the query string
if (isset($_GET['id'])) {
    $alumni_id = $_GET['id'];

    // Fetch alumni data from the database
    $query = "SELECT 
                a.alumni_id, 
                a.last_name, 
                a.first_name, 
                a.middle_name, 
                a.college, 
                a.department, 
                a.section, 
                a.year_graduated, 
                a.contact_number, 
                a.personal_email, 
                e.employment,
                e.employment_status,
                e.past_occupation,
                e.present_occupation,
                e.name_of_employer,
                e.address_of_employer,
                e.years_in_present_employer,
                e.type_of_employer,
                e.major_line_of_business,
                e.job_related_to_program,
                e.program_curriculum_relevant,
                e.time_to_first_job
              FROM 
                `2024-2025` a
              LEFT JOIN 
                `2024-2025-ed` e
              ON 
                a.alumni_id = e.alumni_id
              WHERE 
                a.alumni_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $alumni_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $alumni = $result->fetch_assoc();
    } else {
        die("Alumni not found.");
    }
} else {
    die("No alumni ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Alumni Information</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="add.css">
    <script>
    // Department list for each college
    const departments = {
        "CAS": ["Bachelor of Arts in Communication", "Bachelor of Science in Psychology"],
        "CBA": ["Bachelor of Science in Business Administration - Major in Human Resource Development Management", 
                "Bachelor of Science in Business Administration - Major in Marketing Management", 
                "Bachelor of Science in Business Administration - Major in Operations Management"],
        "College of Accountancy": ["Bachelor of Science in Accountancy"],
        "CCJ": ["Bachelor of Science in Criminology"],
        "CITCS": ["Bachelor of Science in Computer Science", "Bachelor of Science in Information Technology", "Associate in Computer Technology"],
        "COM": ["Doctor of Medicine"],
        "CTE": ["Bachelor of Elementary Education (BEEd) General Elementary Education", 
                "Bachelor of Secondary Education (BSEd) Major in Science", 
                "Major in English", 
                "Major in Social Science"],
        "IPPG": ["Bachelor of Public Administration", "Bachelor of Arts in Political Science"],
        "ISW": ["Bachelor of Science in Social Work"]
    };

    // Function to update departments when a college is selected
    function updateDepartments() {
        var college = document.getElementById('college').value;
        var departmentDropdown = document.getElementById('department');
        departmentDropdown.innerHTML = '<option value="">-- Select Department --</option>'; // Reset options

        if (college && departments[college]) {
            departments[college].forEach(function(department) {
                var option = document.createElement('option');
                option.value = department;
                option.textContent = department;
                departmentDropdown.appendChild(option);
            });
        }
    }

    // Function to toggle the visibility of the employment details
    function toggleEmploymentDetails() {
        var employmentStatus = document.getElementById('employment').value;
        var employmentDetailsDiv = document.getElementById('employment-details');

        if (employmentStatus === "Employed") {
            employmentDetailsDiv.style.display = "block";
        } else {
            employmentDetailsDiv.style.display = "none";
            // Reset all employment detail inputs when hiding the section
            resetEmploymentDetails();
        }
    }

    // Reset the employment details fields
    function resetEmploymentDetails() {
        document.getElementById('employment_status').value = '';
        document.getElementById('type_of_employer').value = '';
        document.getElementById('job_related_to_program').value = '';
        document.getElementById('program_curriculum_relevant').value = '';
        document.getElementById('past_occupation').value = '';
        document.getElementById('present_occupation').value = '';
        document.getElementById('name_of_employer').value = '';
        document.getElementById('address_of_employer').value = '';
        document.getElementById('years_in_present_employer').value = '';
        document.getElementById('major_line_of_business').value = '';
        document.getElementById('time_to_first_job').value = '';
    }

    // Call updateDepartments on page load to ensure it works with the current data
    window.onload = function() {
        updateDepartments();

        // Set the initial department value based on the alumni's current data
        var departmentDropdown = document.getElementById('department');
        var selectedDepartment = "<?= $alumni['department'] ?>";
        if (selectedDepartment) {
            departmentDropdown.value = selectedDepartment;
        }

        // Toggle the employment details based on the existing employment status
        var employmentStatus = "<?= $alumni['employment'] ?>";
        if (employmentStatus === "Employed") {
            document.getElementById('employment-details').style.display = "block";
        } else {
            document.getElementById('employment-details').style.display = "none";
        }
    };
    </script>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="images/plmun-logo.png" alt="PLMUN Logo" class="logo">
    </div>
</header>

<div id="content">
    <div class="container">
        <h2>Edit Alumni Information</h2>
        <form method="POST" action="update_alumni.php">
            <input type="hidden" name="alumni_id" value="<?= $alumni['alumni_id'] ?>">

            <!-- Alumni Basic Information -->
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($alumni['last_name']) ?>" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($alumni['first_name']) ?>" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($alumni['middle_name']) ?>">

            <label for="college">College:</label>
            <select id="college" name="college" required onchange="updateDepartments()">
                <option value="">Select College</option>
                <option value="CAS" <?= $alumni['college'] == 'CAS' ? 'selected' : '' ?>>College of Arts and Sciences (CAS)</option>
                <option value="CBA" <?= $alumni['college'] == 'CBA' ? 'selected' : '' ?>>College of Business Administration (CBA)</option>
                <option value="College of Accountancy" <?= $alumni['college'] == 'College of Accountancy' ? 'selected' : '' ?>>College of Accountancy</option>
                <option value="CCJ" <?= $alumni['college'] == 'CCJ' ? 'selected' : '' ?>>College of Criminal Justice (CCJ)</option>
                <option value="CITCS" <?= $alumni['college'] == 'CITCS' ? 'selected' : '' ?>>College of Information Technology and Computer Studies (CITCS)</option>
                <option value="COM" <?= $alumni['college'] == 'COM' ? 'selected' : '' ?>>College of Medicine (COM)</option>
                <option value="CTE" <?= $alumni['college'] == 'CTE' ? 'selected' : '' ?>>College of Teacher Education</option>
                <option value="IPPG" <?= $alumni['college'] == 'IPPG' ? 'selected' : '' ?>>Institute of Public Policy and Governance</option>
                <option value="ISW" <?= $alumni['college'] == 'ISW' ? 'selected' : '' ?>>Institute of Social Work</option>
            </select>

            <label for="department">Department/Program:</label>
            <select id="department" name="department" required>
                <option value="">-- Select Department --</option>
                <!-- Departments will be populated dynamically -->
            </select>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" value="<?= htmlspecialchars($alumni['section']) ?>">

            <label for="year_graduated">Year Graduated:</label>
            <input type="number" id="year_graduated" name="year_graduated" value="<?= htmlspecialchars($alumni['year_graduated']) ?>" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" value="<?= htmlspecialchars($alumni['contact_number']) ?>">

            <label for="personal_email">Personal Email:</label>
            <input type="email" id="personal_email" name="personal_email" value="<?= htmlspecialchars($alumni['personal_email']) ?>">

            <!-- Alumni Employment Details -->
            <h3>Employment Details</h3>
            <label for="employment">Employment:</label>
            <select id="employment" name="employment" onchange="toggleEmploymentDetails()">
                <option value="">Select Employment Status</option>
                <option value="Employed" <?= $alumni['employment'] == 'Employed' ? 'selected' : '' ?>>Employed</option>
                <option value="Self-Employed" <?= $alumni['employment'] == 'Self-Employed' ? 'selected' : '' ?>>Self-Employed</option>
                <option value="Actively looking for a job" <?= $alumni['employment'] == 'Actively looking for a job' ? 'selected' : '' ?>>Actively looking for a job</option>
                <option value="Never been employed" <?= $alumni['employment'] == 'Never been employed' ? 'selected' : '' ?>>Never been employed</option>
            </select>

            <div id="employment-details" style="display: <?= $alumni['employment'] ? 'block' : 'none' ?>;">
                <!-- Employment Status Dropdown -->
                <label for="employment_status">Employment Status:</label>
                <select id="employment_status" name="employment_status">
                    <option value="">-- Select Employment Status --</option>
                    <option value="Regular/ Permanent" <?= $alumni['employment_status'] == 'Regular/ Permanent' ? 'selected' : '' ?>>Regular/ Permanent</option>
                    <option value="Casual" <?= $alumni['employment_status'] == 'Casual' ? 'selected' : '' ?>>Casual</option>
                    <option value="Contractual" <?= $alumni['employment_status'] == 'Contractual' ? 'selected' : '' ?>>Contractual</option>
                    <option value="Temporary" <?= $alumni['employment_status'] == 'Temporary' ? 'selected' : '' ?>>Temporary</option>
                    <option value="Part-Time Seeking Full-Time Employment" <?= $alumni['employment_status'] == 'Part-Time Seeking Full-Time Employment' ? 'selected' : '' ?>>Part-Time Seeking Full-Time Employment</option>
                    <option value="Part-Time but not seeking Full-Time Employment" <?= $alumni['employment_status'] == 'Part-Time but not seeking Full-Time Employment' ? 'selected' : '' ?>>Part-Time but not seeking Full-Time Employment</option>
                    <option value="Other" <?= $alumni['employment_status'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>

                <!-- Type of Employer Dropdown -->
                <label for="type_of_employer">Type of Employer / Organization:</label>
                <select id="type_of_employer" name="type_of_employer">
                    <option value="">-- Select Type of Employer --</option>
                    <option value="Private" <?= $alumni['type_of_employer'] == 'Private' ? 'selected' : '' ?>>Private</option>
                    <option value="Government" <?= $alumni['type_of_employer'] == 'Government' ? 'selected' : '' ?>>Government</option>
                    <option value="Non-Government Organization (NGO)" <?= $alumni['type_of_employer'] == 'Non-Government Organization (NGO)' ? 'selected' : '' ?>>Non-Government Organization (NGO)</option>
                    <option value="Non-Profit Organization" <?= $alumni['type_of_employer'] == 'Non-Profit Organization' ? 'selected' : '' ?>>Non-Profit Organization</option>
                    <option value="Other" <?= $alumni['type_of_employer'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>

                <label for="job_related_to_program">Is your current job related to the program you took up in PLMun?</label>
                <select id="job_related_to_program" name="job_related_to_program">
                    <option value="Yes" <?= $alumni['job_related_to_program'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $alumni['job_related_to_program'] == 'No' ? 'selected' : '' ?>>No</option>
                </select>

                <label for="program_curriculum_relevant">Is your program curriculum relevant to your current job?</label>
                <select id="program_curriculum_relevant" name="program_curriculum_relevant">
                    <option value="Yes" <?= $alumni['program_curriculum_relevant'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= $alumni['program_curriculum_relevant'] == 'No' ? 'selected' : '' ?>>No</option>
                </select>

                <label for="past_occupation">Past Occupation:</label>
                <input type="text" id="past_occupation" name="past_occupation" value="<?= htmlspecialchars($alumni['past_occupation']) ?>">

                <label for="present_occupation">Present Occupation:</label>
                <input type="text" id="present_occupation" name="present_occupation" value="<?= htmlspecialchars($alumni['present_occupation']) ?>">

                <label for="name_of_employer">Name of Employer:</label>
                <input type="text" id="name_of_employer" name="name_of_employer" value="<?= htmlspecialchars($alumni['name_of_employer']) ?>">

                <label for="address_of_employer">Address of Employer:</label>
                <input type="text" id="address_of_employer" name="address_of_employer" value="<?= htmlspecialchars($alumni['address_of_employer']) ?>">

                <label for="years_in_present_employer">Years in Present Employer:</label>
                <input type="text" id="years_in_present_employer" name="years_in_present_employer" value="<?= htmlspecialchars($alumni['years_in_present_employer']) ?>">

                <label for="major_line_of_business">Major Line of Business:</label>
                <input type="text" id="major_line_of_business" name="major_line_of_business" value="<?= htmlspecialchars($alumni['major_line_of_business']) ?>">

                <label for="time_to_first_job">How long did it take you to get your first job?</label>
                <input type="text" id="time_to_first_job" name="time_to_first_job" value="<?= htmlspecialchars($alumni['time_to_first_job']) ?>">
            </div>

            <button type="submit">Update</button>
        </form>
    </div>
</div>

</body>
</html>
