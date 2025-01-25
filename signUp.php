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
        echo "Please fill in all fields";
    } elseif ($pass !== $cpass) {
        echo "Passwords do not match";
    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();

        if ($row) {
            echo "Email already exists";
        } else {
            $query = "INSERT INTO users (uname, email, pass) VALUES ('$uname', '$email', '$hashed_pass')";
            if ($conn->query($query) === TRUE) {
                echo "<p style='color:white'> User created successfully</p>";
                header("Location: login.php");
                exit;
            } else {
                echo "Error creating user: " . $conn->error;
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Lstyle.css">
</head>

<body>
    <div class="box" style=" height: 550px; " >
        <span class="borderLine"></span>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <a href="index.php"><i class='bx bx-window-close'></i></a>
            <h2>Sign Up</h2>
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
                <a href="login.php">Sign In</a>
                <input type="submit" value="Sign Up">
            </div>
    </div>
    </form>
    </div>

</body>

</html>
<?php
$conn->close();
?>