<?php 

  $query = "SELECT * FROM tblclassteacher WHERE Id = ".$_SESSION['userId']."";
  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $fullName = $rows['firstName']." ".$rows['lastName'];

?>
<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top" style="background-color: #1B1F38; justify-content: center; position: relative;">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3" style="position: absolute; left: 20px; color: white;">
      <i class="fa fa-bars"></i>
    </button>

    <!-- Logo Container -->
    <div class="logo-container text-center" style="flex-grow: 1;">
      <img src="../img/logo/attnlg.png" style="width: 150px; height: 50px;">
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto" style="position: absolute; right: 20px;">
     

      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false" style="color: white;">
          <img class="img-profile rounded-circle" src="img/user-icn.png" style="max-width: 60px">
          <span class="ml-2 d-none d-lg-inline text-white small"><b>Welcome <?php echo $fullName;?></b></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">
            <i class="fas fa-power-off fa-fw mr-2 text-danger"></i>
            Logout
          </a>
        </div>
      </li>
    </ul>
</nav>
