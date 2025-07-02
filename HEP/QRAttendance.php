<?php
include '../Includes/dbcon.php';

if (isset($_GET['classId']) && isset($_GET['classArmId'])) {
    $classId = $_GET['classId'];
    $classArmId = $_GET['classArmId'];
} else {
    echo "Invalid Class Information!";
    exit();
}

// Fetch sessionTermId (active term)
$querey = mysqli_query($conn, "SELECT * FROM tblsessionterm WHERE isActive ='1'");
$rwws = mysqli_fetch_array($querey);
$sessionTermId = $rwws['Id'];

date_default_timezone_set('Asia/Kuala_Lumpur');

$dateTaken = date("Y-m-d");
$timeTaken = date("H:i:s"); // Get the current time

// Handle form submission for attendance
if (isset($_POST['submitAttendance'])) {
    $admissionNo = $_POST['admissionNo'];

    // Check if the student exists in the class
    $checkStudentQuery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE admissionNumber = '$admissionNo' AND classId = '$classId' AND classArmId = '$classArmId'");
    if (mysqli_num_rows($checkStudentQuery) > 0) {

        // Check if the student has an attendance record for today
        $checkAttendanceQuery = mysqli_query($conn, "SELECT * FROM tblattendance WHERE admissionNo = '$admissionNo' AND dateTimeTaken = '$dateTaken' AND classId = '$classId' AND classArmId = '$classArmId' AND sessionTermId = '$sessionTermId'");
        
        if (mysqli_num_rows($checkAttendanceQuery) > 0) {
            // Fetch the current attendance status
            $attendanceRow = mysqli_fetch_assoc($checkAttendanceQuery);
            $currentStatus = $attendanceRow['status'];

            if ($currentStatus == '1') {
                // If the student is already marked as present
                $errorMessage = "Attendance already taken for today!";
            } else {
                // If the student was marked absent, update to present
                $updateAttendanceQuery = mysqli_query($conn, "UPDATE tblattendance SET status = '1', timeTaken = '$timeTaken' WHERE admissionNo = '$admissionNo' AND dateTimeTaken = '$dateTaken' AND classId = '$classId' AND classArmId = '$classArmId' AND sessionTermId = '$sessionTermId'");
                
                if ($updateAttendanceQuery) {
                    $successMessage = "Attendance updated to present!";
                } else {
                    $errorMessage = "Error updating attendance. Please try again.";
                }
            }
        } else {
            // No attendance record exists, so insert a new record with present status
            $insertAttendanceQuery = mysqli_query($conn, "INSERT INTO tblattendance (admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken, timeTaken) VALUES ('$admissionNo', '$classId', '$classArmId', '$sessionTermId', '1', '$dateTaken', '$timeTaken')");

            if ($insertAttendanceQuery) {
                $successMessage = "Attendance marked successfully!";
            } else {
                $errorMessage = "Error inserting attendance. Please try again.";
            }
        }
    } else {
        $errorMessage = "Invalid admission number or student not in this class!";
    }
}

// Automatically mark absent students (those who didn't submit attendance)
$checkAbsentStudents = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$classId' AND classArmId = '$classArmId'");
while ($studentRow = mysqli_fetch_assoc($checkAbsentStudents)) {
    $studentAdmissionNo = $studentRow['admissionNumber'];

    // Check if the student was marked present today
    $checkAttendanceQuery = mysqli_query($conn, "SELECT * FROM tblattendance WHERE admissionNo = '$studentAdmissionNo' AND dateTimeTaken = '$dateTaken' AND classId = '$classId' AND classArmId = '$classArmId' AND sessionTermId = '$sessionTermId'");

    if (mysqli_num_rows($checkAttendanceQuery) == 0) {
        // If no attendance record exists for this student today, mark as absent
        mysqli_query($conn, "INSERT INTO tblattendance (admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken, timeTaken) VALUES ('$studentAdmissionNo', '$classId', '$classArmId', '$sessionTermId', '0', '$dateTaken', NULL)");
    }
}
?>

