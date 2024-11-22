<?php
require('db.php');

$sql = 'SELECT * FROM patient';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        td {
            font-size: 14px;
        }

        .action-btn {
            padding: 8px 12px;
            font-size: 14px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 5px;
        }

        .edit-btn {
            background-color: #28a745; /* Green */
        }

        .delete-btn {
            background-color: #dc3545; /* Red */
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-content label {
            display: block;
            margin: 10px 0 5px;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-content button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .save-btn {
            background-color: #28a745;
            color: white;
        }

        .close-btn {
            background-color: #dc3545;
            color: white;
            float: right;
        }

        .save-btn:hover, .close-btn:hover {
            opacity: 0.9;
        }
    </style>

    <script>
        function openEditModal(id, name, phone, dob, allergy, age) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-dob').value = dob;
            document.getElementById('edit-allergy').value = allergy;
            document.getElementById('edit-age').value = age;
            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function confirmDelete(url) {
            if (confirm('Are you sure you want to delete this patient?')) {
                window.location.href = url;
            }
        }
    </script>
    ";

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Phone Number</th>
            <th>Date of Birth</th>
            <th>Allergy</th>
            <th>Age</th>
            <th>Actions</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
        echo "<td>" . htmlspecialchars($row['allergy']) . "</td>";
        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
        echo "<td>
                <button class='action-btn edit-btn' onclick=\"openEditModal(
                    '" . htmlspecialchars($row['id']) . "',
                    '" . htmlspecialchars($row['patient_name']) . "',
                    '" . htmlspecialchars($row['phone_number']) . "',
                    '" . htmlspecialchars($row['dob']) . "',
                    '" . htmlspecialchars($row['allergy']) . "',
                    '" . htmlspecialchars($row['age']) . "'
                )\">Edit</button>
                <button class='action-btn delete-btn' onclick=\"confirmDelete('delete_patient.php?id=" . htmlspecialchars($row['id']) . "')\">Delete</button>
              </td>";
        echo "</tr>";
    }
    echo "</table>";

    // Modal for editing
    echo "
    <div id='edit-modal' class='modal'>
        <div class='modal-content'>
            <button class='close-btn' onclick='closeEditModal()'>Close</button>
            <form method='POST' action='update_patient.php'>
                <input type='hidden' id='edit-id' name='id'>
                <label>Patient Name:</label>
                <input type='text' id='edit-name' name='patient_name' required>
                <label>Phone Number:</label>
                <input type='text' id='edit-phone' name='phone_number' required>
                <label>Date of Birth:</label>
                <input type='date' id='edit-dob' name='dob' required>
                <label>Allergy:</label>
                <input type='text' id='edit-allergy' name='allergy'>
                <label>Age:</label>
                <input type='number' id='edit-age' name='age' required>
                <button type='submit' class='save-btn'>Save Changes</button>
            </form>
        </div>
    </div>
    ";
} else {
    echo "<p class='no-data'>No patients found.</p>";
}

// Close the connection
$conn->close();
?>
