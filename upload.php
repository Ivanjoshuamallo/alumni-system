<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['csv_file'])) {
    // Check if the file is a CSV
    $fileName = $_FILES['csv_file']['name'];
    $fileTmpName = $_FILES['csv_file']['tmp_name'];
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    if ($fileType != 'csv') {
        echo "<p>Error: Only CSV files are allowed.</p>";
    } else {
        // Open CSV file for reading
        if (($handle = fopen($fileTmpName, "r")) !== FALSE) {
            // Skip the header row
            fgetcsv($handle);

            // Start a transaction
            $conn->begin_transaction();
            echo "<p>Transaction started</p>"; // Debugging point

            try {
                // Prepare INSERT queries
                $query1 = "INSERT INTO `2024-2025` (alumni_id, last_name, first_name, middle_name, college, department, section, year_graduated, contact_number, personal_email)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt1 = $conn->prepare($query1);

                $query2 = "INSERT INTO `2024-2025-ed` (alumni_id, employment, employment_status, past_occupation, present_occupation, name_of_employer, address_of_employer, 
                          years_in_present_employer, type_of_employer, major_line_of_business, job_related_to_program, program_curriculum_relevant, time_to_first_job) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = $conn->prepare($query2);

                // Read CSV rows and insert them
                while (($data = fgetcsv($handle)) !== FALSE) {
                    // Output the CSV row data for debugging
                    var_dump($data);

                    list($alumniId, $lastName, $firstName, $middleName, $college, $department, $section, $yearGraduated, $contactNumber, $personalEmail,
                         $employment, $employmentStatus, $pastOccupation, $presentOccupation, $nameOfEmployer, $addressOfEmployer, $yearsInPresentEmployer,
                         $typeOfEmployer, $majorLineOfBusiness, $jobRelatedToProgram, $programCurriculumRelevant, $timeToFirstJob) = $data;

                    // Handle potential NULL or empty values for the second table
                    $employmentStatus = empty($employmentStatus) ? null : $employmentStatus;
                    $pastOccupation = empty($pastOccupation) ? null : $pastOccupation;
                    $presentOccupation = empty($presentOccupation) ? null : $presentOccupation;
                    $nameOfEmployer = empty($nameOfEmployer) ? null : $nameOfEmployer;
                    $addressOfEmployer = empty($addressOfEmployer) ? null : $addressOfEmployer;
                    $yearsInPresentEmployer = empty($yearsInPresentEmployer) ? null : $yearsInPresentEmployer;
                    $typeOfEmployer = empty($typeOfEmployer) ? null : $typeOfEmployer;
                    $majorLineOfBusiness = empty($majorLineOfBusiness) ? null : $majorLineOfBusiness;
                    $jobRelatedToProgram = empty($jobRelatedToProgram) ? null : $jobRelatedToProgram;
                    $programCurriculumRelevant = empty($programCurriculumRelevant) ? null : $programCurriculumRelevant;
                    $timeToFirstJob = empty($timeToFirstJob) ? null : $timeToFirstJob;

                    // Insert into 2024-2025 table
                    $stmt1->bind_param("isssssssss", $alumniId, $lastName, $firstName, $middleName, $college, $department, $section, $yearGraduated, $contactNumber, $personalEmail);
                    $stmt1->execute();

                    // Insert into 2024-2025-ed table
                    $stmt2->bind_param("issssssssssss", $alumniId, $employment, $employmentStatus, $pastOccupation, $presentOccupation, $nameOfEmployer, $addressOfEmployer, 
                                       $yearsInPresentEmployer, $typeOfEmployer, $majorLineOfBusiness, $jobRelatedToProgram, $programCurriculumRelevant, $timeToFirstJob);
                    $stmt2->execute();
                }

                // Commit the transaction if successful
                $conn->commit();
                echo "<p>CSV data has been successfully uploaded and inserted into the database.</p>";
                fclose($handle);

            } catch (Exception $e) {
                // Rollback if any error occurs
                $conn->rollback();
                echo "<p>Error: " . $e->getMessage() . "</p>";
                fclose($handle);
            }
        } else {
            echo "<p>Error: Unable to open CSV file.</p>";
        }
    }
}
?>
