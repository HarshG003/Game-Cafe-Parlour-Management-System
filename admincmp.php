<?php
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

// Fetch devices and associated user information from the database
$conn = connectToDB();
$sql = "SELECT d.*, u.uname FROM devices d LEFT JOIN users u ON d.user_id = u.id"; // Assuming user_id is the foreign key in devices table
$result = $conn->query($sql);
$devices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
}
$conn->close();

// Update device details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectToDB();
    $id = $_POST["id"];
    $device_name = $_POST["device_name"];
    $ip_address = $_POST["ip_address"];
    $description = $_POST["description"];

    $sql = "UPDATE devices SET device_name = '$device_name', ip_address = '$ip_address', description = '$description' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Device details updated successfully";
    } else {
        echo "Error updating device details: " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Management</title>
    <link rel="stylesheet" href="Hstyle.css">
    <link rel="stylesheet" href="cmp.css">
    <style>
        .device-card{
            color: aliceblue;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 100px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }

        .modify-form {
            display: none;
        }
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
    background-color: gray;
}
    </style>
</head>

<body>
    <header style="background-color:black; margin-bottom: 100px;">
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
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <h1 style="color: aliceblue; margin-top: 100px; margin-left: 700px" >Device Management</h1>
    <div class="device-container">
        <?php foreach ($devices as $device): ?>
            <div class="device-card <?php echo ($device['status'] == 'Occupied') ? 'occupied' : 'vacant'; ?>">
                <h3><?php echo htmlspecialchars($device['device_name']); ?></h3>
                <p>Status: <?php echo htmlspecialchars($device['status']); ?></p>
                <p>IP Address: <?php echo htmlspecialchars($device['ip_address']); ?></p>
                <p>Description: <?php echo htmlspecialchars($device['description']); ?></p>
                <?php if ($device['status'] == 'Occupied'): ?>
                    <p>User: <?php echo htmlspecialchars($device['uname']); ?></p>
                <?php endif; ?>
                <div class="dropdown" style="float: right;">
                    <button class="dropbtn">Edit</button>
                    <div class="dropdown-content">
                        <a href="#" onclick="showModifyForm(<?php echo $device['id']; ?>)">Modify</a>
                        <a href="delete_device.php?id=<?php echo $device['id']; ?>">Delete</a>
                    </div>
                </div>
                <form class="modify-form" id="modify-form-<?php echo $device['id']; ?>" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input type="hidden" name="id" value="<?php echo $device['id']; ?>">
                    <label for="device_name">Device Name:</label>
                    <input type="text" id="device_name" name="device_name" value="<?php echo htmlspecialchars($device['device_name']); ?>"><br><br>
                    <label for="ip_address">IP Address:</label>
                    <input type="text" id="ip_address" name="ip_address" value="<?php echo htmlspecialchars($device['ip_address']); ?>"><br><br>
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($device['description']); ?>"><br><br>
                    <input type="submit" value="Save Changes">
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function showModifyForm(deviceId) {
            document.getElementById("modify-form-" + deviceId).style.display = "block";
        }
    </script>
</body>

</html>