<?php
session_start();

$conn = new mysqli("localhost", "root", "", "gamecafe");

if ($_SESSION['id']) {
    $uid = $_SESSION['id'];
} else {
    echo 'login first';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Hstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleMenu() {
            $('.nav').toggleClass('active');
        }
    </script>
</head>

<body>
    <header>
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="tournement.php">Tournament</a></li>
            <li><a href="computer.php">Book Seat</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <!-- Home Banner-->
    <div class="banner">
        <div class="bg">
            <div class="content">
                <h2>
                    <?php
                    $sql = 'select uname from users where id= ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $uname = $row['uname'];
                    echo $uname;
                    ?> WELCOME TO <br> GAMMERS PARADISE
                </h2>
            </div>
            <img src="img/assassin2.png" alt="">
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#search').on('keyup', function () {
                var search = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: 'search.php',
                    data: { search: search },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>