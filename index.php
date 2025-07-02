<?php 
include 'Includes/dbcon.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/attnlg.png" rel="icon">
    <title>STDC E-HADIR</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('img/background.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            color: #fff;
        }

        .container-login {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative; 
            padding: 20px;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.75);
            border: none;
            border-radius: 15px;
            padding: 20px;
            width: 60vw;  /* 60% of the viewport width */
            max-width: 700px; /* Max width for large screens */
            height: auto;     /* Adjust height based on content */
            margin: 0 auto;   /* Center the card */
        }

        .login-form h5 {
            font-size: 24px;
            color: #ffc107;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .login-form img {
            margin-bottom: 20px;
        }

        .login-form .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 30px;
            color: #fff;
        }

        .login-form .form-control::placeholder {
            color: #ccc;
        }

        .login-form select.form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 30px;
            color: #fff;
        }

        .login-form select.form-control option {
            color: #000;
        }

        .btn-custom {
            background-color: #ffc107;
            color: #1b1f38;
            border-radius: 30px;
            padding: 10px 25px;
            font-size: 16px;
        }

        .btn-custom:hover {
            background-color: #e0a800;
            color: #fff;
        }

        .text-center a {
            color: #ffc107;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .alert {
            margin-top: 20px;
            font-size: 14px;
        }

        /* Media query for screens below 768px (tablets and phones) */
        @media (max-width: 768px) {
            .card {
                width: 90vw;  /* 90% of the viewport width for mobile */
                padding: 15px;
            }

            .login-form h5 {
                font-size: 18px;
            }

            .btn-custom {
                font-size: 14px;
                padding: 8px 20px;
            }

            .form-control {
                font-size: 14px;
            }

            .login-form img {
                width: 200px;
                height: 80px;
            }
        }
    </style>
</head>

<body>

    <!-- Login Content -->
    <div class="container-login">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="login-form">
                    <h5 class="text-center"><strong>STDC Student E-HADIR</strong></h5>
                    <div class="text-center">
                        <img src="img/logo/attnlg.png" style="width: 250px; height: 100px;">
                        <h1 class="h4 text-light mb-4">Login Account</h1>
                    </div>
                    <form class="user" method="POST" action="">
                        <div class="form-group">
                            <label for="usertype" class="text-light">Sign in as:</label>
                            <select required name="userType" class="form-control mb-3">
                                <option value="">--Select User--</option>
                                <option value="Administrator">Administrator</option>
                                <option value="HEA">Hal Ehwal Akademik (HEA)</option>
                                <option value="HEP">Hal Ehwal Pelajar (HEP)</option>
                                <option value="Student">Student</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" required name="username" placeholder="Enter Email Address/ID Student">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" required name="password" placeholder="Enter Password">
                        </div>
                        <div class="form-group custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input" id="customCheck">
                            <label class="custom-control-label text-light" for="customCheck">Remember me</label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-custom btn-block" value="Login" name="login">
                        </div>
                        <div class="form-group">
                            <a href="signup.php" class="btn btn-secondary btn-block">Sign up</a>
                        </div>
                    </form>

                    <!-- PHP Code for Login -->
                    <?php
                        if(isset($_POST['login'])){
                            $userType = $_POST['userType'];
                            $username = $_POST['username'];
                            $password = $_POST['password'];
                            $password = md5($password);

                            if($userType == "Administrator"){
                                $query = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
                                $rs = $conn->query($query);
                                $num = $rs->num_rows;
                                $rows = $rs->fetch_assoc();

                                if($num > 0){
                                    $_SESSION['userId'] = $rows['Id'];
                                    $_SESSION['firstName'] = $rows['firstName'];
                                    $_SESSION['lastName'] = $rows['lastName'];
                                    $_SESSION['emailAddress'] = $rows['emailAddress'];

                                    echo "<script type = \"text/javascript\">
                                    window.location = (\"Admin/index.php\")
                                    </script>";
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    Invalid Username/Password!
                                    </div>";
                                }
                            } else if($userType == "HEA"){
                                $query = "SELECT * FROM tblclassteacher WHERE emailAddress = '$username' AND password = '$password' AND role = 'HEA'";
                                $rs = $conn->query($query);
                                $num = $rs->num_rows;
                                $rows = $rs->fetch_assoc();

                                if($num > 0){
                                    $_SESSION['userId'] = $rows['Id'];
                                    $_SESSION['firstName'] = $rows['firstName'];
                                    $_SESSION['lastName'] = $rows['lastName'];
                                    $_SESSION['emailAddress'] = $rows['emailAddress'];
                                    $_SESSION['classId'] = $rows['classId'];
                                    $_SESSION['classArmId'] = $rows['classArmId'];
                                    $_SESSION['role'] = $rows['role'];

                                    echo "<script type = \"text/javascript\">
                                    window.location = (\"HEA/index.php\")
                                    </script>";
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    Invalid Username/Password!
                                    </div>";
                                }

                            } else if($userType == "HEP"){
                                $query = "SELECT * FROM tblclassteacher WHERE emailAddress = '$username' AND password = '$password' AND role = 'HEP'";
                                $rs = $conn->query($query);
                                $num = $rs->num_rows;
                                $rows = $rs->fetch_assoc();

                                if($num > 0){
                                    $_SESSION['userId'] = $rows['Id'];
                                    $_SESSION['firstName'] = $rows['firstName'];
                                    $_SESSION['lastName'] = $rows['lastName'];
                                    $_SESSION['emailAddress'] = $rows['emailAddress'];
                                    $_SESSION['classId'] = $rows['classId'];
                                    $_SESSION['classArmId'] = $rows['classArmId'];
                                    $_SESSION['role'] = $rows['role'];

                                    echo "<script type = \"text/javascript\">
                                    window.location = (\"HEP/index.php\")
                                    </script>";
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    Invalid Username/Password!
                                    </div>";
                                }

                            } else if($userType == "Student"){
                                $query = "SELECT * FROM tblstudents WHERE admissionNumber = '$username' AND password = '$password'";
                                $rs = $conn->query($query);
                                $num = $rs->num_rows;
                                $rows = $rs->fetch_assoc();

                                if($num > 0){
                                    $_SESSION['userId'] = $rows['Id'];
                                    $_SESSION['firstName'] = $rows['firstName'];
                                    $_SESSION['lastName'] = $rows['lastName'];
                                    $_SESSION['emailAddress'] = $rows['admissionNumber'];
                                    $_SESSION['classId'] = $rows['classId'];
                                    $_SESSION['classArmId'] = $rows['classArmId'];

                                    echo "<script type = \"text/javascript\">
                                    window.location = (\"Student/index.php\")
                                    </script>";
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    Invalid Username/Password!
                                    </div>";
                                }
                            } 
                        }
                    ?>
                    <div class="text-center">
                        <a class="big" href="forgot_password.php">Forgot Your Password?</a>
                    <div class="text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Content -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>

    </div>
</body>
</html>

