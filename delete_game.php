<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gamecafe";

// Function to connect to the database
function connectToDB($servername, $username, $password, $dbname)
{
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $game_id = $_GET['id'];

    // Delete game from the database
    $conn = connectToDB($servername, $username, $password, $dbname);
    $sql = "DELETE FROM games WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $game_id);

    if ($stmt->execute()) {
        header("Location: modifygame.php"); // Redirect to games list page
        exit;
    } else {
        echo 'Error deleting game: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>