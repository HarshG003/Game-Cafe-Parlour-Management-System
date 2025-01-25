<?php
session_start();

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

$conn = connectToDB(); // Use a single database connection

$user_id = $_SESSION['id'] ?? null;

$user_name = '';
$user_email = '';
$UID = '';
$EntryFee = 0;

if ($user_id) {
    // Get user details
    $userSql = "SELECT uname, email,phone FROM users WHERE id = ?";
    $stmt = $conn->prepare($userSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $user_name = $user['uname'];
        $user_email = $user['email'];
        $user_phone = $user['phone'];
    }
    $stmt->close();

    // Get UID from the tmt table
    $uidSql = "SELECT uid FROM tmt WHERE name = ?";
    $stmt = $conn->prepare($uidSql);
    $stmt->bind_param("s", $user_name); // "s" for string
    $stmt->execute();
    $uidResult = $stmt->get_result();

    if ($uidResult->num_rows > 0) {
        $uidRow = $uidResult->fetch_assoc();
        $UID = $uidRow['uid'];
    }
    $stmt->close();
}

// Fetch tournaments with game name, date, time, and remaining player count
$tournaments_query = "
    SELECT t.id, g.name AS game_name, t.tournament_date, t.tournament_time,
           (t.player_count - IFNULL(COUNT(p.id), 0)) AS remaining_players, t.entry_fee AS entry_fee
    FROM tournaments t
    JOIN games g ON t.game_id = g.id
    LEFT JOIN tmt p ON p.tournament_id = t.id
    GROUP BY t.id, g.name, t.tournament_date, t.tournament_time, t.player_count 
    ORDER BY t.tournament_date, t.tournament_time
";
$tournaments_result = $conn->query($tournaments_query);

$flag = '';
if (isset($_POST['submit'])) {
    // Retrieve form data
    $UID = $_POST['uid'] ?? '';
    $Name = $_POST['name'];
    $Phone = $_POST['phone'];
    $Email = $_POST['email'];
    $TournamentId = $_POST['tournament'];

    // Fetch Entry Fee for the tournament
    $feeSql = "SELECT entry_fee FROM tournaments WHERE id = ?";
    $stmt = $conn->prepare($feeSql);
    $stmt->bind_param("i", $TournamentId);
    $stmt->execute();
    $feeResult = $stmt->get_result();
    $feeRow = $feeResult->fetch_assoc();
    $EntryFee = $feeRow['entry_fee'];
    $stmt->close();

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO tmt (uid, name, phone, email, tournament_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $UID, $Name, $Phone, $Email, $TournamentId);

    if ($stmt->execute()) {
        // Calculate and display prize pool
        $prizePool = calculatePrizePool($TournamentId, $EntryFee);
        echo "<script>alert('Prize pool calculated: $prizePool');</script>"; // Replace with a proper display mechanism

        // Redirect to payment page with entry fee
        header("Location: payment.php?amount=$EntryFee");
        exit;
    } else {
        $flag = '0'; // Failure
    }
    $stmt->close();
}

// Function to calculate the prize pool
function calculatePrizePool($tournamentId, $entryFee)
{
    global $conn;

    // Count the number of registered players for the tournament
    $countSql = "SELECT COUNT(*) as player_count FROM tmt WHERE tournament_id = ?";
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param("i", $tournamentId);
    $stmt->execute();
    $countResult = $stmt->get_result();
    $playerCount = $countResult->fetch_assoc()['player_count'];
    $stmt->close();

    // Calculate total entry fees collected
    $totalCollected = $playerCount * $entryFee;

    // Determine prize distribution
    if ($playerCount < 3) {
        return $entryFee; // Prize is the entry fee back
    } else {
        $prizes = [];
        $prizes['1st'] = $totalCollected * 0.35; // 35% for 1st prize
        $prizes['2nd'] = $totalCollected * 0.30; // 30% for 2nd prize
        $prizes['3rd'] = $totalCollected * 0.25; // 25% for 3rd prize

        // Return prize amounts
        return $prizes;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Hstyle.css">
    <title>Tournament Registration</title><style>
        body {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("img/bt.jpg");
            background-repeat: no-repeat;
            background-size: 100vw 100vh;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #050e2d;
            height: auto;
            width: 400px;
            border: 1px solid grey;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        input,
        select {
            width: 90%;
            border: 1px solid grey;
            border-radius: 10px;
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
        }

        button {
            height: 48px;
            width: 166px;
            border-radius: 10px;
            margin-top: 20px;
            background: #1aeeef;
            border: none;
            color: #050e2d;
            box-shadow: 0 0 10px #1aeeef;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
            font-size: large;
        }

        button:hover {
            background: #17f5f5;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header style="background-color: #414141;">
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="computer.php">Book Seat</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <form action="" method="post">
        <h2>Register for Tournament</h2>
        <input type="text" name="uid" value="<?php echo $UID; ?>" required><br>
        <input type="text" name="name" value="<?php echo $user_name; ?>" readonly><br>
        <input type="number" name="phone"  value="<?php echo $user_phone; ?>" placeholder="Enter Phone Number" required><br>
        <input type="email" name="email" value="<?php echo $user_email; ?>" readonly><br>

        <!-- Dynamic Tournament Dropdown -->
        <select name="tournament" id="tournament" required>
            <option value="" hidden>Select Tournament</option>
            <?php
            if ($tournaments_result && $tournaments_result->num_rows > 0) {
                while ($row = $tournaments_result->fetch_assoc()) {
                    $currentTimestamp = strtotime(date('Y-m-d H:i:s'));
                    $tournamentTimestamp = strtotime($row['tournament_date'] . ' ' . $row['tournament_time']);
                    if ($tournamentTimestamp >= $currentTimestamp) {
                        echo '<option value="' . $row['id'] . '">' . $row['game_name'] . ' - Date: ' . $row['tournament_date'] . ' Time: ' . $row['tournament_time'] . ' - Remaining: ' . $row['remaining_players'] . ' - Entry Fee: ' . $row['entry_fee'] . '</option>';
                    }
                }
            } else {
                echo '<option value="">No tournaments available</option>';
            }
            ?>
        </select><br>

        <button type="submit" name="submit">Submit</button>

        <?php
        if ($flag == '1') {
            echo "<p style='color:white;'><strong>Success:</strong> Your details have been submitted!</p>";
        }
        if ($flag == '0') {
            echo "<p style='color:white;'><strong>Failed:</strong> Submission failed. Try again.</p>";
        }
        ?>
    </form>
</body>

</html>