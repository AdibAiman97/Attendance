<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch classes and class arms for the lecturer using email
$query = "SELECT tblclass.className, tblclassarms.classArmName, tblclass.Id as classId, tblclassarms.Id as classArmId 
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Download Attendance Report</strong></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Download Attendance Report</li>
            </ol>
          </div>

          <!-- Filter Section -->
          <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Selection by Course and Date</h6>
            </div>
            <div class="card-body">
              <form method="post" action="">
                <div class="form-group row">
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-control-label">Course<span class="text-danger ml-2">*</span></label>
                    <select name="classId" id="classId" class="form-control">
                      <option value="">--Select Course--</option>
                      <?php
                      foreach ($classData as $classInfo) {
                          $selected = (isset($_POST['classId']) && $_POST['classId'] == $classInfo['classId']) ? "selected" : "";
                          echo "<option value='{$classInfo['classId']}' $selected>{$classInfo['className']} - {$classInfo['classArmName']}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-control-label">Date Type<span class="text-danger ml-2">*</span></label>
                    <select name="dateType" id="dateType" class="form-control" onchange="typeDropDown(this.value)">
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
                      <option value="Evening">Evening</option>
                    </select>
                  </div>
                </div>
                <div id="txtHint">
                  <!-- Date inputs will be displayed here based on date type selection -->
                </div>
                <button type="submit" name="filter" class="btn btn-primary">View Attendance</button>
              </form>
            </div>
          </div>

          <!-- Filtered Data Table -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Attendance Record</h6>
                </div>
                <div class="card-body">
                  <form method="post" action="download.php">
                    <div class="table-responsive">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
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
                          if (isset($_POST['filter'])) {
                            $classId = $_POST['classId'];
                            $dateType = $_POST['dateType'];
                            $sessionType = $_POST['sessionType'];
                            $query = "";

                            if ($dateType == "1") { // Single Date
                                $singleDate = $_POST['singleDate'];
                                $query = "
                                    SELECT DISTINCT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, 
                                    tblattendance.timeTaken, tblclass.className, tblclassarms.classArmName, 
                                    tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                                    tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, 
                                    tblstudents.admissionNumber, tblattendance.sessionType
                                    FROM tblattendance
                                    INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                    INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                    INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                                    INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                                    INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                                    WHERE tblattendance.dateTimeTaken = '$singleDate' 
                                    AND tblattendance.classId = '$classId'
                                ";

                            } elseif ($dateType == "2") { // Date Range
                                $fromDate = $_POST['fromDate'];
                                $toDate = $_POST['toDate'];
                                $query = "
                                    SELECT DISTINCT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, 
                                    tblattendance.timeTaken, tblclass.className, tblclassarms.classArmName, 
                                    tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                                    tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, 
                                    tblstudents.admissionNumber, tblattendance.sessionType
                                    FROM tblattendance
                                    INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                                    INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                    INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                                    INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                                    INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                                    WHERE tblattendance.dateTimeTaken BETWEEN '$fromDate' AND '$toDate'
                                    AND tblattendance.classId = '$classId'
                                ";
                            }

                            // Filter by session type
                            if ($sessionType != "ALL") {
                                $query .= " AND tblattendance.sessionType = '$sessionType'";
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
                                  <td>{$rows['otherName']}</td>
                                  <td>{$rows['admissionNumber']}</td>
                                  <td>{$rows['className']}</td>
                                  <td>{$rows['classArmName']}</td>
                                  <td>{$rows['sessionType']}</td>
                                  <td>{$rows['sessionName']}</td>
                                  <td>{$rows['termName']}</td>
                                  <td style='background-color:$colour'>$status</td>
                                  <td>{$rows['dateTimeTaken']}</td>
                                  <td>{$rows['timeTaken']}</td>
                                </tr>";
                              }

                              $attendancePercentage = ($presentCount / $totalCount) * 100;
                              echo "<tr><td colspan='11' style='text-align: center;'><b>Total Present: $presentCount/$totalCount</b></td></tr>";
                              echo "<tr><td colspan='11' style='text-align: center;'><b>Attendance Percentage: " . round($attendancePercentage, 2) . "%</b></td></tr>";
                            } else {
                              echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <input type="hidden" name="classId" value="<?php echo isset($_POST['classId']) ? $_POST['classId'] : ''; ?>">
                    <input type="hidden" name="sessionType" value="<?php echo isset($_POST['sessionType']) ? $_POST['sessionType'] : ''; ?>">
                    <input type="hidden" name="dateType" value="<?php echo isset($_POST['dateType']) ? $_POST['dateType'] : ''; ?>">
                    <input type="hidden" name="singleDate" value="<?php echo isset($_POST['singleDate']) ? $_POST['singleDate'] : ''; ?>">
                    <input type="hidden" name="fromDate" value="<?php echo isset($_POST['fromDate']) ? $_POST['fromDate'] : ''; ?>">
                    <input type="hidden" name="toDate" value="<?php echo isset($_POST['toDate']) ? $_POST['toDate'] : ''; ?>">
                    <br>
                    <button type="submit" name="download" class="btn btn-primary">Download Excel</button>
                  </form>
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
