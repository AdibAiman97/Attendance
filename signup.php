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
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .container-login {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
            padding: 0;
            margin: 0;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.75);
            border: none;
            border-radius: 15px;
            padding: 20px;
            width: 60vw;
            max-width: 700px;
            height: auto;
            margin: 0 auto;
        }

        .login-form h5 {
            font-size: 24px;
            color: #ffc107;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .login-form img {
            margin-bottom: 20px;
            max-width: 100%;
            height: auto;
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

        .login-form .form-control option {
            color: black;
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

        .alert {
            margin-top: 20px;
            font-size: 14px;
        }

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

    <!-- Sign Up Content -->
    <div class="container-login">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="login-form">
                <div class="text-center">
                    <h5 class="text-center"><strong>STDC Student E-HADIR</strong></h5>
                        <img src="img/logo/attnlg.png" style="width: 250px; height: 100px;">
                        <h1 class="h4 text-light mb-4">Sign Up to Register</h1>
                    </div>
                    <form class="user" method="POST" action="signup.php">
                        <!-- Select User Type -->
                        <div class="form-group">
                            <label for="usertype" class="text-light">Sign up as:</label>
                            <select name="usertype" id="usertype" class="form-control mb-3" required>
                                <option value="">--Select User--</option>
                                <option value="student">Student</option>
                                <option value="Staff">STDC Staff</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" required name="firstname" placeholder="Enter First Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" required name="lastname" placeholder="Enter Last Name">
                        </div>
                        <!-- Phone Number for Students -->
                        <div class="form-group" id="phoneNoField" style="display:none;">
                            <input type="text" class="form-control" name="phoneno" placeholder="Enter Phone Number">
                        </div>
                        <!-- Admission Number for Students instead of Email -->
                        <div class="form-group" id="admissionNumberField" style="display:none;">
                            <input type="text" class="form-control" name="admissionNumber" placeholder="Enter ID Student">
                        </div>
                        <!-- Email for Lecturers -->
                        <div class="form-group" id="emailField">
                            <input type="email" class="form-control" name="email" placeholder="Enter Email Address">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" required name="password" id="password" placeholder="Enter Password (min 5 characters)">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-custom btn-block" value="Create Account" name="signup">
                        </div>
                    </form>
                    <!-- Back to Main System Button -->
                    <div class="text-center mt-4">
                        <a href="./" class="btn btn-secondary btn-block">Back to Login</a>
                    </div>
                </div>

                <?php
                if(isset($_POST['signup'])) {
                    $firstname = $_POST['firstname'];
                    $lastname = $_POST['lastname'];
                    $password = $_POST['password'];
                    $usertype = $_POST['usertype'];

                    // Password validation: Must be at least 5 characters
                    if(strlen($password) < 5) {
                        echo "<div class='alert alert-danger' role='alert'>Password must be at least 5 characters!</div>";
                    } elseif($usertype == '') {
                        echo "<div class='alert alert-danger' role='alert'>Please select a user type!</div>";
                    } else {
                        $password = md5($password);  // Encrypt password before storing
                        $dateCreated = date('Y-m-d');  // Format the date as YYYY-MM-DD

                        // Split queries based on user type
                        if($usertype == 'student') {
                            $admissionNumber = $_POST['admissionNumber'];
                            $phoneno = $_POST['phoneno'];  // Phone number for students
                            $query = "INSERT INTO tblstudents (firstName, lastName, otherName, admissionNumber, password, dateCreated)
                                      VALUES ('$firstname', '$lastname', '$phoneno', '$admissionNumber', '$password', '$dateCreated')";
                        } elseif($usertype == 'Staff') {
                            $email = $_POST['email'];
                            $phoneno = $_POST['phoneno'];  // Phone number for lecturers
                            $query = "INSERT INTO tblclassteacher (firstName, lastName, emailAddress, password, phoneNo, dateCreated)
                                      VALUES ('$firstname', '$lastname', '$email', '$password', '$phoneno', '$dateCreated')";
                        }

                        if($conn->query($query) === TRUE) {
                            echo "<div class='alert alert-success' role='alert'>Account created successfully!</div>";
                            echo "<script>
                                setTimeout(function() {
                                    window.location.href = 'index.php';
                                }, 4000); // Redirect after 4 seconds
                            </script>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Error creating account: " . $conn->error . "</div>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Sign Up Content -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>

    <script>
        // Show or hide fields based on user type
        document.getElementById('usertype').addEventListener('change', function() {
            var userType = this.value;
            if(userType === 'student') {
                document.getElementById('phoneNoField').style.display = 'block';
                document.getElementById('admissionNumberField').style.display = 'block';
                document.getElementById('emailField').style.display = 'none';
            } else if(userType === 'Staff') {
                document.getElementById('phoneNoField').style.display = 'block';
                document.getElementById('admissionNumberField').style.display = 'none';
                document.getElementById('emailField').style.display = 'block';
            } else {
                document.getElementById('phoneNoField').style.display = 'none';
                document.getElementById('admissionNumberField').style.display = 'none';
                document.getElementById('emailField').style.display = 'block';
            }
        });
    </script>
</body>

</html>




