<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "GameCafe");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
        $update_query = "UPDATE users SET password = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            echo "<script>alert('Password reset successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please check and try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="Lstyle.css">
</head>

<body>
    <div class="box">
        <span class="borderLine"></span>
        <form action="forgot_password.php" method="POST">
            <h2>Forgot Password</h2>
            <div class="inputbox">
                <input type="text" name="email" required="required">
                <span>Email</span>
                <i></i>
            </div>
            <div class="inputbox">
                <input type="password" name="new_password" required="required">
                <span>New Password</span>
                <i></i>
            </div>
            <div class="links">
                <a href="login.php">Sign In</a>
                <input type="submit" value="Reset Password">
            </div>
        </form>
    </div>
</body>

</html>