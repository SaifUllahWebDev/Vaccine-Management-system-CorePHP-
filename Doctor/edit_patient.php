<?php
require('db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM patient WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Display an edit form pre-filled with patient data
        echo "
        <form method='POST' action=''>
            <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
            <label>Patient Name:</label>
            <input type='text' name='patient_name' value='" . htmlspecialchars($row['patient_name']) . "' required>
            <label>Phone Number:</label>
            <input type='text' name='phone_number' value='" . htmlspecialchars($row['phone_number']) . "' required>
            <label>Date of Birth:</label>
            <input type='date' name='dob' value='" . htmlspecialchars($row['dob']) . "' required>
            <label>Allergy:</label>
            <input type='text' name='allergy' value='" . htmlspecialchars($row['allergy']) . "'>
            <label>Age:</label>
            <input type='number' name='age' value='" . htmlspecialchars($row['age']) . "' required>
            <button type='submit' name='update'>Update</button>
        </form>";
    }
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $patient_name = $_POST['patient_name'];
    $phone_number = $_POST['phone_number'];
    $dob = $_POST['dob'];
    $allergy = $_POST['allergy'];
    $age = intval($_POST['age']);

    $update_sql = "UPDATE patient SET patient_name='$patient_name', phone_number='$phone_number', dob='$dob', allergy='$allergy', age=$age WHERE id=$id";

    if ($conn->query($update_sql)) {
        echo "Patient record updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
