<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Home</title>
    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="home.css">
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

<!-- Main Content -->
<div id="content">
    <div class="main-content">
        <h2>COLLEGES</h2>
        
        <!-- Gallery of College Icons -->
        <div class="gallery">
            <div class="gallery-item">
                <img src="images/cas-logo.png" alt="CAS Logo">
                <p>College of Arts & Sciences</p>
            </div>
            <div class="gallery-item">
                <img src="images/cba-logo.png" alt="CBA Logo">
                <p>College of Business Administration</p>
            </div>
            <div class="gallery-item">
                <img src="images/ccj-logo.png" alt="CCJ Logo">
                <p>College of Criminal Justice</p>
            </div>
            <div class="gallery-item">
                <img src="images/citcs-logo.png" alt="CITCS Logo">
                <p>College of Computer Studies</p>
            </div>
            <div class="gallery-item">
                <img src="images/cte-logo.png" alt="CTE Logo">
                <p>College of Teacher Education</p>
            </div>
        </div>

        <h2>EDUCATIONAL PHILOSOPHY</h2>
        <div class="philosophy">
            <div class="philosophy-item">
                <h3>Mission</h3>
                <p>To provide quality, affordable and relevant education responsive to the changing needs of the local and global communities through effective and efficient integration of instruction, research, and extension; to develop productive and God-loving individuals in society.</p>
            </div>
            <div class="philosophy-item">
                <h3>Vision</h3>
                <p>A dynamic and highly competitive Higher Education Institution (HEI) committed to people empowerment towards building a humane society.</p>
            </div>
            <div class="philosophy-item">
                <h3>Quality Policy</h3>
                <p>“We, in the Pamantasan ng Lungsod ng Muntinlupa, commit to meet and even exceed our clients’ needs and expectations by adhering to good governance, productivity and continually improving the effectiveness of our Quality Management System in compliance to ethical standards and applicable statutory and regulatory requirements.”</p>
            </div>
        </div>
    </div>

    <!-- Contact Info in Bottom Corner -->
    <div class="contact">
        Email: <a href="mailto:plmuncomm@plmun.edu.ph">plmuncomm@plmun.edu.ph</a>
    </div>

    <!-- Footer with Website Link -->
    <footer>
        Visit our official website: <a href="https://www.plmun.edu.ph/#" target="_blank">https://www.plmun.edu.ph/#</a>
    </footer>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("open");
    }
</script>

</body>
</html>
