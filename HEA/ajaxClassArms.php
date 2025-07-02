<?php
include '../Includes/dbcon.php';

if (isset($_GET['cid'])) {
    $classId = $_GET['cid'];

    $query = "SELECT classArmName, Id FROM tblclassarms WHERE classId = '$classId'";
    $result = $conn->query($query);

    echo "<option value=''>--Select Class Section--</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['Id']}'>{$row['classArmName']}</option>";
    }
}
?>
