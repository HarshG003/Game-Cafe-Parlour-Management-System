<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gamecafe";

// Function to connect to the database
function connectToDB()
{
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Fetch games from the database
$conn = connectToDB();
$sql = "SELECT * FROM games";
$result = $conn->query($sql);
$games = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Hstyle.css">
    <link rel="stylesheet" href="modify.css">
    <title>Game Management</title>
</head>

<body>
    <header style="background-color:black;">
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="admin.php">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Games <i
                        class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addGame.php">Add Game</a></li>
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
            <li><a href="users.php">Customers</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <h1>Game Management</h1>
    <div class="game-container">
        <?php foreach ($games as $game): ?>
            <div class="game-card">
                <h3><?php echo htmlspecialchars($game['name']); ?></h3>
                <p>Genres: <?php echo htmlspecialchars($game['genres']); ?></p>
                <p>Description: <?php echo htmlspecialchars($game['description']); ?></p>
                <img src="<?php echo $game['image']; ?>" alt="<?php echo $game['name']; ?>">
                <div class="dropdown" style="float: right;">
                    <button class="dropbtn">Edit</button>
                    <div class="dropdown-content">
                        <a href="#" onclick="showModifyForm(<?php echo $game['id']; ?>)">Modify</a>
                        <a href="delete_game.php?id=<?php echo $game['id']; ?>">Delete</a>
                    </div>
                </div>
                <form class="modify-form" id="modify-form-<?php echo $game['id']; ?>">
                    <input type="hidden" name="id" value="<?php echo $game['id']; ?>">
                    <label for="name">Game Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($game['name']); ?>"><br><br>
                    <label for="genres">Genres:</label>
                    <input type="text" id="genres" name="genres"
                        value="<?php echo htmlspecialchars($game['genres']); ?>"><br><br>
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description"
                        value="<?php echo htmlspecialchars($game['description']); ?>"><br><br>
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image"><br><br>
                    <input type="submit" value="Save Changes">
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function showModifyForm(gameId) {
            document.getElementById("modify-form-" + gameId).style.display = "block";
        }
    </script>
</body>

</html>