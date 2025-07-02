<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $sessionName=$_POST['sessionName'];
    $termId=$_POST['termId'];
    $dateCreated = date("Y-m-d");
   
    $query=mysqli_query($conn,"select * from tblsessionterm where sessionName ='$sessionName' and termId = '$termId'");
    $ret=mysqli_fetch_array($query);

    if($ret > 0){ 
        echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=exists';</script>";
    }
    else{
        $query=mysqli_query($conn,"insert into tblsessionterm(sessionName,termId,isActive,dateCreated) value('$sessionName','$termId','0','$dateCreated')");
        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=created';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=error';</script>";
        }
    }
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id= $_GET['Id'];

    $query=mysqli_query($conn,"select * from tblsessionterm where Id ='$Id'");
    $row=mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if(isset($_POST['update'])){
        $sessionName=$_POST['sessionName'];
        $termId=$_POST['termId'];
        $dateCreated = date("Y-m-d");

        $query=mysqli_query($conn,"update tblsessionterm set sessionName='$sessionName',termId='$termId',isActive='0' where Id='$Id'");
        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=updated';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=error';</script>";
        }
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id= $_GET['Id'];

    $query = mysqli_query($conn,"DELETE FROM tblsessionterm WHERE Id='$Id'");
    if ($query == TRUE) {
        echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=deleted';</script>";
    } else {
        echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=error';</script>";
    }
}

//--------------------------------ACTIVATE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "activate") {
    $Id= $_GET['Id'];

    $query=mysqli_query($conn,"update tblsessionterm set isActive='0' where isActive='1'");
    if ($query) {
        $que=mysqli_query($conn,"update tblsessionterm set isActive='1' where Id='$Id'");
        if ($que) {
            echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=activated';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=error';</script>";
        }
    } else {
        echo "<script type='text/javascript'>window.location.href='createSessionTerm.php?msg=error';</script>";
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
        showNotification('This Session and Term Already Exists!', 'danger');
      } else if (msg === 'activated') {
        showNotification('Record activated successfully!', 'success');
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Create Session and Semester</strong></h1>
            <div id="notification-area"></div>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Session and Semester</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Session and Semester</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Session Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="sessionName" value="<?php echo isset($row['sessionName']) ? $row['sessionName'] : '';?>" id="exampleInputFirstName" placeholder="Session">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Semester<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry= "SELECT * FROM tblterm ORDER BY termName ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="termId" class="form-control mb-3">';
                          echo'<option value="">--Select Semester--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['Id'].'" >'.$rows['termName'].'</option>';
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
                      <h6 class="m-0 font-weight-bold text-primary">All Session and Semester</h6>
                      <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the check symbol besides each to make session and term active!</i></h6>
                      <button class="btn btn-secondary no-print" onclick="printDiv('printableArea')">Print</button>
                    </div>
                    <div class="table-responsive p-3" id="printableArea">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>No</th>
                            <th>Session</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Activation</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT tblsessionterm.Id,tblsessionterm.sessionName,tblsessionterm.isActive,tblsessionterm.dateCreated, tblterm.termName FROM tblsessionterm INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn=0;
                          if($num > 0) { 
                            while ($rows = $rs->fetch_assoc()) {
                              $status = ($rows['isActive'] == '1') ? "Active" : "InActive";
                              $sn = $sn + 1;
                              echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['sessionName']."</td>
                                <td>".$rows['termName']."</td>
                                <td>".$status."</td>
                                <td>".$rows['dateCreated']."</td>
                                <td><a href='?action=activate&Id=".$rows['Id']."'><i class='fas fa-fw fa-check'></i></a></td>
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
