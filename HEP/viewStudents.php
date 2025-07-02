<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch all classes for the lecturer based on email
$query = "SELECT tblclass.className, tblclassarms.classArmName, tblclassteacher.classId, tblclassteacher.classArmId
          FROM tblclassteacher
          INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
          INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
          WHERE tblclassteacher.emailAddress = (SELECT emailAddress FROM tblclassteacher WHERE Id = '$_SESSION[userId]')";

$rs = $conn->query($query);
$num = $rs->num_rows;
$classes = $rs->fetch_all(MYSQLI_ASSOC);

// Collect class IDs and class arm IDs
$classIds = array_column($classes, 'classId');
$classArmIds = array_column($classes, 'classArmId');
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
    function classArmDropdown(str) {
      if (str == "") {
          document.getElementById("txtHint").innerHTML = "";
          return;
      } else { 
          if (window.XMLHttpRequest) {
              // code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp = new XMLHttpRequest();
          } else {
              // code for IE6, IE5
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("txtHint").innerHTML = this.responseText;
              }
          };
          xmlhttp.open("GET","ajaxClassArms.php?cid="+str,true);
          xmlhttp.send();
      }
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
            <h1 class="h3 mb-0 text-gray-800"><strong>All Students in Course</strong></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Students in Course</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Select by Course</h6>
                  <h6 class="m-0 font-weight-bold text-danger">Note: <i>Please select a course to view!</i></h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-sm-4 mb-3 mb-sm-0">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                        <select name="classFilter" class="form-control mb-3">
                          <option value="">--Select Course--</option>
                          <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['classId'] . '-' . $class['classArmId']; ?>">
                              <?php echo $class['className'] . ' - ' . $class['classArmName']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="filter" class="btn btn-primary">View Students</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Students In Class</h6>
                      <button class="btn btn-secondary no-print" onclick="printDiv('printableArea')">Print</button>
                    </div>
                    <div class="table-responsive p-3" id="printableArea">
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
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (isset($_POST['filter']) && !empty($_POST['classFilter'])) {
                            list($filterClassId, $filterClassArmId) = explode('-', $_POST['classFilter']);

                            $query = "SELECT tblstudents.Id, tblclass.className, tblclassarms.classArmName, tblstudents.firstName,
                                      tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber, tblstudents.dateCreated
                                      FROM tblstudents
                                      INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                                      INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
                                      WHERE tblstudents.classId = '$filterClassId' AND tblstudents.classArmId = '$filterClassArmId'";

                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if ($num > 0) { 
                              while ($rows = $rs->fetch_assoc()) {
                                $sn++;
                                echo "
                                <tr>
                                  <td>".$sn."</td>
                                  <td>".$rows['firstName']."</td>
                                  <td>".$rows['lastName']."</td>
                                  <td>".$rows['otherName']."</td>
                                  <td>".$rows['admissionNumber']."</td>
                                  <td>".$rows['className']."</td>
                                  <td>".$rows['classArmName']."</td>
                                </tr>";
                              }
                            } else {
                              echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
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
          <!--Row-->
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include "Includes/footer.php";?>
      <!-- Footer -->
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
