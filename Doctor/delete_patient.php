<?php
require('db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM patient WHERE id=$id";

    if ($conn->query($sql)) {
        echo "Patient record deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
