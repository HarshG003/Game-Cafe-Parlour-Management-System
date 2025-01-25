```php
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

// Initialize error message and success message
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device_name = trim($_POST['device_name'] ?? '');
    $device_type = trim($_POST['device_type'] ?? '');
    $ip_address = trim($_POST['ip_address'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validate input
    if (empty($device_name) || empty($device_type) || empty($ip_address) || empty($description)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($ip_address, FILTER_VALIDATE_IP)) {
        $error = 'Invalid IP address format';
    } else {
        // Check if IP address already exists
        $conn = connectToDB();
        $sql = "SELECT * FROM devices WHERE ip_address = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = 'IP address already exists';
        } else {
            // Insert new device into the database
            $sql = "INSERT INTO devices (device_name, device_type, status, ip_address, description) VALUES (?, ?, 'Vacant', ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $device_name, $device_type, $ip_address, $description);

            if ($stmt->execute()) {
                $success = 'Device added successfully!';
                // Reset form fields
                $device_name = $device_type = $ip_address = $description = '';
            } else {
                $error = 'Error adding device: ' . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Hstyle.css">
    <title>Add Device</title>
    <style>
        /* Reset margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container styling */
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        /* Overlay styling */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        /* Header styling */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }

        /* Input styling */
        input[type="text"],
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }

        /* Textarea styling */
        textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            resize: vertical;
            /* Allows vertical resizing only */
        }

        /* Button styling */
        input[type="submit"] {
            background-color: #45f3ff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        /* Button hover effect */
        input[type="submit"]:hover {
            background-color: #36c5e0;
        }

        /* Success and error messages */
        .success {
            color: #28a745;
            margin-top: 10px;
            text-align: center;
        }

        .error {
            color: #dc3545;
            margin-top: 10px;
            text-align: center;
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
    <header style="background-color:black;">
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
    <div class="overlay"></div>
    <div class="container">
        <h2>Add Device</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <label for="device_name">Device Name:</label>
                <input type="text" name="device_name" value="<?= htmlspecialchars($device_name ?? '') ?>" required>
            </div>
            <div>
                <label for="device_type">Device Type:</label>
                <select name="device_type" required>
                    <?php
                    $deviceTypes = ["Computer", "GamingPhone", "PlayStation"];
                    foreach ($deviceTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type); ?>">
                            <?= htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="ip_address">IP Address:</label>
                <input type="text" name="ip_address" value="<?= htmlspecialchars($ip_address ?? '') ?>" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" rows="4"
                    required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            </div>
            <div>
                <input type="submit" value="Add Device">
            </div>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

