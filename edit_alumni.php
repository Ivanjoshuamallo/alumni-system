<?php
include 'connection.php';

$resultsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startLimit = ($page - 1) * $resultsPerPage;

// Get filter values from form
$collegeFilter = isset($_GET['college']) ? $_GET['college'] : '';
$departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
$sectionFilter = isset($_GET['section']) ? $_GET['section'] : '';
$employmentFilter = isset($_GET['employment']) ? $_GET['employment'] : '';

// Prepare filter conditions
$conditions = [];
if ($collegeFilter) {
    $conditions[] = "a.college = '$collegeFilter'";
}
if ($departmentFilter) {
    $conditions[] = "a.department = '$departmentFilter'";
}
if ($sectionFilter) {
    $conditions[] = "a.section = '$sectionFilter'";
}
if ($employmentFilter) {
    $conditions[] = "e.employment = '$employmentFilter'";
}

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
            a.alumni_id = e.alumni_id";

if ($conditions) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$resultsQuery = $query . " LIMIT ?, ?";

$stmt = $conn->prepare($resultsQuery);
$stmt->bind_param("ii", $startLimit, $resultsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$totalQuery = "SELECT COUNT(*) as total FROM `2024-2025` a LEFT JOIN `2024-2025-ed` e ON a.alumni_id = e.alumni_id";
$totalResult = $conn->query($totalQuery);
$totalAlumni = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalAlumni / $resultsPerPage);

// Departments array for filter
$departments = [
    "CAS" => ["Bachelor of Arts in Communication", "Bachelor of Science in Psychology"],
    "CBA" => ["Bachelor of Science in Business Administration - Major in Human Resource Development Management", 
              "Bachelor of Science in Business Administration - Major in Marketing Management", 
              "Bachelor of Science in Business Administration - Major in Operations Management"],
    "College of Accountancy" => ["Bachelor of Science in Accountancy"],
    "CCJ" => ["Bachelor of Science in Criminology"],
    "CITCS" => ["Bachelor of Science in Computer Science", 
                "Bachelor of Science in Information Technology", 
                "Associate in Computer Technology"],
    "COM" => ["Doctor of Medicine"],
    "CTE" => ["Bachelor of Elementary Education (BEEd) General Elementary Education", 
              "Bachelor of Secondary Education (BSEd) Major in Science", 
              "Major in English", 
              "Major in Social Science"],
    "IPPG" => ["Bachelor of Public Administration", "Bachelor of Arts in Political Science"],
    "ISW" => ["Bachelor of Science in Social Work"]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Alumni Information</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="table-style.css">
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
        <h2>S.Y 2024-2025 Update and Delete Alumni Information Section</h2>

        <?php
        if (isset($_GET['message'])) {
            echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
        }
        if (isset($_GET['error'])) {
            echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
        }
        ?>

        <form method="POST" action="delete_alumni.php">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all" onclick="selectAll()"> Select All</th>
                            <th>Alumni ID</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>College</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Year Graduated</th>
                            <th>Contact Number</th>
                            <th>Personal Email</th>
                            <th>Employment</th>
                            <th>Employment Status</th>
                            <th>Past Occupation</th>
                            <th>Present Occupation</th>
                            <th>Name of Employer</th>
                            <th>Address of Employer</th>
                            <th>Years in Present Employer</th>
                            <th>Type of Employer</th>
                            <th>Major Line of Business</th>
                            <th>Job Related to Program</th>
                            <th>Program Curriculum Relevant</th>
                            <th>Time to First Job</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_alumni[]" value="<?= $row['alumni_id'] ?>"></td>
                                <td><?= $row['alumni_id'] ?></td>
                                <td><?= $row['last_name'] ?></td>
                                <td><?= $row['first_name'] ?></td>
                                <td><?= $row['middle_name'] ?></td>
                                <td><?= $row['college'] ?></td>
                                <td><?= $row['department'] ?></td>
                                <td><?= $row['section'] ?></td>
                                <td><?= $row['year_graduated'] ?></td>
                                <td><?= $row['contact_number'] ?></td>
                                <td><?= $row['personal_email'] ?></td>
                                <td><?= $row['employment'] ?></td>
                                <td><?= $row['employment_status'] ?></td>
                                <td><?= $row['past_occupation'] ?></td>
                                <td><?= $row['present_occupation'] ?></td>
                                <td><?= $row['name_of_employer'] ?></td>
                                <td><?= $row['address_of_employer'] ?></td>
                                <td><?= $row['years_in_present_employer'] ?></td>
                                <td><?= $row['type_of_employer'] ?></td>
                                <td><?= $row['major_line_of_business'] ?></td>
                                <td><?= $row['job_related_to_program'] ?></td>
                                <td><?= $row['program_curriculum_relevant'] ?></td>
                                <td><?= $row['time_to_first_job'] ?></td>
                                <td style="text-align: center;">
                                    <a href="edit_alumni_form.php?id=<?= $row['alumni_id'] ?>">
                                        <img src="images/edit-icon.png" alt="Edit" width="25">
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <a href="delete_alumni.php?id=<?= $row['alumni_id'] ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                        <img src="images/delete-icon.png" alt="Delete" width="20">
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <a href="?page=1">&laquo;&laquo; First</a>
                    <a href="?page=<?= max($page - 1, 1) ?>">&laquo; Prev</a>
                    <a href="?page=<?= min($page + 1, $totalPages) ?>">Next &raquo;</a>
                    <a href="?page=<?= $totalPages ?>">Last &raquo;&raquo;</a>
                </div>

                <button type="submit" name="delete_selected" class="btn btn-danger">Delete Selected</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("open");
    }

    // Select all checkboxes
    function selectAll() {
        const checkboxes = document.querySelectorAll('input[name="selected_alumni[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = document.getElementById('select_all').checked);
    }
</script>

</body>
</html>
