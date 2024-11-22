<?php
require('db.php');

// Add the `corona_result` column if it doesn't already exist (one-time operation).
$conn->query("ALTER TABLE patient ADD COLUMN IF NOT EXISTS corona_result ENUM('Positive', 'Negative') DEFAULT 'Negative'");

// Fetch all patients from the database
$sql = "SELECT * FROM patient";
$result = $conn->query($sql);

// Handle updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_patient'])) {
    $id = intval($_POST['id']);
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $allergy = $conn->real_escape_string($_POST['allergy']);
    $age = intval($_POST['age']);
    $medicine_prescribed = $conn->real_escape_string($_POST['medicine_prescribed']);
    $vaccination_status = $conn->real_escape_string($_POST['vaccination_status']);
    $corona_result = $conn->real_escape_string($_POST['corona_result']);

    // Update query
    $update_sql = "UPDATE patient 
                   SET patient_name = '$patient_name', 
                       phone_number = '$phone_number', 
                       dob = '$dob', 
                       allergy = '$allergy', 
                       age = $age, 
                       medicine_prescribed = '$medicine_prescribed', 
                       vaccination_status = '$vaccination_status',
                       corona_result = '$corona_result'
                   WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>
                alert('Patient details updated successfully.');
                window.location.href = 'patient_manage.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating details: " . $conn->error . "');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .close-btn, .save-btn {
            margin-top: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .modal-content {
            display: flex;
            flex-direction: column;
        }
        .close-btn {
            align-self: flex-end;
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .save-btn {
            background: green;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 10px;
            cursor: pointer;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Patient Management</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Phone Number</th>
        <th>Date of Birth</th>
        <th>Allergy</th>
        <th>Age</th>
        <th>Medicine Prescribed</th>
        <th>Vaccination Status</th>
        <th>Corona Result</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= htmlspecialchars($row['phone_number']) ?></td>
            <td><?= htmlspecialchars($row['dob']) ?></td>
            <td><?= htmlspecialchars($row['allergy']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['medicine_prescribed'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['vaccination_status'] ?? 'Pending') ?></td>
            <td><?= htmlspecialchars($row['corona_result'] ?? 'Unknown') ?></td>
            <td>
                <button class="action-btn edit-btn" onclick="openUpdateModal(
                    '<?= htmlspecialchars($row['id']) ?>',
                    '<?= htmlspecialchars($row['patient_name']) ?>',
                    '<?= htmlspecialchars($row['phone_number']) ?>',
                    '<?= htmlspecialchars($row['dob']) ?>',
                    '<?= htmlspecialchars($row['allergy']) ?>',
                    '<?= htmlspecialchars($row['age']) ?>',
                    '<?= htmlspecialchars($row['medicine_prescribed']) ?>',
                    '<?= htmlspecialchars($row['vaccination_status']) ?>',
                    '<?= htmlspecialchars($row['corona_result']) ?>'
                )">Update Details</button>
            </td>
        </tr>
    <?php } ?>
</table>

<div id="update-modal" class="modal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeUpdateModal()">Close</button>
        <form method="POST" action="">
            <input type="hidden" id="update-id" name="id">

            <label>Patient Name:</label>
            <input type="text" id="update-name" name="patient_name" required>

            <label>Phone Number:</label>
            <input type="text" id="update-phone" name="phone_number" required>

            <label>Date of Birth:</label>
            <input type="date" id="update-dob" name="dob" required>

            <label>Allergy:</label>
            <input type="text" id="update-allergy" name="allergy">

            <label>Age:</label>
            <input type="number" id="update-age" name="age" required>

            <label>Medicine Prescribed:</label>
            <input type="text" id="update-medicine" name="medicine_prescribed">

            <label>Vaccination Status:</label>
            <select id="update-vaccination" name="vaccination_status">
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
            </select>

            <label>Corona Result:</label>
            <select id="update-corona" name="corona_result">
                <option value="Negative">Negative</option>
                <option value="Positive">Positive</option>
            </select>

            <button type="submit" class="save-btn" name="update_patient">Update Details</button>
        </form>
    </div>
</div>

<div id="overlay" class="overlay"></div>

<script>
    function openUpdateModal(id, name, phone, dob, allergy, age, medicine, vaccination, corona) {
        document.getElementById('update-id').value = id;
        document.getElementById('update-name').value = name;
        document.getElementById('update-phone').value = phone;
        document.getElementById('update-dob').value = dob;
        document.getElementById('update-allergy').value = allergy;
        document.getElementById('update-age').value = age;
        document.getElementById('update-medicine').value = medicine || '';
        document.getElementById('update-vaccination').value = vaccination || 'Pending';
        document.getElementById('update-corona').value = corona || 'Negative';
        document.getElementById('update-modal').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closeUpdateModal() {
        document.getElementById('update-modal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
</script>

</body>
</html>
