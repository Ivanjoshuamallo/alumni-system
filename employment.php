<?php
include 'connection.php';

$resultsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startLimit = ($page - 1) * $resultsPerPage;

$employmentFilter = $_POST['employment'] ?? '';

$query = "SELECT 
            a.alumni_id, 
            a.last_name, 
            a.first_name,
            e.employment, 
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

$conditions = [];
if ($employmentFilter) {
    $conditions[] = "e.employment = '$employmentFilter'";
}

if ($conditions) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " LIMIT ?, ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $startLimit, $resultsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$totalQuery = "SELECT COUNT(*) as total FROM `2024-2025` a LEFT JOIN `2024-2025-ed` e ON a.alumni_id = e.alumni_id";
if ($conditions) {
    $totalQuery .= " WHERE " . implode(" AND ", $conditions);
}

$totalResult = $conn->query($totalQuery);
$totalAlumni = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalAlumni / $resultsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Employment Details</title>
    <link rel="stylesheet" href="style.css">
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
        <h2>S.Y 2024-2025 Alumni Employment Details</h2>

        <div class="filter-container">
            <form method="POST" action="">
                <div class="filter-row">
                    <select name="employment" id="employment" onchange="this.form.submit()">
                        <option value="">Select Employment</option>
                        <option value="Employed" <?= $employmentFilter == 'Employed' ? 'selected' : '' ?>>Employed</option>
                        <option value="Self-Employed" <?= $employmentFilter == 'Self-Employed' ? 'selected' : '' ?>>Self-Employed</option>
                        <option value="Actively looking for a job" <?= $employmentFilter == 'Actively looking for a job' ? 'selected' : '' ?>>Actively looking for a job</option>
                        <option value="Never been employed" <?= $employmentFilter == 'Never been employed' ? 'selected' : '' ?>>Never been employed</option>
                    </select>
                    <button type="submit" class="button">Filter</button>
                    <a href="employment.php" class="button">All Alumni</a>
                </div>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Alumni ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Employment</th>
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['alumni_id'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['first_name'] ?></td>
                        <td><?= $row['employment'] ?></td>
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
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
            <a href="?page=1"><<</a>
            <a href="?page=<?= max(1, $page - 1) ?>"><</a>
            <span>Page <?= $page ?> of <?= $totalPages ?></span>
            <a href="?page=<?= min($totalPages, $page + 1) ?>">></a>
            <a href="?page=<?= $totalPages ?>">>></a>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("open");
    }
</script>

</body>
</html>
