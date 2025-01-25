<?php
session_start();
$conn = new mysqli("localhost", "root", "", "gamecafe");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function verifyLogin($uname, $pass, &$admin_id)
{
    $conn = new mysqli("localhost", "root", "", "gamecafe");

    // Retrieve the hashed password from the database and the admin's id
    $sql = "SELECT id, pass FROM admin WHERE uname = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the stored hashed password and admin id
        $row = $result->fetch_assoc();
        $hashedPassword = $row['pass'];
        $admin_id = $row['id'];

        // Verify the entered password with the stored hashed password
        if (password_verify($pass, $hashedPassword)) {
            $stmt->close();
            $conn->close();
            return true; // Password matches
        } else {
            $stmt->close();
            $conn->close();
            return false; // Password does not match
        }
    } else {
        $stmt->close();
        $conn->close();
        return false; // Username not found
    }
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = trim($_POST['uname']);
    $pass = trim($_POST['password']);

    if (empty($uname) || empty($pass)) {
        $error = 'Please fill in all fields';
    } elseif (verifyLogin($uname, $pass, $admin_id)) {
        // Prevent session fixation attacks by regenerating session ID
        session_regenerate_id(true);
        $_SESSION['uname'] = $uname;
        $_SESSION['id'] = $admin_id; // Save admin ID in session for further use
        header("Location: admin.php");
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Admin Login</title>
    <link rel="stylesheet" href="Lstyle.css">
</head>

<body>
    <div class="box">
        <span class="borderLine"></span>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <a href="index.php"><i class='bx bx-window-close'></i></a>
            <h2>ADMIN LOGIN</h2>
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
            <input type="submit" value="Login">
            <!-- Display error message if any -->
            <?php if (!empty($error)): ?>
                <div class="error" id="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>
