<?php
session_start();
$conn = new mysqli("localhost", "root", "", "gamecafe");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["uname"]) && !empty($_POST["password"])) {
        $uname = $_POST["uname"];
        $password = $_POST["password"];

        $query = "SELECT * FROM users WHERE uname = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['pass'];
            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true); // Regenerate session ID to prevent session fixation
                $_SESSION["uname"] = $uname;
                $_SESSION["id"] = $row['id']; // Store the user ID in the session
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Please fill in all fields";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Lstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="box">
        <span class="borderLine"></span>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <a href="index.php"><i class='bx bx-window-close'></i></a>
            <h2>Sign in</h2>
            <div class="inputbox">
                <input type="text" name="uname" required="required">
                <span>Username</span>
                <i></i>
            </div>
            <div class="inputbox">
                <input type="password" name="password" required="required">
                <span>Password</span>
                <i></i>
            </div>
            <div class="links">
                <a href="fPassword.php">Forgot Password</a>
                <a href="signUp.php">SignUp</a>
            </div>
            <input type="submit" value="Login">
            <div class="error"><?php echo $error; ?></div>
        </form>
    </div>
</body>
</html>


