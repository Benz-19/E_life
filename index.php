<!DOCTYPE html>
<html>

<head>
    <title>Prescription</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Make body take full viewport height */
            margin: 0;
            /* Remove default body margin */
        }

        .content {
            text-align: center;
        }

        .info {
            color: pink;
            /* Or your desired color */
        }

        img {
            display: block;
            /* Ensure image is a block element */
            margin: 0 auto;
            /* Center image horizontally */
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="info">
            <img src="./src/includes/genratePrescription.php" alt="Prescription">
        </div>
    </div>
</body>

</html>