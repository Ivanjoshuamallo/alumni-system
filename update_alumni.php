<?php
include 'connection.php';

// Handling form submission to update alumni data in the database
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
    $employment = isset($_POST['employment']) ? $_POST['employment'] : '';
    $employmentStatus = isset($_POST['employment_status']) ? $_POST['employment_status'] : '';
    $pastOccupation = isset($_POST['past_occupation']) ? $_POST['past_occupation'] : '';
    $presentOccupation = isset($_POST['present_occupation']) ? $_POST['present_occupation'] : '';
    $nameOfEmployer = isset($_POST['name_of_employer']) ? $_POST['name_of_employer'] : '';
    $addressOfEmployer = isset($_POST['address_of_employer']) ? $_POST['address_of_employer'] : '';
    $yearsInPresentEmployer = isset($_POST['years_in_present_employer']) ? $_POST['years_in_present_employer'] : '';
    $typeOfEmployer = isset($_POST['type_of_employer']) ? $_POST['type_of_employer'] : '';
    $majorLineOfBusiness = isset($_POST['major_line_of_business']) ? $_POST['major_line_of_business'] : '';
    $jobRelatedToProgram = isset($_POST['job_related_to_program']) ? $_POST['job_related_to_program'] : '';
    $programCurriculumRelevant = isset($_POST['program_curriculum_relevant']) ? $_POST['program_curriculum_relevant'] : '';
    $timeToFirstJob = isset($_POST['time_to_first_job']) ? $_POST['time_to_first_job'] : '';

    // Start a database transaction to ensure both updates are executed
    $conn->begin_transaction();

    try {
        // Step 1: Update the `2024-2025` table with alumni basic information
        $updateQuery = "UPDATE `2024-2025` SET 
                        last_name = ?, 
                        first_name = ?, 
                        middle_name = ?, 
                        college = ?, 
                        department = ?, 
                        section = ?, 
                        year_graduated = ?, 
                        contact_number = ?, 
                        personal_email = ? 
                        WHERE alumni_id = ?";
        
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssssssi", $lastName, $firstName, $middleName, $college, $department, $section, $yearGraduated, $contactNumber, $personalEmail, $alumniId);
        
        // Execute the update for the first query
        if ($stmt->execute()) {
            // Step 2: Update the `2024-2025-ed` table with the employment details
            $updateQuery2 = "UPDATE `2024-2025-ed` SET 
                            employment = ?, 
                            employment_status = ?, 
                            past_occupation = ?, 
                            present_occupation = ?, 
                            name_of_employer = ?, 
                            address_of_employer = ?, 
                            years_in_present_employer = ?, 
                            type_of_employer = ?, 
                            major_line_of_business = ?, 
                            job_related_to_program = ?, 
                            program_curriculum_relevant = ?, 
                            time_to_first_job = ? 
                            WHERE alumni_id = ?";
            
            // Correct the order of parameters here
            $stmt2 = $conn->prepare($updateQuery2);
            $stmt2->bind_param("ssssssssssssi", $employment, $employmentStatus, $pastOccupation, $presentOccupation, $nameOfEmployer, $addressOfEmployer, 
                               $yearsInPresentEmployer, $typeOfEmployer, $majorLineOfBusiness, $jobRelatedToProgram, $programCurriculumRelevant, 
                               $timeToFirstJob, $alumniId);  // Bind alumniId last

            // Execute the update for the second query
            if ($stmt2->execute()) {
                // Commit the transaction if both updates are successful
                $conn->commit();

                // Redirect to all_information.php after successful update
                header("Location: all_information.php");
                exit(); // Always call exit() after header redirection to stop further script execution
            } else {
                // Rollback the transaction if the second update fails
                $conn->rollback();
                echo "<p>Error updating alumni employment details in 2024-2025-ed table: " . $conn->error . "</p>";
            }
        } else {
            // Rollback the transaction if the first update fails
            $conn->rollback();
            echo "<p>Error updating alumni basic information in 2024-2025 table: " . $conn->error . "</p>";
        }
    } catch (Exception $e) {
        // Rollback if any exception occurs
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>
