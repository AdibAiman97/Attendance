<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$classId = intval($_GET['classId']);

$query = "SELECT DISTINCT firstName, lastName, admissionNumber FROM tblstudents WHERE classId = '$classId'";
$rs = $conn->query($query);

echo "<option value=''>--Select Student--</option>";
while ($row = $rs->fetch_assoc()) {
    echo "<option value='" . $row['admissionNumber'] . "'>" . $row['firstName'] . " " . $row['lastName'] . "</option>";
}
?>
