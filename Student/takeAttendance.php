<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/attnlg.png" rel="icon">
    <title>Scan QR Code</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.1.3/html5-qrcode.min.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #1B1F38;
            color: white;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h2 {
            color: #FFC107;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        #reader {
            width: 100%;
            max-width: 500px; /* Increase max-width for larger screens */
            height: auto;
            aspect-ratio: 1 / 1; /* Keeps the scanning area square-shaped */
            margin: 20px auto;
            border: 2px solid #FFC107;
            border-radius: 15px;
            background-color: #262B47;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            width: 100%;
            max-width: 600px; /* Larger container for larger screens */
        }

        .btn-custom {
            background-color: #FFC107;
            color: #1B1F38;
            border-radius: 25px;
            padding: 10px 30px;
            font-size: 16px;
            margin-top: 30px;
            width: 100%;
            max-width: 250px;
        }

        .btn-custom:hover {
            background-color: #FFA000;
            color: #fff;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 14px;
            color: #ccc;
        }

        .logo-container img {
            max-width: 100%; /* Ensures the logo is responsive */
            height: auto;
            margin-bottom: 20px;
        }

        @media screen and (max-width: 1024px) {
            #reader {
                max-width: 600px; /* Adjust for tablets and smaller laptops */
            }
        }

        @media screen and (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
            }

            #reader {
                max-width: 300px; /* Adjust for larger phones */
            }
        }

        @media screen and (max-width: 574px) {
            h2 {
                font-size: 1.3rem;
            }

            #reader {
                max-width: 186px; /* Adjust for smaller phones */
                
            }

            .btn-custom {
                font-size: 14px;
                padding: 8px 20px;
            }

            .footer-text {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="logo-container">
            <img src="../img/logo/attnlg.png" alt="Logo" style="width: 250px; height: 100px;">
        </div>
        <h2>Scan QR Code to Take Attendance</h2>
        <div id="reader"></div>
        <button class="btn btn-custom" onclick="stopScanning()">Stop Scanning</button>
        <p class="footer-text">Ensure your camera is positioned correctly for an optimal scan.</p>
    </div>

    <script>
        function onScanSuccess(qrCodeMessage) {
            // Handle the scanned QR code message
            window.location.href = qrCodeMessage; // Redirect to the attendance page
        }

        function onScanError(errorMessage) {
            // Optional: handle scan error
            console.warn("QR Code scan error: ", errorMessage);
        }

        let html5QrCode;

        function startScanning() {
            html5QrCode = new Html5Qrcode("reader");

            // Adjust QR box size for different screens
            const qrboxSize = Math.min( window.innerHeight, window.innerWidth) * 0.4;

            html5QrCode.start(
                { facingMode: "environment" }, // Use the rear camera on mobile devices
                {
                    fps: 10,
                    qrbox: { width: qrboxSize, height: qrboxSize },
                    aspectRatio: 1 / 1 // Ensuring it stays square
                    
                },
                onScanSuccess,
                onScanError
            )
            .then(() => {
                console.log("Camera started successfully.");
            })
            .catch(err => {
                console.error("Unable to start scanning. Error: ", err);
                alert("Failed to start the camera: " + err);
            });
            
        }

        function stopScanning() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    console.log("Scanning stopped.");
                    alert("Scanning stopped.");
                }).catch(err => {
                    console.log("Error stopping scanning: ", err);
                    alert("Error stopping scanning: " + err);
                });
            }
            window.location.href = './';
        }

        startScanning();
    </script>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>






