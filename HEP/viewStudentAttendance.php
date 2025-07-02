<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch classes and class arms for the lecturer using email
$query = "SELECT DISTINCT tblclass.className, tblclassarms.classArmName, tblclass.Id as classId, tblclassarms.Id as classArmId 
          FROM tblclassteacher
          INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
          INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
          WHERE tblclassteacher.emailAddress = '$_SESSION[emailAddress]'";
$rs = $conn->query($query);
$classData = [];
while ($row = $rs->fetch_assoc()) {
    $classData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="../img/attnlg.png" rel="icon">
  <title>STDC E-HADIR</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <style>
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>


  <script>
    function fetchStudentsByClass(classId) {
      if (classId == "") {
          document.getElementById("studentDropdown").innerHTML = "<option value=''>--Select Student--</option>";
          return;
      } else { 
          if (window.XMLHttpRequest) {
              xmlhttp = new XMLHttpRequest();
          } else {
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("studentDropdown").innerHTML = this.responseText;
              }
          };
          xmlhttp.open("GET","ajaxFetchStudents.php?classId="+classId,true);
          xmlhttp.send();
      }
    }

    function typeDropDown(val) {
      var html = "";
      if(val == "1") {
          html += '<div class="form-group row"><div class="col-sm-4 mb-3 mb-sm-0"><label class="form-control-label">Date<span class="text-danger ml-2">*</span></label>';
          html += '<input type="date" class="form-control" name="singleDate" required></div></div>';
      } else if(val == "2") {
          html += '<div class="form-group row"><div class="col-sm-4 mb-3 mb-sm-0"><label class="form-control-label">From Date<span class="text-danger ml-2">*</span></label>';
          html += '<input type="date" class="form-control" name="fromDate" required></div>';
          html += '<div class="col-sm-4 mb-3 mb-sm-0"><label class="form-control-label">To Date<span class="text-danger ml-2">*</span></label>';
          html += '<input type="date" class="form-control" name="toDate" required></div></div>';
      }
      document.getElementById("txtHint").innerHTML = html;
    }

    function printDiv(divName) {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
    }
  </script>
</head>
<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><strong>View Student Attendance</strong></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Student Attendance</li>
            </ol>
          </div>

          <!-- Filter Section -->
          <div class="card mb-4">
             <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Selection by Course and Student</h6>
            </div>
            <div class="card-body">
              <form method="post" action="">
                <div class="form-group row">
                  <!-- Class Filter -->
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-control-label">Course<span class="text-danger ml-2">*</span></label>
                    <select name="classId" id="classId" class="form-control" onchange="fetchStudentsByClass(this.value)" required>
                      <option value="">--Select Course--</option>
                      <?php
                      foreach ($classData as $classInfo) {
                          echo "<option value='{$classInfo['classId']}'>{$classInfo['className']} - {$classInfo['classArmName']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <!-- Student Filter -->
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-control-label">Student Name<span class="text-danger ml-2">*</span></label>
                    <select name="admissionNumber" id="studentDropdown" class="form-control" required>
                      <option value="">--Select Student--</option>
                    </select>
                  </div>
                </div>

                <!-- Date Filter -->
                <div class="form-group row">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-control-label">Date Type<span class="text-danger ml-2">*</span></label>
                    <select name="type" onchange="typeDropDown(this.value)" class="form-control mb-3" required>
                      <option value="">--Select--</option>
                      <option value="1">By Single Date</option>
                      <option value="2">By Date Range</option>
                    </select>
                  </div>
                      <!-- New Session Dropdown -->
                      <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-control-label">Session Type<span class="text-danger ml-2">*</span></label>
                    <select name="sessionType" id="sessionType" class="form-control">
                      <option value="">--Select Session--</option>
                      <option value="Morning">Morning</option>
                    </select>
                  </div>
                </div>
              

                <div id="txtHint"></div>

                <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
              </form>
            </div>
          </div>

          <!-- Attendance Table -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Attendance Record</h6>
                  <button class="btn btn-secondary no-print" onclick="printDiv('printableArea')">Print</button>
                </div>
                <div class="table-responsive p-3" id="printableArea">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>ID Student</th>
                        <th>Course</th>
                        <th>Sector Name</th>
                        <th>Session Type</th>
                        <th>Session</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (isset($_POST['view'])) {
                        $admissionNumber = $_POST['admissionNumber'];
                        $classId = $_POST['classId'];
                        $type = $_POST['type'];
                        $sessionType = $_POST['sessionType'];
                        $query = "";

                        if ($type == "1") { // Single Date Attendance
                          $singleDate = $_POST['singleDate'];
                          $query = "SELECT DISTINCT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblattendance.timeTaken, tblclass.className,
                                    tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                                    tblstudents.firstName, tblstudents.lastName, tblstudents.admissionNumber
                                    FROM tblattendance
                                    INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                    INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                    INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                                    INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                                    INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                                    WHERE tblattendance.dateTimeTaken = '$singleDate'
                                    AND tblattendance.admissionNo = '$admissionNumber'
                                    AND tblattendance.classId = '$classId'
                                    AND tblattendance.sessionType = '$sessionType'"; 
                        } else { // Date Range Attendance
                          $fromDate = $_POST['fromDate'];
                          $toDate = $_POST['toDate'];
                          $query = "SELECT DISTINCT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblattendance.timeTaken, tblclass.className,
                                    tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                                    tblstudents.firstName, tblstudents.lastName, tblstudents.admissionNumber
                                    FROM tblattendance
                                    INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                    INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                    INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                                    INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                                    INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                                    WHERE tblattendance.dateTimeTaken BETWEEN '$fromDate' AND '$toDate'
                                    AND tblattendance.admissionNo = '$admissionNumber'
                                     AND tblattendance.classId = '$classId'
                                    AND tblattendance.sessionType = '$sessionType'";
                        }

                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $sn = 0;
                        $status = "";
                        $presentCount = 0;
                        $totalCount = $num;

                        if ($num > 0) {
                          while ($rows = $rs->fetch_assoc()) {
                            if ($rows['status'] == '1') {
                              $status = "Present";
                              $colour = "#00FF00";
                              $presentCount++;
                            } else {
                              $status = "Absent";
                              $colour = "#FF0000";
                            }
                            $sn++;
                            echo "
                            <tr>
                              <td>$sn</td>
                              <td>{$rows['firstName']}</td>
                              <td>{$rows['lastName']}</td>
                              <td>{$rows['admissionNumber']}</td>
                              <td>{$rows['className']}</td>
                              <td>{$rows['classArmName']}</td>
                              <td>{$sessionType}</td>
                              <td>{$rows['sessionName']}</td>
                              <td>{$rows['termName']}</td>
                              <td style='background-color:$colour'>$status</td>
                              <td>{$rows['dateTimeTaken']}</td>
                              <td>{$rows['timeTaken']}</td>
                            </tr>";
                          }

                          $attendancePercentage = ($presentCount / $totalCount) * 100;
                          $absentCount = $totalCount - $presentCount;

                          echo "<div class='alert alert-info' style='background-color: navy;'><strong style='color: white;'>Attendance Present: $presentCount/$totalCount</strong></div>";
                          echo "<div class='alert alert-info' style='background-color: navy;'><strong style='color: white;'>Attendance Percentage: " . round($attendancePercentage, 2) . "%</strong></div>";
  
                          } else {
                            echo "<div class='alert alert-danger' role='alert'><strong style='color: white;'>No Record Found!</strong></div>";
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </div>
      <!-- Footer -->
      <?php include "Includes/footer.php";?>
      <!-- Footer -->
      </div>
      <!---Container Fluid-->
      </div>
    </div>
  </div>
  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>