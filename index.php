<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Hstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Home</title>
    <style>
        .card {
            display: none;
        }

        /* Hide all cards initially */
        .active {
            display: block;
        }

        /* Show active cards */
    </style>
</head>

<body>
    <header>
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="home.php">Home</a></li>
            <li><a href="aboutUs.php">About</a></li>
            <li><a href="#Game">Games</a></li>
            <li><a href="adlogin.php">Admin Login</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>

    <!-- Home Banner-->
    <div class="banner">
        <div class="bg">
            <div class="content">
                <h2>A New Home For <br> Game Lovers</h2>
                <a href="login.php" class="btn">join now</a>
            </div>
            <img src="img/assassin3.png" alt="">
        </div>
    </div>
    <!--About-->
    <div class="about">
        <div class="contentbx">
            <h2>About Us</h2>
            <p>GAMMERS is a gaming café that provides services like organizing game tournaments, where you can
                participate for a minimal fee and have a chance to win prizes. You can also play various games offline
                at our premises.
            </p>
            <a href="aboutUs.php">Read more</a>
        </div>
        <img src="img/ab2.png">
    </div>
    <!-- games -->
    <div class="games">
        <h2 id="Game">Popular Games</h2>
        <ul>
            <li class="list active" data-filter="all" onclick="filterGames('all')">All</li>
            <li class="list" data-filter="Action" onclick="filterGames('Action')">Action</li>
            <li class="list" data-filter="Adventure" onclick="filterGames('Adventure')">Adventure</li>
            <li class="list" data-filter="Sports" onclick="filterGames('Sports')">Sports</li>
            <li class="list" data-filter="Racing" onclick="filterGames('Racing')">Racing</li>
        </ul>
        <div class="cardbx">
            <?php
            // Fetch games from the database
            $conn = new mysqli("localhost", "root", "", "GameCafe");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $game_query = "SELECT id, name, genres, image FROM games";
            $game_result = $conn->query($game_query);

            if ($game_result->num_rows > 0) {
                while ($game = $game_result->fetch_assoc()) {
                    echo "<div class='card {$game['genres']}' data-item='{$game['genres']}'>
                            <img src='{$game['image']}' alt='{$game['name']} Image'>
                            <div class='content'>
                                <h4>{$game['name']}</h4>
                                <div class='info'>
                                    <p>{$game['genres']}<br><span></span></p>
                                    <a href='games.php'>Info</a>
                                </div>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No games available at the moment.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- Tournament -->
    <!-- Tournament -->
    <div class="tournaments">
        <h2>Live Tournaments</h2>
        <div class="boxbx">
            <?php
            // Fetch live tournaments from the database
            $conn = new mysqli("localhost", "root", "", "GameCafe");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Updated SQL query to include prize pools and entry fees
            $tournament_query = "
            SELECT t.id AS tournament_id, g.name AS game_name, g.image AS game_image, 
                   t.tournament_date, t.tournament_time, 
                   t.prize_pool_1st, t.prize_pool_2nd, t.prize_pool_3rd, t.entry_fee,
                   COUNT(p.id) AS registered_players
            FROM tournaments t
            JOIN games g ON t.game_id = g.id
            LEFT JOIN tmt p ON p.tournament_id = t.id
            WHERE t.tournament_date > CURDATE() 
                  OR (t.tournament_date = CURDATE() AND t.tournament_time > CURTIME())
            GROUP BY t.id, g.name, g.image, t.tournament_date, t.tournament_time,
                     t.prize_pool_1st, t.prize_pool_2nd, t.prize_pool_3rd, t.entry_fee
        ";

            $tournament_result = $conn->query($tournament_query);

            if ($tournament_result === false) {
                echo "<pre>Error: " . $conn->error . "</pre>";
            }

            if ($tournament_result->num_rows > 0) {
                while ($row = $tournament_result->fetch_assoc()) {
                    echo "<div class='box'>
                        <img src='{$row['game_image']}' alt='{$row['game_name']} Image' onerror='this.onerror=null; this.src=\"img/default.png\"'> <!-- Fallback image -->
                        <div class='content'>
                            <p>Game: {$row['game_name']}<br>Date: {$row['tournament_date']}<br>Time: {$row['tournament_time']}<br>
                            <div class='btn'>
                                <a href='login.php' class='Join'>Join now</a>
                            </div>
                        </div>
                      </div>";
                }
            } else {
                echo "<p>No live tournaments at the moment.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>


    <script>
        // Function to filter games by genres
        function filterGames(genres) {
            const cards = document.querySelectorAll('.card');
            const lists = document.querySelectorAll('.list');

            // Show all games if 'all' is clicked, else show specific genres games
            cards.forEach(card => {
                if (genres === 'all' || card.classList.contains(genres)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Update active class for tabs
            lists.forEach(list => {
                list.classList.remove('active');
                if (list.getAttribute('data-filter') === genres) {
                    list.classList.add('active');
                }
            });
        }

        // Initially show all games
        filterGames('all');
    </script>
</body>

</html>