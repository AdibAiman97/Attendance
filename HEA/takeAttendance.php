<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
require '../phpqrcode/qrlib.php'; // Ensure you have the QR code generation library

// Fetch classes and class arms for the lecturer using email
$query = "SELECT DISTINCT tblclass.className, tblclass.Id as classId 
          FROM tblclassteacher
          INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
          WHERE tblclassteacher.emailAddress = '$_SESSION[emailAddress]'";
$rs = $conn->query($query);
$classData = [];
while ($row = $rs->fetch_assoc()) {
    $classData[] = $row;
}

// Session and Term
$querey = mysqli_query($conn, "SELECT * FROM tblsessionterm WHERE isActive ='1'");
$rwws = mysqli_fetch_array($querey);
$sessionTermId = $rwws['Id'];


date_default_timezone_set('Asia/Kuala_Lumpur');

$dateTaken = date("Y-m-d");
$timeTaken = date("H:i:s"); // Get the current time



// Generate QR Code for attendance
if (isset($_POST['generateQR']) || isset($_POST['hiddenClassId'])) {
  $selectedClassId = $_POST['classId'] ?? $_POST['hiddenClassId'];
  $selectedClassArmId = $_POST['classArmId'] ?? $_POST['hiddenClassArmId'];
  $sessionType = $_POST['sessionType']; // Fetch the session type (Morning/Evening)

  // Fetch the class name for the selected class ID
  $classNameQuery = "SELECT className FROM tblclass WHERE Id = '$selectedClassId'";
  $classNameResult = $conn->query($classNameQuery);
  $classNameRow = $classNameResult->fetch_assoc();
  $className = $classNameRow['className'];
  
  // Generate unique QR code with timestamp
  $timestamp = time(); // Unique time-based component
  $url = "http://localhost/STDC-Attendance-System/Student/QRAttendance.php?classId=" . $selectedClassId . "&classArmId=" . $selectedClassArmId . "&sessionType=" . urlencode($sessionType) . "&ts=" . $timestamp;
  
  // Generate the QR code image
  $qrImage = 'qr_codes/attendance_' . $selectedClassId . '_' . $selectedClassArmId . '_' . $sessionType . '_' . $timestamp . '.png';
  QRcode::png($url, $qrImage, 'L', 8, 2);

  $generated = true;
}


// Process attendance delete
if (isset($_POST['delete'])) {
    foreach ($classData as $classInfo) {
        $classId = $classInfo['classId'];
        $delete = mysqli_query($conn, "DELETE FROM tblattendance WHERE classId = '$classId' AND dateTimeTaken='$dateTaken'");
    }
    if ($delete) {
        echo "<script type='text/javascript'>window.location.href='takeAttendance.php?msg=deleted';</script>";
    } else {
        echo "<script type='text/javascript'>window.location.href='takeAttendance.php?msg=error';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STDC E-HADIR</title>
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
      background-color: #2c2f39;
      padding: 30px;
      border-radius: 15px;
      border: 2px solid #ffc107; /* Border color matching the button color */
      max-width: 600px;
      margin: auto; /* Center the container */
    }

    .btn-primary {
      background-color: #ffc107; /* Primary color */
      border: none;
      border-radius: 30px;
    }

    .btn-primary:hover {
      background-color: #e0a800; /* Hover color */
    }

    h2, h4 {
      text-align: center;
      color: #ffc107; /* Header color */
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

    img {
      margin: 20px 0;
    }

    .qr-image {
      border: 2px solid #ffc107; /* QR code image border color */
      border-radius: 10px; /* Rounded corners for the QR code image */
      padding: 10px; /* Space between border and image */
      display: inline-block; /* Center the image */
    }

    .red {
    color: red;
}

  </style>
</head>
<body>
<br>
<div class="text-center">
<img src="../img/logo/attnlg.png" style="width:250px;height:100px">
</div>
  <div class="container">
    <div class="text-center">
    </div>
    <!-- Display the title with the current date -->
    <h2>Generate Attendance QR Code (Date: <?php echo date("d-M-Y", strtotime($dateTaken)),", ","Time: "; echo date("H:i:s", strtotime($timeTaken)); ?>)</h2>

    
    <br>
    <form method="post" action="">
    <div class="form-group">
        <label for="classId">Select Course:</label>
        <select name="classId" id="classId" class="form-control" required>
            <option value="">--Select Course--</option>
            <?php
            foreach ($classData as $classInfo) {
                // Check if the current option is the selected one and set it as selected
                $selected = (isset($_POST['classId']) && $_POST['classId'] == $classInfo['classId']) ? "selected" : "";
                echo "<option value='{$classInfo['classId']}' $selected>{$classInfo['className']}</option>";
            }
            ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="classArmId">Select Course Section:</label>
        <select name="classArmId" id="classArmId" class="form-control" required>
            <option value="">--Select Course Section--</option>
            <!-- This will be dynamically populated via AJAX, see below -->
        </select>
    </div>
    <div class="form-group">
        <label for="sessionType">Select Session (Morning/Evening):</label>
        <select name="sessionType" id="sessionType" class="form-control" required>
            <option value="">--Select Session--</option>
            <option value="Morning" <?php if (isset($_POST['sessionType']) && $_POST['sessionType'] == 'Morning') echo 'selected'; ?>>Morning</option>
            <option value="Evening" <?php if (isset($_POST['sessionType']) && $_POST['sessionType'] == 'Evening') echo 'selected'; ?>>Evening</option>
        </select>
    </div>
    <input type="hidden" id="hiddenClassId" name="hiddenClassId" value="<?php echo isset($_POST['classId']) ? $_POST['classId'] : ''; ?>">
    <input type="hidden" id="hiddenClassArmId" name="hiddenClassArmId" value="<?php echo isset($_POST['classArmId']) ? $_POST['classArmId'] : ''; ?>">

    <button type="submit" name="generateQR" class="btn btn-primary btn-block">Generate QR Code</button>
</form>



    <!-- Back to Main System Button -->
    <div class="text-center mt-4">
      <a href="./" class="btn btn-secondary btn-block">Back to Main System</a>
    </div>
  </div>

  <!-- Display the generated QR code -->
  <?php if (isset($generated)) { ?>
    <div class="text-center mt-4">
        <h4>Scan this QR code to submit attendance for: <strong style="color: red;"><?php echo $className; ?></strong></h4>
        <br>
        <div class="qr-image">
            <img id="qrCodeImage" src="<?php echo $qrImage; ?>" alt="Attendance QR Code" style="max-width: 100%;">
        </div>
    </div>
<?php } ?>


  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>
    // AJAX to dynamically fetch class arms based on selected class
    $('#classId').on('change', function() {
    var classId = $(this).val();
    if (classId !== "") {
        $.ajax({
            url: 'ajaxClassArms.php',
            method: 'GET',
            data: {cid: classId},
            success: function(data) {
                $('#classArmId').html(data);
                // Set the previously selected class arm, if available
                var selectedClassArm = $('#hiddenClassArmId').val();
                if (selectedClassArm) {
                    $('#classArmId').val(selectedClassArm);
                }
            }
        });
    } else {
        $('#classArmId').html('<option value="">Select Course Section</option>');
    }
});

// On page load, trigger change event to populate class arm if the class is already selected
$(document).ready(function() {
    var selectedClassId = $('#classId').val();
    if (selectedClassId) {
        $('#classId').trigger('change');
    }
});

  </script>
<script>
// Automatically resubmit the form to regenerate the QR code every minute
setInterval(function() {
    document.querySelector('form').submit();
}, 10000); // 60 seconds

// Store the selected classId and classArmId in hidden fields to maintain state across refreshes
document.getElementById('classId').addEventListener('change', function() {
    document.getElementById('hiddenClassId').value = this.value;
});

document.getElementById('classArmId').addEventListener('change', function() {
    document.getElementById('hiddenClassArmId').value = this.value;
});
</script>



</body>
</html>

