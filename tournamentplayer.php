<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GameCafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch currently running tournaments with registered players
$current_tournaments_query = "
    SELECT t.id, g.name AS game_name, t.tournament_date, t.tournament_time,
           t.player_count AS max_players, COUNT(p.id) AS registered_players
    FROM tournaments t
    JOIN games g ON t.game_id = g.id
    LEFT JOIN tmt p ON p.tournament_id = t.id
    WHERE t.tournament_date >= CURDATE() 
          AND (t.tournament_date > CURDATE() OR t.tournament_time >= CURTIME())
    GROUP BY t.id, g.name, t.tournament_date, t.tournament_time, t.player_count 
    ORDER BY t.tournament_date, t.tournament_time
";

$current_tournaments_result = $conn->query($current_tournaments_query);

// Check if query was successful
if ($current_tournaments_result === false) {
    die("Error executing query: " . $conn->error);
}

// Fetch upcoming tournaments with registered players
$upcoming_tournaments_query = "
    SELECT t.id, g.name AS game_name, t.tournament_date, t.tournament_time,
           t.player_count AS max_players, COUNT(p.id) AS registered_players
    FROM tournaments t
    JOIN games g ON t.game_id = g.id
    LEFT JOIN tmt p ON p.tournament_id = t.id
    WHERE t.tournament_date > CURDATE() OR (t.tournament_date = CURDATE() AND t.tournament_time > CURTIME())
    GROUP BY t.id, g.name, t.tournament_date, t.tournament_time, t.player_count 
    ORDER BY t.tournament_date, t.tournament_time
";

$upcoming_tournaments_result = $conn->query($upcoming_tournaments_query);

// Check if query was successful
if ($upcoming_tournaments_result === false) {
    die("Error executing query: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Hstyle.css">
    <title>Tournaments</title>
    <style>
        h1 {
            margin-top: 100px;
        }

        table {
            width: 100%;
            border: solid #333 1px;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }


        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 1em;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr {
            background-color: #ddd;
        }

        h1 {
            text-align: center;
            color: #ddd;
        }

        .no-tournaments {
            text-align: center;
            font-weight: bold;
            color: #ddd;
        }

        .nav {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .nav li {
            position: relative;
            /* Needed for absolute positioning of dropdown */
        }

        .nav a {
            text-decoration: none;
            padding: 15px;
            display: block;
        }

        .dropdown-menu {
            display: none;
            /* Hide dropdown menu by default */
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
            <li><a href="addGame.php">Add Games</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Tournament <i
                        class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addtournament.php">Create Tournament</a></li>
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
            <li><a href="users.php">Customers</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>

    <h1>Currently Running Tournaments</h1>

    <table>
        <tr>
            <th>Game Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Max Players</th>
            <th>Registered Players</th>
        </tr>
        <?php
        if ($current_tournaments_result && $current_tournaments_result->num_rows > 0) {
            while ($row = $current_tournaments_result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['game_name']}</td>
                        <td>{$row['tournament_date']}</td>
                        <td>{$row['tournament_time']}</td>
                        <td>{$row['max_players']}</td>
                        <td>{$row['registered_players']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='no-tournaments'>No currently running tournaments found.</td></tr>";
        }
        ?>
    </table>
    <script>
        function toggleDropdown(event) {
            event.preventDefault(); // Prevent default anchor click behavior
            const dropdownMenu = event.target.nextElementSibling; // Get the next sibling (dropdown-menu)

            // Toggle the 'show' class
            dropdownMenu.classList.toggle('show');
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
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