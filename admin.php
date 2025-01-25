<?php
session_start();

$conn = new mysqli("localhost", "root", "", "gamecafe");

if (isset($_SESSION['id'])) {
    $uid = $_SESSION['id'];
} else {
    echo 'Login first';
    exit; // Stop the script execution if the user is not logged in
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
    <title>Admin</title>
    <style>
        .nav {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .nav li {
            position: relative;
        }

        .nav a {
            text-decoration: none;
            padding: 15px;
            display: block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-menu,
        .dropdown-menu.show {
            display: block;
            background-color: transparent;
        }
    </style>
</head>

<body>
    <header>
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="admin.php">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Games <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addGame.php">Add Game</a></li>
                    <li><a href="modifygame.php">Modify Game</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Tournament <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addtournament.php">Create Tournament</a></li>
                    <li><a href="tournamentplayer.php">Tournament Details</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Inventory <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addcmp.php">Add Device</a></li>
                    <li><a href="admincmp.php">Devices Details</a></li>
                </ul>
            </li>
            <li><a href="users.php">Customers</a></li>
            <li><a href="adadmin.php">Add Admin</a></li>
            <li><a href="adlogout.php">Logout</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>

    <!-- Home Banner -->
    <div class="banner">
        <div class="bg">
            <div class="content">
                <h2>
                    <?php
                    // Fetch admin's username
                    $sql = 'SELECT uname FROM admin WHERE id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $uname = $row['uname'];
                        echo $uname . ' WELCOME <br> TO GAME PARLOUR MANAGEMENT <br> SYSTEM';
                    } else {
                        echo 'Admin not found';
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </h2>
                <p></p>
            </div>
            <img src="img/assassin2.png" alt="Assassin Image">
        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            event.preventDefault();
            const dropdownMenu = event.target.nextElementSibling;
            dropdownMenu.classList.toggle('show');
        }

        window.onclick = function (event) {
            if (!event.target.matches('.dropdown-toggle')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(menu => {
                    if (menu.classList.contains('show')) {
                        menu.classList.remove('show');
                    }
                });
            }
        }
    </script>

</body>

</html>
