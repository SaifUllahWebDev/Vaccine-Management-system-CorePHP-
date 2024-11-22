<?php
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch updated data from the POST request
    $id = intval($_POST['id']);
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $allergy = $conn->real_escape_string($_POST['allergy']);
    $age = intval($_POST['age']);

    // SQL query to update the patient record
    $sql = "UPDATE patient 
            SET patient_name = '$patient_name', 
                phone_number = '$phone_number', 
                dob = '$dob', 
                allergy = '$allergy', 
                age = $age 
            WHERE id = $id";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Patient record updated successfully.');
                window.location.href = 'patient.php'; // Replace with the name of your main display page
              </script>";
    } else {
        echo "<script>
                alert('Error updating record: " . $conn->error . "');
                window.history.back();
              </script>";
    }
}

// Close the connection
$conn->close();
?>
