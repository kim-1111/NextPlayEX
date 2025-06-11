<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextPlay - Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/principal.css">
    <link rel="stylesheet" href="../CSS/management.css">
    <link rel="stylesheet" href="../Layout/layout.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../Layout/include.js"></script>
    <script src="../Layout/auth.js"></script>
    <link rel="stylesheet" href="../CSS/about.css">
</head>

<body>

    <div id="navbar"></div>
    <div id="loginwindow"></div>
    <div id="registerwindow"></div>


    <style>
        .buttons-manager {
            margin-top: 50px;
            margin-bottom: 100px;

        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 36px;
            max-width: 80%;
            margin-bottom: 80px;

        }

        .active {
            background-color: #4CAF50;
            color: white;
        }

        .disabled {
            background-color: #cccccc;
            color: #666666;
            cursor: not-allowed;
        }
    </style>


    <div class="buttons-manager">
        <center><a href="eventmanager.php"><button class="button active">Event manager</button></a></center>
        <center><a href="gamemanager.php"><button class="button disabled">Game manager</button></a></center>
    </div>



    <!-- Footer -->
    <footer id="footer">
    </footer>

</body>

</html>