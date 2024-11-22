<?php
require('db.php');

// Check if a button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action']; // 'accept' or 'reject'

    if ($action === 'accept') {
        $update_sql = "UPDATE appointment SET status='Accepted' WHERE id=$id";
    } elseif ($action === 'reject') {
        $update_sql = "UPDATE appointment SET status='Rejected' WHERE id=$id";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "<p style='text-align: center; color: green;'>Appointment $action successfully!</p>";
    } else {
        echo "<p style='text-align: center; color: red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch details from the database
$sql = 'SELECT * FROM appointment';
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

        td button {
            padding: 8px 16px;
            font-size: 14px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        td button[type='submit'][value='accept'] {
            background-color: #28a745; /* Green */
        }

        td button[type='submit'][value='reject'] {
            background-color: #dc3545; /* Red */
        }

        td button:hover {
            opacity: 0.9;
        }

        td {
            font-size: 14px;
        }

        .status-accepted {
            color: #28a745;
            font-weight: bold;
        }

        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            margin: 50px auto;
            font-size: 18px;
            color: #888;
        }
    </style>
    ";

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Phone Number</th>
            <th>Allergy</th>
            <th>Vaccination Name</th>
            <th>Appointment Date</th>
            <th>Status/Actions</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['allergy']) . "</td>";
        echo "<td>" . htmlspecialchars($row['vaccination_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";

        // Check the current status
        if ($row['status'] === 'Accepted') {
            echo "<td class='status-accepted'>Accepted</td>";
        } elseif ($row['status'] === 'Rejected') {
            echo "<td class='status-rejected'>Rejected</td>";
        } else {
            echo "<td>
                    <form method='POST' action=''>
                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "' />
                        <button type='submit' name='action' value='accept'>Accept</button>
                        <button type='submit' name='action' value='reject'>Reject</button>
                    </form>
                  </td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='no-data'>No appointments found.</p>";
}

// Close the connection
$conn->close();
?>
