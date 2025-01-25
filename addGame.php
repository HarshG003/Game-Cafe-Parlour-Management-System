<?php
require_once 'config.php';

if (isset($_POST["submit"])) {
    $gn = $_POST["gameName"];
    $gen = $_POST["genres"];
    $des = $_POST["description"];

    $upload_dir = "uploads/";
    $image = $_FILES["imageUpload"]["name"];
    $upload_file = $upload_dir . basename($image);
    $imageType = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
    $check = $_FILES["imageUpload"]["size"];
    $upload_ok = 1;

    // Check if game already exists in database
    $stmt = $conn->prepare("SELECT * FROM games WHERE name = ?");
    $stmt->bind_param("s", $gn);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Game already exists')</script>";
        $upload_ok = 0;
    }

    if (file_exists($upload_file)) {
        echo "<script>alert('The file already exists')</script>";
        $upload_ok = 0;
    } elseif ($check <= 0) {
        echo "<script>alert('The photo size is 0 please change the photo')</script>";
        $upload_ok = 0;
    } elseif ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png' && $imageType != 'gif') {
        echo "<script>alert('Please change the image format')</script>";
        $upload_ok = 0;
    }

    if ($upload_ok == 0) {
        echo "<script>alert('Sorry your file can't be uploaded. Please try again')</script>";
    } else {
        if (!empty($gn) && !empty($des)) {
            if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $upload_file)) {
                $stmt = $conn->prepare("INSERT INTO games (name, genres, description, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $gn, $gen, $des, $upload_file);

                if ($stmt->execute()) {
                    echo "<script>alert('Successfully uploaded')</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "')</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Failed to move uploaded file')</script>";
            }
        } else {
            echo "<script>alert('Please fill in all fields')</script>";
        }
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
    <link rel="stylesheet" href="Hstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="addGame.css">
    <style>
        .nav {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .nav li {
            position: relative;
        }

        .nav a {
            text-decoration: none;
            padding: 15px;
            display: block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-menu,
        .dropdown-menu.show {
            display: block;
            background-color: transparent;
        }
    </style>
    <title>Upload Game</title>
</head>

<body>
<header>
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="admin.php">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">Games <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown-menu">
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
            <li><a href="adadmin.php">Add Admin</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <section id="upload_container">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="gameName" id="gameName" placeholder="Game Name" required />
            <input type="text" name="genres" id="genres" placeholder="Genres" required />
            <input type="text" name="description" id="description" placeholder="Description" required />
            <input type="file" name="imageUpload" id="imageUpload" required />
            <input type="submit" value="Upload" name="submit">
        </form>
    </section>
    <script>
        var uploadImage = document.getElementById("imageUpload");

        uploadImage.addEventListener("change", function () {
            var file = this.files[0];
            var choose = document.getElementById("choose");
            if (choose) {
                choose.innerHTML = "You can change (" + file.name + ") picture";
            }
        });
    </script>
</body>

</html>