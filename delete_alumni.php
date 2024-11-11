<?php
include 'connection.php';

if (isset($_GET['id'])) {
    // Single deletion logic (for the case when a single alumni ID is passed via URL)
    $alumniId = $_GET['id'];

    // Check if the alumni ID exists
    $checkQuery = "SELECT * FROM `2024-2025` WHERE alumni_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $alumniId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the alumni record from both tables
        $deleteAlumniQuery = "DELETE FROM `2024-2025` WHERE alumni_id = ?";
        $deleteEmploymentQuery = "DELETE FROM `2024-2025-ed` WHERE alumni_id = ?";

        // Execute the deletion
        $stmt = $conn->prepare($deleteAlumniQuery);
        $stmt->bind_param("i", $alumniId);
        $stmt->execute();

        $stmt = $conn->prepare($deleteEmploymentQuery);
        $stmt->bind_param("i", $alumniId);
        $stmt->execute();

        // Redirect back to the alumni page with a success message
        header("Location: edit_alumni.php?message=Alumni record deleted successfully");
        exit();
    } else {
        // Redirect back to edit_alumni.php with an error message if ID doesn't exist
        header("Location: edit_alumni.php?error=Record not found");
        exit();
    }
} elseif (isset($_POST['delete_selected']) && isset($_POST['selected_alumni']) && !empty($_POST['selected_alumni'])) {
    // Bulk deletion logic (for when multiple alumni IDs are passed via form)
    $alumniIds = $_POST['selected_alumni'];  // Get the selected alumni IDs from the form
    $alumniIdsStr = implode(',', array_map('intval', $alumniIds)); // Convert to a comma-separated string

    // Perform the deletion in both tables for the selected alumni IDs
    $deleteAlumniQuery = "DELETE FROM `2024-2025` WHERE alumni_id IN ($alumniIdsStr)";
    $deleteEmploymentQuery = "DELETE FROM `2024-2025-ed` WHERE alumni_id IN ($alumniIdsStr)";

    // Execute the deletion queries
    if ($conn->query($deleteAlumniQuery) && $conn->query($deleteEmploymentQuery)) {
        // Redirect back to the alumni page with a success message
        header("Location: edit_alumni.php?message=Selected alumni records deleted successfully");
        exit();
    } else {
        // Redirect back to the alumni page with an error message if deletion fails
        header("Location: edit_alumni.php?error=Error deleting selected alumni records");
        exit();
    }
} else {
    // Redirect back to the alumni page if no ID is provided for single delete or no alumni are selected for bulk delete
    header("Location: edit_alumni.php?error=No alumni selected for deletion");
    exit();
}
?>
