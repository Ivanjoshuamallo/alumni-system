<?php
include 'connection.php';

$resultsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startLimit = ($page - 1) * $resultsPerPage;

$collegeFilter = $_POST['college'] ?? '';
$departmentFilter = $_POST['department'] ?? '';
$sectionFilter = $_POST['section'] ?? '';

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
            e.employment
          FROM 
            `2024-2025` a
          LEFT JOIN 
            `2024-2025-ed` e
          ON 
            a.alumni_id = e.alumni_id";

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

if ($conditions) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Pagination query with LIMIT
$query .= " LIMIT ?, ?";

// Prepare the query to prevent SQL injection
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $startLimit, $resultsPerPage); // Bind the values for startLimit and resultsPerPage
$stmt->execute();
$result = $stmt->get_result();

// Total results query
$totalQuery = "SELECT COUNT(*) as total FROM `2024-2025` a LEFT JOIN `2024-2025-ed` e ON a.alumni_id = e.alumni_id";
if ($conditions) {
    $totalQuery .= " WHERE " . implode(" AND ", $conditions);
}

$totalResult = $conn->query($totalQuery);
$totalAlumni = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalAlumni / $resultsPerPage);

// Sections for filter
$sectionsQuery = "SELECT DISTINCT section FROM `2024-2025`";
$sectionsResult = $conn->query($sectionsQuery);
$sections = [];
while ($row = $sectionsResult->fetch_assoc()) {
    $sections[] = $row['section'];
}

$departments = [
    "CAS" => ["Bachelor of Arts in Communication", "Bachelor of Science in Psychology"],
    "CBA" => ["Bachelor of Science in Business Administration - Major in Human Resource Development Management", 
              "Bachelor of Science in Business Administration - Major in Marketing Management", 
              "Bachelor of Science in Business Administration - Major in Operations Management"],
    "College of Accountancy" => ["Bachelor of Science in Accountancy"],
    "CCJ" => ["Bachelor of Science in Criminology"],
    "CITCS" => ["Bachelor of Science in Computer Science", "Bachelor of Science in Information Technology", 
                "Associate in Computer Technology"],
    "COM" => ["Doctor of Medicine"],
    "CTE" => ["Bachelor of Elementary Education (BEEd) General Elementary Education", 
              "Bachelor of Secondary Education (BSEd) Major in Science", 
              "Major in English", "Major in Social Science"],
    "IPPG" => ["Bachelor of Public Administration", "Bachelor of Arts in Political Science"],
    "ISW" => ["Bachelor of Science in Social Work"]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header with Logo on the left and Hamburger on the Right -->
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

<!-- Page Content -->
<div id="content">
    <div class="container">
        <h2>S.Y 2024-2025 ALUMNI LIST</h2>

        <!-- Centered Filteration Form -->
        <div class="filter-container">
            <form method="POST" action="">
                <div class="filter-row">
                    <select name="college" id="college" onchange="this.form.submit()">
                        <option value="">Select College</option>
                        <?php foreach (array_keys($departments) as $college): ?>
                            <option value="<?= $college ?>" <?= $collegeFilter == $college ? 'selected' : '' ?>><?= $college ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="department" id="department">
                        <option value="">Select Department</option>
                        <?php if ($collegeFilter): ?>
                            <?php foreach ($departments[$collegeFilter] as $department): ?>
                                <option value="<?= $department ?>" <?= $departmentFilter == $department ? 'selected' : '' ?>><?= $department ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <input list="sections" name="section" id="section" placeholder="Type or select Section" value="<?= htmlspecialchars($sectionFilter) ?>" />
                    <datalist id="sections">
                        <?php foreach ($sections as $section): ?>
                            <option value="<?= $section ?>"></option>
                        <?php endforeach; ?>
                    </datalist>

                    <div class="filter-container">
                        <button type="submit" class="button">Filter</button>
                        <a href="index.php" class="button">All Alumni</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Alumni Table -->
        <table>
            <thead>
                <tr>
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
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
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
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
