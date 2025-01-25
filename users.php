<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "gamecafe");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to select all users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gammers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Hstyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 200px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .container .table {
            margin-bottom: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        .container .table th,
        .container .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .container .table th {
            background-color: #f0f0f0;
        }

        .nav {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav li {
            position: relative;
            margin-right: 20px;
        }

        .nav a {
            text-decoration: none;
            padding: 15px;
            display: block;
            color: #fff;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
            padding: 10px;
            border-radius: 10px;
        }

        .dropdown:hover .dropdown-menu,
        .dropdown-menu.show {
            display: block;
            background-color: gray;
        }

        .toggleMenu {
            display: none;
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }

        @media only screen and (max-width: 768px) {
            .nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav li {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .toggleMenu {
                display: block;
            }

            .container {
                margin: 20px;
            }
        }
    </style>
</head>

<body >
    <header
        style="background-color: black; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="#" class="logo" style="color: #fff; font-size: 24px; font-weight: bold;">Gammers</a>
        <ul class="nav">
            <li><a href="admin.php">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Games <i
                        class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addGame.php">Add Game</a></li>
                    <li><a href="modifygame.php">Modify Game</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Tournament <i
                        class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addtournament.php">Create Tournament</a></li>
                    <li><a href="tournamentplayer.php">Tournament Details</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Inventory <i
                        class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addcmp.php">Add Device</a></li>
                    <li><a href="admincmp.php">Devices Details</a></li>
                </ul>
            </li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"><i class="bi bi-list"></i></div>
    </header>
    <br>
    <div class="container">
        <?php
        // Check if query was successful
        if ($result) {
            // Create a table to display users
            echo "<table class='table table-striped table-bordered table-hover'>";
            echo "<tr><th>Name</th><th>Email</th><th>Phone</th></tr>";

            // Fetch and display each user
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row["uname"] . "</td><td>" . $row["email"] . "</td><td>" . $row["phone"] . "</td></tr>";
            }

            echo "</table>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Close the connection
        mysqli_close($conn);
        ?>
    </div>
    <script>
        function toggleDropdown(event) {
            event.preventDefault();
            var dropdownMenu = event.target.nextElementSibling;
            dropdownMenu.classList.toggle("show");
        }

        function toggleMenu() {
            var nav = document.querySelector(".nav");
            nav.classList.toggle("show");
        }
    </script>
</body>

</html>