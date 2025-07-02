<?php 
include 'Includes/dbcon.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/attnlg.png" rel="icon">
    <title>Reset Password</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="css/custom-styles.css" rel="stylesheet"> <!-- Custom CSS File -->
    <script>
        function validatePassword() {
            var newPassword = document.getElementById("new_password").value;
            if (newPassword.length < 5) {
                alert("Password must be at least 5 characters long.");
                return false;
            }
            return true;
        }

        // Function to make alert disappear after 2 seconds
        function hideAlert() {
            setTimeout(function() {
                let alert = document.getElementById('alertMessage');
                if (alert) {
                    alert.style.display = 'none';
                }
            }, 2000); // 2 seconds
        }

        // Show/hide fields based on selected user type
        function toggleFields() {
            var userType = document.getElementById('usertype').value;
            document.getElementById('admissionNumberField').style.display = userType === 'student' ? 'block' : 'none';
            document.getElementById('emailField').style.display = userType === 'Staff' ? 'block' : 'none';
        }
    </script>
    <style>
        body {
            background-image: url('img/background.jpeg'); /* Use your desired background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            color: #fff;
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
            width: 90vw; /* Adjusts width dynamically with viewport width */
            max-width: 700px; /* Limits the width on larger screens */
            margin: 0 auto;
            margin-left: -104px; /* Adjusts position to the left */
        }

        .login-form h5 {
            font-size: 24px;
            color: #ffc107;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .login-form img {
            margin-bottom: 20px;
            max-width: 100%; /* Ensures logo scales properly on smaller devices */
        }

        .login-form .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 30px;
            color: #fff; /* Change this for the input text color */
        }

        .login-form .form-control::placeholder {
            color: #ccc;
        }

        .login-form .form-control option {
            color: black; /* Set the text color for dropdown options to black */
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

        .btn-secondary {
            border-radius: 30px;
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

        /* Adjust form fields for smaller screens */
        @media (max-width: 768px) {
            .card {
                width: 90vw;  /* 90% of the viewport width for mobile */
                padding: 15px;
                margin-left: 0px; /* Adjusts position to the left */
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

<body class="bg-gradient-login" onload="hideAlert()">
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <h5 class="text-center"><strong>STDC Student E-HADIR</strong></h5>
                                    <div class="text-center">
                                        <img src="img/logo/attnlg.png" style="width:250px;height:100px">
                                        <br><br>
                                        <h1 class="h4 text-light mb-4">Reset Password</h1>
                                    </div>
                                    <form class="user" method="POST" action="" onsubmit="return validatePassword();">
                                        <!-- Select User Type -->
                                        <div class="form-group">
                                            <label for="usertype">Reset password as:</label>
                                            <select name="usertype" id="usertype" class="form-control" required onchange="toggleFields()">
                                                <option value="">--Select User--</option>
                                                <option value="student">Student</option>
                                                <option value="Staff">STDC Staff</option>

                                            </select>
                                        </div>

                                        <!-- Admission Number for Students -->
                                        <div class="form-group" id="admissionNumberField" style="display:none;">
                                            <input type="text" class="form-control" name="admissionNumber" placeholder="Enter ID Student">
                                        </div>

                                        <!-- Email for Lecturers -->
                                        <div class="form-group" id="emailField" style="display:none;">
                                            <input type="email" class="form-control" name="email" placeholder="Enter Email Address">
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Enter new password (min 5 characters)">
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-custom btn-block" name="reset_password" value="Reset Password">
                                        </div>
                                    </form>
                                    
                                    <!-- Back to Main System Button -->
                                    <div class="text-center mt-4">
                                        <a href="./" class="btn btn-secondary btn-block">Back to Login</a>
                                    </div>

                                    <?php
                                    if (isset($_POST['reset_password'])) {
                                        $usertype = $_POST['usertype'];
                                        $new_password = $_POST['new_password'];

                                        // Server-side validation for password length
                                        if (strlen($new_password) < 5) {
                                            echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                Password must be at least 5 characters long.
                                            </div>";
                                        } elseif ($usertype == '') {
                                            echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                Please select a user type!
                                            </div>";
                                        } else {
                                            $hashed_password = md5($new_password); // Encrypt password
                                            
                                            if ($usertype == 'student') {
                                                // Reset password for students using admission number
                                                $admissionNumber = $_POST['admissionNumber'];
                                                if ($admissionNumber == '') {
                                                    echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                        Please enter your Admission Number.
                                                    </div>";
                                                } else {
                                                    // Check if admission number exists in tblstudents
                                                    $query = "SELECT * FROM tblstudents WHERE admissionNumber = '$admissionNumber'";
                                                    $result = $conn->query($query);

                                                    if ($result->num_rows > 0) {
                                                        // Update the password
                                                        $update_query = "UPDATE tblstudents SET password='$hashed_password' WHERE admissionNumber='$admissionNumber'";
                                                        if ($conn->query($update_query) === TRUE) {
                                                            echo "<div id='alertMessage' class='alert alert-success mt-4' role='alert'>
                                                                Password has been reset successfully.
                                                            </div>";
                                                        } else {
                                                            echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                                Error updating password.
                                                            </div>";
                                                        }
                                                    } else {
                                                        echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                            Admission Number not found.
                                                        </div>";
                                                    }
                                                }

                                            } elseif ($usertype == 'Staff') {
                                                // Reset password for lecturers using email
                                                $email = $_POST['email'];
                                                if ($email == '') {
                                                    echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                        Please enter your Email Address.
                                                    </div>";
                                                } else {
                                                    // Check if email exists in tblclassteacher
                                                    $query = "SELECT * FROM tblclassteacher WHERE emailAddress = '$email'";
                                                    $result = $conn->query($query);

                                                    if ($result->num_rows > 0) {
                                                        // Update the password
                                                        $update_query = "UPDATE tblclassteacher SET password='$hashed_password' WHERE emailAddress='$email'";
                                                        if ($conn->query($update_query) === TRUE) {
                                                            echo "<div id='alertMessage' class='alert alert-success mt-4' role='alert'>
                                                                Password has been reset successfully.
                                                            </div>";
                                                        } else {
                                                            echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                                Error updating password.
                                                            </div>";
                                                        }
                                                    } else {
                                                        echo "<div id='alertMessage' class='alert alert-danger mt-4' role='alert'>
                                                            Email Address not found.
                                                        </div>";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>
</html>


