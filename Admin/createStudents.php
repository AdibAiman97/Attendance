<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $firstName=$_POST['firstName'];
    $lastName=$_POST['lastName'];
    $otherName=$_POST['otherName'];
    $admissionNumber=$_POST['admissionNumber'];
    $classId=$_POST['classId'];
    $classArmId=$_POST['classArmId'];
    $dateCreated = date("Y-m-d");
   
    $query=mysqli_query($conn,"select * from tblstudents where admissionNumber ='$admissionNumber'");
    $ret=mysqli_fetch_array($query);

    $sampPass = "pass123";
    $sampPass_2 = md5($sampPass);

    if($ret < 0){ 
        echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=exists';</script>";
    }
    else{
        $query=mysqli_query($conn,"insert into tblstudents(firstName,lastName,otherName,admissionNumber,password,classId,classArmId,dateCreated) 
        value('$firstName','$lastName','$otherName','$admissionNumber','$sampPass_2','$classId','$classArmId','$dateCreated')");
        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=created';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=error';</script>";
        }
    }
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id= $_GET['Id'];

    $query=mysqli_query($conn,"select * from tblstudents where Id ='$Id'");
    $row=mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if(isset($_POST['update'])){
        $firstName=$_POST['firstName'];
        $lastName=$_POST['lastName'];
        $otherName=$_POST['otherName'];
        $admissionNumber=$_POST['admissionNumber'];
        $classId=$_POST['classId'];
        $classArmId=$_POST['classArmId'];
        $dateCreated = date("Y-m-d");

        $query=mysqli_query($conn,"update tblstudents set firstName='$firstName', lastName='$lastName', otherName='$otherName', admissionNumber='$admissionNumber', classId='$classId',classArmId='$classArmId' where Id='$Id'");
        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=updated';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=error';</script>";
        }
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id= $_GET['Id'];

    $query = mysqli_query($conn,"DELETE FROM tblstudents WHERE Id='$Id'");
    if ($query == TRUE) {
        echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=deleted';</script>";
    } else {
        echo "<script type='text/javascript'>window.location.href='createStudents.php?msg=error';</script>";
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
          xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
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
        showNotification('This Admission Number Already Exists!', 'danger');
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Create Student</strong></h1>
            <div id="notification-area"></div>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Student</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Student</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="firstName" value="<?php echo isset($row['firstName']) ? $row['firstName'] : '';?>" id="exampleInputFirstName" >
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="lastName" value="<?php echo isset($row['lastName']) ? $row['lastName'] : '';?>" id="exampleInputFirstName" >
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Phone Number<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="otherName" value="<?php echo isset($row['otherName']) ? $row['otherName'] : '';?>" id="exampleInputFirstName" >
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">ID Student<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="admissionNumber" value="<?php echo isset($row['admissionNumber']) ? $row['admissionNumber'] : '';?>" id="exampleInputFirstName" >
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo'<option value="">--Select Course--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['Id'].'" >'.$rows['className'].'</option>';
                          }
                          echo '</select>';
                        }
                        ?>  
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Sector Name<span class="text-danger ml-2">*</span></label>
                        <?php
                        echo"<div id='txtHint'></div>";
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

              <!-- Filter by Sector -->
              <div class="card mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Filter by Course</h6>
                </div>
                <div class="card-body">
                  <form method="post" action="">
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                        <select name="filterSector" id="filterSector" class="form-control">
                          <option value="">--Select Sector--</option>
                          <?php
                          $qry = "SELECT DISTINCT className FROM tblclass ORDER BY className ASC";
                          $result = $conn->query($qry);
                          while ($row = $result->fetch_assoc()) {
                              $selected = (isset($_POST['filterSector']) && $_POST['filterSector'] == $row['className']) ? 'selected' : '';
                              echo "<option value='{$row['className']}' $selected>{$row['className']}</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
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
                            <th>Sector</th>
                            <th>Date Created</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $filterSector = isset($_POST['filterSector']) ? $_POST['filterSector'] : '';
                          $query = "SELECT tblstudents.Id,tblclass.className,tblclassarms.classArmName,tblclassarms.Id AS classArmId,tblstudents.firstName,
                          tblstudents.lastName,tblstudents.otherName,tblstudents.admissionNumber,tblstudents.dateCreated
                          FROM tblstudents
                          LEFT JOIN tblclass ON tblclass.Id = tblstudents.classId
                          LEFT JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId";
                          if (!empty($filterSector)) {
                            $query .= " WHERE tblclass.className = '$filterSector'";
                          }
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;
                          if($num > 0) { 
                            while ($rows = $rs->fetch_assoc()) {
                              $sn = $sn + 1;
                              $className = isset($rows['className']) ? $rows['className'] : "Not Assigned";
                              $classArmName = isset($rows['classArmName']) ? $rows['classArmName'] : "Not Assigned";
                              echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['firstName']."</td>
                                <td>".$rows['lastName']."</td>
                                <td>".$rows['otherName']."</td>
                                <td>".$rows['admissionNumber']."</td>
                                <td>".$className."</td>
                                <td>".$classArmName."</td>
                                <td>".$rows['dateCreated']."</td>
                                <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i></a></td>
                                <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i></a></td>
                              </tr>";
                            }
                          } else {
                            echo "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
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
