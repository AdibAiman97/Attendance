<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $classId=$_POST['classId'];
    $classArmName=$_POST['classArmName'];
   
    $query=mysqli_query($conn,"select * from tblclassarms where classArmName ='$classArmName' and classId = '$classId'");
    $ret=mysqli_fetch_array($query);

    if($ret > 0){ 
        echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=exists';</script>";
    }
    else{
        $query=mysqli_query($conn,"insert into tblclassarms(classId,classArmName,isAssigned) value('$classId','$classArmName','0')");
        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=created';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=error';</script>";
        }
    }
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id= $_GET['Id'];
    $filterSectorName = isset($_GET['filterSectorName']) ? $_GET['filterSectorName'] : '';

    $query=mysqli_query($conn,"select * from tblclassarms where Id ='$Id'");
    $row=mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if(isset($_POST['update'])){
        $classId=$_POST['classId'];
        $classArmName=$_POST['classArmName'];

        $query=mysqli_query($conn,"update tblclassarms set classId = '$classId', classArmName='$classArmName' where Id='$Id'");

        if ($query) {
            echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=updated&filterSectorName=$filterSectorName';</script>";
        } else {
            echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=error';</script>";
        }
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id= $_GET['Id'];
    $filterSectorName = isset($_GET['filterSectorName']) ? $_GET['filterSectorName'] : '';

    $query = mysqli_query($conn,"DELETE FROM tblclassarms WHERE Id='$Id'");

    if ($query == TRUE) {
        echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=deleted&filterSectorName=$filterSectorName';</script>";
    } else {
        echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=error';</script>";
    }
}

//--------------------------------ASSIGN/UNASSIGN------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && ($_GET['action'] == "assign" || $_GET['action'] == "unassign")) {
    $Id= $_GET['Id'];
    $filterSectorName = isset($_GET['filterSectorName']) ? $_GET['filterSectorName'] : '';

    $status = ($_GET['action'] == "assign") ? '1' : '0';

    $query = mysqli_query($conn,"UPDATE tblclassarms SET isAssigned='$status' WHERE Id='$Id'");

    if ($query == TRUE) {
        echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=status_updated&filterSectorName=$filterSectorName';</script>";
    } else {
        echo "<script type='text/javascript'>window.location.href='createClassArms.php?msg=error';</script>";
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
      } else if (msg === 'status_updated') {
        showNotification('Status updated successfully!', 'success');
      } else if (msg === 'exists') {
        showNotification('This Course Sector Already Exists!', 'danger');
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
            <h1 class="h3 mb-0 text-gray-800"><strong>Create Course Sector</strong></h1>
            <div id="notification-area"></div>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Course Sector</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Course Sector</h6>
                    <?php echo isset($statusMsg) ? $statusMsg : ''; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                         <?php
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="classId" class="form-control mb-3">';
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
                      <input type="text" class="form-control" name="classArmName" value="<?php echo isset($row['classArmName']) ? $row['classArmName'] : '';?>" id="exampleInputFirstName" placeholder="--Sector Name--">
                        </div>
                    </div>
                      <?php
                    if (isset($Id))
                    {
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
                      <h6 class="m-0 font-weight-bold text-primary">Filter by Sector</h6>
                    </div>
                    <div class="card-body">
                      <form method="post">
                        <div class="form-group row mb-3">
                          <div class="col-xl-6">
                            <label class="form-control-label">Select Sector<span class="text-danger ml-2">*</span></label>
                            <?php
                            $qry = "SELECT DISTINCT classArmName FROM tblclassarms ORDER BY classArmName ASC";
                            $result = $conn->query($qry);
                            $num = $result->num_rows;		
                            if ($num > 0){
                              echo '<select required name="filterSectorName" class="form-control mb-3">';
                              echo '<option value="">--Select Sector--</option>';
                              while ($rows = $result->fetch_assoc()){
                                $selected = ($rows['classArmName'] == $filterSectorName) ? 'selected' : '';
                                echo '<option value="'.$rows['classArmName'].'" '.$selected.'>'.$rows['classArmName'].'</option>';
                              }
                              echo '</select>';
                            }
                            ?>  
                          </div>
                        </div>
                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Sectors</h6>
                      <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the Assign or Unassign button to make activation of course and sector!</i></h6>
                      <button class="btn btn-secondary no-print" onclick="printDiv('printableArea')">Print</button>
                    </div>
                    <div class="table-responsive p-3" id="printableArea">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>No</th>
                            <th>Course</th>
                            <th>Sector</th>
                            <th>Status</th>
                            <th>Activation</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $filterSectorName = isset($_POST['filterSectorName']) ? $_POST['filterSectorName'] : '';
                          $query = "SELECT tblclassarms.Id,tblclassarms.isAssigned,tblclass.className,tblclassarms.classArmName 
                                    FROM tblclassarms
                                    INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId";
                          if (!empty($filterSectorName)) {
                            $query .= " WHERE tblclassarms.classArmName = '$filterSectorName'";
                          }
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn=0;
                          $status="";
                          if($num > 0)
                          { 
                            while ($rows = $rs->fetch_assoc())
                              {
                                  if($rows['isAssigned'] == '1'){
                                    $status = "Active";
                                    $action = "<a href='?action=unassign&Id=".$rows['Id']."&filterSectorName=".$filterSectorName."' class='btn btn-danger btn-sm'>Unassign</a>";
                                  } else {
                                    $status = "Inactive";
                                    $action = "<a href='?action=assign&Id=".$rows['Id']."&filterSectorName=".$filterSectorName."' class='btn btn-success btn-sm'>Assign</a>";
                                  }
                                 $sn = $sn + 1;
                                echo"
                                  <tr>
                                    <td>".$sn."</td>
                                    <td>".$rows['className']."</td>
                                    <td>".$rows['classArmName']."</td>
                                    <td>".$status."</td>
                                    <td>".$action."</td>
                                    <td><a href='?action=edit&Id=".$rows['Id']."&filterSectorName=".$filterSectorName."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                    <td><a href='?action=delete&Id=".$rows['Id']."&filterSectorName=".$filterSectorName."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                  </tr>";
                              }
                          }
                          else
                          {
                               echo   
                               "<div class='alert alert-danger' role='alert'>
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

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

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
