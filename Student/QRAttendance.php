<?php
include '../Includes/dbcon.php';

if (isset($_GET['classId']) && isset($_GET['classArmId']) && isset($_GET['sessionType']) && isset($_GET['ts'])) {
    $classId = $_GET['classId'];
    $classArmId = $_GET['classArmId'];
    $sessionType = $_GET['sessionType']; // Get the session type from the URL
    $timestamp = $_GET['ts'];

    // Set the expiration time (e.g., 1 minute = 60 seconds)
    $expirationTime = 120;

    // Check if the timestamp is still valid
    $currentTime = time();
    if (($currentTime - $timestamp) > $expirationTime) {
        echo "This link has expired!";
        exit();
    }

} else {
    echo "Invalid Class Information, Session, or Timestamp!";
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

    // Check if the student exists in the class with the correct session type
    $checkStudentQuery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE admissionNumber = '$admissionNo' AND classId = '$classId' AND classArmId = '$classArmId'");
    if (mysqli_num_rows($checkStudentQuery) > 0) {

        // Check if the student has an attendance record for today in the specific session
        $checkAttendanceQuery = mysqli_query($conn, "SELECT * FROM tblattendance WHERE admissionNo = '$admissionNo' AND dateTimeTaken = '$dateTaken' AND classId = '$classId' AND classArmId = '$classArmId' AND sessionTermId = '$sessionTermId' AND sessionType = '$sessionType'");
        
        if (mysqli_num_rows($checkAttendanceQuery) > 0) {
            // Fetch the current attendance status
            $attendanceRow = mysqli_fetch_assoc($checkAttendanceQuery);
            $currentStatus = $attendanceRow['status'];

            if ($currentStatus == '1') {
                // If the student is already marked as present for the session
                $errorMessage = "Attendance already taken for today in this session!";
            } else {
                // If the student was marked absent, update to present
                $updateAttendanceQuery = mysqli_query($conn, "UPDATE tblattendance SET status = '1', timeTaken = '$timeTaken' WHERE admissionNo = '$admissionNo' AND dateTimeTaken = '$dateTaken' AND classId = '$classId' AND classArmId = '$classArmId' AND sessionTermId = '$sessionTermId' AND sessionType = '$sessionType'");
                
                if ($updateAttendanceQuery) {
                    $successMessage = "Attendance updated to present for this session!";
                } else {
                    $errorMessage = "Error updating attendance. Please try again.";
                }
            }
        } else {
            // No attendance record exists, so insert a new record with present status
            $insertAttendanceQuery = mysqli_query($conn, "INSERT INTO tblattendance (admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken, timeTaken, sessionType) VALUES ('$admissionNo', '$classId', '$classArmId', '$sessionTermId', '1', '$dateTaken', '$timeTaken', '$sessionType')");

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

// Automatically mark absent students (those who didn't submit attendance for the session)
$checkAbsentStudents = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$classId' AND classArmId = '$classArmId'");
while ($studentRow = mysqli_fetch_assoc($checkAbsentStudents)) {
    $studentAdmissionNo = $studentRow['admissionNumber'];

    // Check if the student was marked present today in the session
    $checkAttendanceQuery = mysqli_query($conn, "SELECT * FROM tblattendance WHERE admissionNo = '$studentAdmissionNo' AND dateTimeTaken = '$dateTaken' AND classId = '$classId' AND classArmId = '$classArmId' AND sessionTermId = '$sessionTermId' AND sessionType = '$sessionType'");

    if (mysqli_num_rows($checkAttendanceQuery) == 0) {
        // If no attendance record exists for this student in the session today, mark as absent
        mysqli_query($conn, "INSERT INTO tblattendance (admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken, timeTaken, sessionType) VALUES ('$studentAdmissionNo', '$classId', '$classArmId', '$sessionTermId', '0', '$dateTaken', '00:00:00', '$sessionType')");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Form</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/custom-styles.css" rel="stylesheet"> <!-- Custom CSS File -->
  <link href="../img/attnlg.png" rel="icon">
  <style>
    body {
      background-color: #1B1F38;
      color: white;
      font-family: 'Arial', sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background: #2c2f39;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
      border: 2px solid #ffc107; /* Border color matching the button color */
    }
    h2 {
      text-align: center;
      color: #ffc107; /* Header color */
    }
    .submit-btn {
      background-color: #ffc107;
      color: black;
      font-weight: bold;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .submit-btn:hover {
      background-color: #e0a800; /* Hover color */
    }
    .success-message, .error-message {
      margin: 20px 0;
      font-weight: bold;
      text-align: center;
    }
    .success-message {
      color: green;
    }
    .error-message {
      color: red;
    }
    .form-control {
      background-color: rgba(255, 255, 255, 0.1);
      border: none;
      border-radius: 30px;
      color: #fff;
    }
    .form-control::placeholder {
      color: #ccc;
    }
  </style>
</head>
<body>
  <br>
  <br>
  <br>
  <br>
<div class="text-center">
<img src="../img/logo/attnlg.png" style="width:250px;height:100px">
</div>
<br>
  <div class="container">
    <div class="form-container">
      <h2>Mark Your Attendance</h2>

      <!-- Display success or error messages -->
      <?php if (isset($successMessage)) { ?>
        <div class="success-message" id="successMessage"><?php echo $successMessage; ?></div>
      <?php } elseif (isset($errorMessage)) { ?>
        <div class="error-message" id="errorMessage"><?php echo $errorMessage; ?></div>
      <?php } ?>

      <form method="post" action="">
        <div class="form-group">
          <label for="admissionNo">Enter ID Student:</label>
          <input type="text" name="admissionNo" id="admissionNo" class="form-control" placeholder="Your ID Student" required>
        </div>
        <button type="submit" name="submitAttendance" class="submit-btn btn btn-block">Submit Attendance</button>
      </form>

      <!-- Back to Main System Button -->
      <div class="text-center mt-4">
        <a href="http://localhost/STDC-Attendance-System/Student/index.php" class="btn btn-secondary btn-block">Back to Main System</a>
      </div>
    </div>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Function to fade out messages after 2 seconds
    $(document).ready(function() {
      setTimeout(function() {
        $('#successMessage, #errorMessage').fadeOut();
      }, 2000);
    });
  </script>

</body>
</html>
