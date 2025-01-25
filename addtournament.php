<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gamecafe";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchGames($conn)
{
    $sql = "SELECT id, name FROM games";
    $result = $conn->query($sql);
    $games = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $games[] = $row;
        }
    }
    return $games;
}

// Update the createTournament function to include time
function createTournament($conn, $game_id, $tournament_date, $tournament_time, $mode, $entry_fee, $player_count)
{
    // Updated the type definition string to include 's' for tournament_time
    $stmt = $conn->prepare("INSERT INTO tournaments (game_id, tournament_date, tournament_time, mode, entry_fee, player_count) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $game_id, $tournament_date, $tournament_time, $mode, $entry_fee, $player_count);
    $stmt->execute();
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = $_POST['game'];
    $tournament_date = $_POST['date'];
    $tournament_time = $_POST['time']; // Get the tournament time from POST data
    $mode = $_POST['mode'];
    $entry_fee = $_POST['entry_fee'];
    $player_count = $_POST['player_count'];
    createTournament($conn, $game_id, $tournament_date, $tournament_time, $mode, $entry_fee, $player_count);
    echo "New tournament created successfully";
}

$games = fetchGames($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Tournament</title>
    <link rel="stylesheet" href="Hstyle.css">
    <link rel="stylesheet" href="adtournmt.css">
</head>
i
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
                    <li><a href="modifygame.php">Modify Game</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Tournament <i
                        class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
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
    <div class="box">
        <form method="POST">
            <h2>Create Tournament</h2>
            <div class="inputbox">
                <span>Game:</span>
                <select name="game" required="required">
                    <?php foreach ($games as $game) { ?>
                        <option value="<?php echo $game['id']; ?>"><?php echo $game['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inputbox">
                <span>Tournament Date:</span>
                <input type="date" name="date" required="required">
            </div>
            <div class="inputbox">
                <span>Tournament Time:</span>
                <input type="time" name="time" required="required"> <!-- Make sure this is included -->
            </div>
            <div class="inputbox">
                <span>Mode:</span>
                <select name="mode" required="required">
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
            <div class="inputbox">
                <span>Entry Fee:</span>
                <input type="number" name="entry_fee" required="required">
            </div>
            <div class="inputbox">
                <span>Player Count:</span>
                <input type="number" name="player_count" required="required">
            </div>
            <input type="submit" value="Create Tournament">
        </form>
    </div>
</body>

</html>