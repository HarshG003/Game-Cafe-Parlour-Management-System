
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GameCafe";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    if (empty($uname) || empty($email) || empty($pass) || empty($cpass)) {
        $error = "Please fill in all fields";
    } elseif ($pass !== $cpass) {
        $error = "Passwords do not match";
    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $query = "SELECT * FROM admin WHERE email = '$email'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        if ($row) {
            $error = "Email already exists";
        } else {
            $query = "INSERT INTO admin (uname, email, pass) VALUES ('$uname', '$email', '$hashed_pass')";
            if ($conn->query($query) === TRUE) {
                $success = "Admin created successfully";
                header("Location: adlogin.php");
                exit;
            } else {
                $error = "Error creating admin: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="Hstyle.css">
    <style>
        body{
            background-color: #ddd;
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
        .box {
            width: 400px;
            padding: 40px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #191919;
            text-align: center;
        }
        .box input[type="email"], .box input[type="text"], .box input[type="password"] {
            border: 0;
            background: none;
            display: block;
            margin: 20px auto;
            text-align: center;
            border: 2px solid #3498db;
            padding: 14px 10px;
            width: 200px;
            outline: none;
            color: white;
            border-radius: 24px;
            transition: 0.25s;
        }
        .box input[type="email"]:focus, .box input[type="text"]:focus, .box input[type="password"]:focus {
            width: 280px;
            border-color: #2ecc71;
        }
        .box input[type="submit"] {
            border: 0;
            background: none;
            display: block;
            margin: 20px auto;
            text-align: center;
            border: 2px solid #2ecc71;
            padding: 14px 40px;
            width: 200px;
            outline: none;
            color: white;
            border-radius: 24px;
            transition: 0.25s;
            cursor: pointer;
        }
        .box input[type="submit"]:hover {
            background: #2ecc71;
        }
        .links {
            padding-top: 20px;
        }
        .links a {
            text-decoration: none;
            color: white;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <header style="background-color: black;" >
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="admin.php">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Games <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
                    <li><a href="addGames.php">Add Game</a></li>
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
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <div class="box">
        <span class="borderLine"></span>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h2>Sign Up</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
            <div class="inputbox">
                <input type="email" required="required" name="email">
                <span>Email</span>
                <i></i>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="uname">
                <span>Username</span>
                <i></i>
            </div>
            <div class="inputbox">
                <input type="password" required="required" name="pass">
                <span>Password</span>
                <i></i>
            </div>
            <div class="inputbox">
                <input type="password" required="required" name="cpass">
                <span>Confirm Password</span>
                <i></i>
            </div>
            <div class="links">
                <input type="submit" value="Sign Up">
            </div>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>