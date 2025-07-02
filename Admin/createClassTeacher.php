<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];
    $classArmId = $_POST['classArmId'];
    $role = $_POST['role'];
    $dateCreated = date("Y-m-d");

    $query = mysqli_query($conn, "select * from tblclassteacher where emailAddress ='$emailAddress'");
    $ret = mysqli_fetch_array($query);

    $sampPass = "pass123";
    $sampPass_2 = md5($sampPass);

    if ($ret = 0) { 
        echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=exists';</script>";
    } else {
        $query = mysqli_query($conn, "INSERT into tblclassteacher(firstName, lastName, emailAddress, password, phoneNo, classId, classArmId, dateCreated, role) 
            value('$firstName', '$lastName', '$emailAddress', '$sampPass_2', '$phoneNo', '$classId', '$classArmId', '$dateCreated', '$role')");

        if ($query) {
            $qu = mysqli_query($conn, "update tblclassarms set isAssigned='1' where Id ='$classArmId'");
            if ($qu) {
                echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=created';</script>";
            } else {
                echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=error';</script>";
            }
        } else {
            echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=error';</script>";
        }
    }
}

//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];

    $query = mysqli_query($conn, "select * from tblclassteacher where Id ='$Id'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if (isset($_POST['update'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $emailAddress = $_POST['emailAddress'];
        $phoneNo = $_POST['phoneNo'];
        $classId = $_POST['classId'];
        $classArmId = $_POST['classArmId'];
        $role = $_POST['role'];
        $dateCreated = date("Y-m-d");

        $query = mysqli_query($conn, "update tblclassteacher set firstName='$firstName', lastName='$lastName', emailAddress='$emailAddress', phoneNo='$phoneNo', classId='$classId', classArmId='$classArmId', role='$role' where Id='$Id'");
        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=updated';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=error';</script>";
        }
    }
}

//--------------------DELETE------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['classArmId']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];
  $classArmId = $_GET['classArmId'];

  $query = mysqli_query($conn, "DELETE FROM tblclassteacher WHERE Id='$Id'");
  if ($query == TRUE) {
      $qu = mysqli_query($conn, "update tblclassarms set isAssigned='0' where Id ='$classArmId'");
      if ($qu) {
          echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=deleted';</script>";
      } else {
          echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=error';</script>";
      }
  } else {
      echo "<script type='text/javascript'>window.location.href='createClassTeacher.php?msg=error';</script>";
  }
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
  <?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print {
        display: none;
      }
    }
    .notification {
      display: inline-block;
      margin-left: 20px;
      z-index: 1000;
    }
  </style>
  <script>
    function classArmDropdown(str) {
      if (str == "") {
          document.getElementById("txtHint").innerHTML = "";
          return;
      } else { 
          if (window.XMLHttpRequest) {
              xmlhttp = new XMLHttpRequest();
          } else {
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

    function showNotification(message, type) {
      var notification = document.createElement('div');
      notification.className = `alert alert-${type} notification`;
      notification.innerText = message;
      document.getElementById('notification-area').appendChild(notification);
      setTimeout(function() {
        notification.remove();
      }, 3000);
    }

    window.onload = function() {
      const urlParams = new URLSearchParams(window.location.search);
      const msg = urlParams.get('msg');
      if (msg === 'created') {
        showNotification('Record created successfully!', 'success');
      } else if (msg === 'updated') {
        showNotification('Record updated successfully!', 'success');
      } else if (msg === 'deleted') {
        showNotification('Record deleted successfully!', 'success');
      } else if (msg === 'exists') {
        showNotification('This Email Address Already Exists!', 'danger');
      } else if (msg === 'error') {
        showNotification('An error occurred!', 'danger');
      }
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Create Course Staff</strong></h1>
            <div id="notification-area"></div>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Course Staff</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Course Staff</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    
                   <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName" value="<?php echo isset($row['firstName']) ? $row['firstName'] : '';?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="lastName" value="<?php echo isset($row['lastName']) ? $row['lastName'] : '';?>" id="exampleInputLastName">
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress" value="<?php echo isset($row['emailAddress']) ? $row['emailAddress'] : '';?>" id="exampleInputEmail">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Phone Number<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="phoneNo" value="<?php echo isset($row['phoneNo']) ? $row['phoneNo'] : '';?>" id="exampleInputPhone">
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        if ($result->num_rows > 0) {
                            echo ' <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                            echo'<option value="">--Select Course--</option>';
                            while ($row_class = $result->fetch_assoc()) {
                                $selected = (isset($row['classId']) && $row_class['Id'] == $row['classId']) ? 'selected' : '';
                                echo'<option value="'.$row_class['Id'].'" '.$selected.'>'.$row_class['className'].'</option>';
                            }
                            echo '</select>';
                        }
                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Sector<span class="text-danger ml-2">*</span></label>
                        <div id="txtHint">
                          <?php 
                          if (isset($row['classArmId'])) {
                              echo "<option value='".$row['classArmId']."'>".$row['classArmName']."</option>";
                          } else {
                              echo "<option value=''> </option>";
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Role<span class="text-danger ml-2">*</span></label>
                        <?php
    // Fetching roles from tblclassteacher and ordering them by role ASC
    $qry = "SELECT DISTINCT role FROM tblclassteacher ORDER BY role ASC";
    $result = $conn->query($qry);

    if ($result->num_rows > 0) {
        echo '<select required name="role" onchange="roleDropdown(this.value)" class="form-control mb-3">';
        echo '<option value="">--Select Role--</option>';

        // Fetching each row and setting the selected value
        while ($row_class = $result->fetch_assoc()) {
            // Check if the selected role matches and set 'selected' attribute accordingly
            $selected = (isset($row['role']) && $row_class['role'] == $row['role']) ? 'selected' : '';

            // Output each option with the role ID as the value
            echo '<option value="'.$row_class['role'].'" '.$selected.'>'.$row_class['role'].'</option>';
        }

        echo '</select>';
    }
?>
                      </div>
                      </div>
                    <?php
                    if (isset($Id)) {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Class Lecturers</h6>
                      <button class="btn btn-secondary no-print" onclick="printDiv('printableArea')">Print</button>
                    </div>
                    <div class="table-responsive p-3" id="printableArea">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Phone No</th>
                            <th>Course</th>
                            <th>Sector Name</th>
                            <th>Role</th>
                            <th>Date Created</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT tblclassteacher.Id, tblclass.className, tblclassarms.classArmName, tblclassarms.Id AS classArmId, tblclassteacher.firstName,
                          tblclassteacher.lastName, tblclassteacher.emailAddress, tblclassteacher.phoneNo, tblclassteacher.role, tblclassteacher.dateCreated
                          FROM tblclassteacher
                          LEFT JOIN tblclass ON tblclass.Id = tblclassteacher.classId
                          LEFT JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn=0;
                          if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                                $sn = $sn + 1;
                                $className = isset($rows['className']) ? $rows['className'] : "Not Assigned";
                                $classArmName = isset($rows['classArmName']) ? $rows['classArmName'] : "Not Assigned";
                                echo"
                                <tr>
                                    <td>".$sn."</td>
                                    <td>".$rows['firstName']."</td>
                                    <td>".$rows['lastName']."</td>
                                    <td>".$rows['emailAddress']."</td>
                                    <td>".$rows['phoneNo']."</td>
                                    <td>".$className."</td>
                                    <td>".$classArmName."</td>
                                    <td>".$rows['role']."</td>
                                    <td>".$rows['dateCreated']."</td>
                                    <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                    <td><a href='?action=delete&Id=".$rows['Id']."&classArmId=".$rows['classArmId']."'><i class='fas fa-fw fa-trash'></i></a></td>
                                </tr>";
                              }
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                        }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
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