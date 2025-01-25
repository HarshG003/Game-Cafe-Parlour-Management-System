<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GameCafe"; // Updated database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Assuming user session is used

// Check if user session is set
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

// Fetch user information from the session
$userId = $_SESSION['id']; // Assuming the user is logged in
$userSql = "SELECT uname, email FROM users WHERE id = '$userId'";
$userResult = $conn->query($userSql);
$user = $userResult->fetch_assoc();

// Check if the user has already reserved a device
$hasReserved = isset($_SESSION['reserved']) ? $_SESSION['reserved'] : false;

// Handle reservation if a POST request is made
// Handle reservation if a POST request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deviceId = $_POST['deviceId'];
    $hours = $_POST['hours'];

    // Check if the user has already reserved a device
    $userSql = "SELECT id FROM devices WHERE user_id = '$userId' AND status = 'Occupied'";
    $userResult = $conn->query($userSql);
    if ($userResult->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "You have already reserved a device. Please cancel the existing reservation before reserving another device."]);
        exit();
    }

    switch ($hours) {
        case 1:
            $fees = 100;
            break;
        case 2:
            $fees = 200;
            break;
        case 3:
            $fees = 300;
            break;
        case 4:
            $fees = 350;
            break;
        case 5:
            $fees = 400;
            break;
        default:
            $fees = 0;
            break;
    }

    // Update device status and reservation details
    $sql = "UPDATE devices 
            SET status='Occupied', reserved_at=NOW(), reservation_duration='$hours', user_id='$userId' 
            WHERE id='$deviceId'";

    if ($conn->query($sql) === TRUE) {
        // Set session variable for reserved status and amount
        $_SESSION['reserved'] = true; // User has reserved a device
        $_SESSION['reservation_amount'] = $fees; // Store the fees in session
        echo json_encode(["success" => true, "message" => "Device reserved successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
    exit(); // Prevent the rest of the HTML from loading after handling the POST request
}

// Fetch devices from the database
$deviceSql = "SELECT d.*, u.uname, u.email FROM devices d 
              LEFT JOIN users u ON d.user_id = u.id";
$result = $conn->query($deviceSql);

if (isset($_POST['submit']))
    header("Location: payment.php?amount=$fees");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameCafe Devices</title>
    <link rel="stylesheet" href="cmp.css">
    <link rel="stylesheet" href="Hstyle.css">
</head>

<body>
    <header style="background-color: #414141;">
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="tournement.php">Tournament</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header>
    <h1 style="margin-top: 100px; margin-left: 40%; color:aliceblue;" >Available Devices</h1>

    <div class="device-container">
        <?php
        if ($result->num_rows > 0) {
            while ($device = $result->fetch_assoc()) {
                // Initialize remaining_seconds and remaining_time
                $remaining_seconds = 0; // Default to 0
                $remaining_time = '00:00:00'; // Default to 0 time

                // Calculate remaining time if the device is occupied
                if ($device['status'] == 'Occupied') {
                    $reserved_at = strtotime($device['reserved_at']);
                    $current_time = time();
                    $reserved_for = $device['reservation_duration'] * 3600; // Convert hours to seconds
                    $remaining_seconds = $reserved_for - ($current_time - $reserved_at) - 19800;

                    // If time remains, format it into HH:MM:SS
                    if ($remaining_seconds > 0) {
                        $remaining_time = gmdate("H:i:s", $remaining_seconds);
                    } else {
                        // Reset the device if time expired
                        $conn->query("UPDATE devices SET status='Vacant', reserved_at=NULL, reservation_duration=NULL, user_id=NULL WHERE id=" . $device['id']);
                        $device['status'] = 'Vacant'; // Update the device status for display
                    }
                }

                // Device card rendering
                echo '<div class="device-card" id="device-' . $device['id'] . '" style="background-color: ' . ($device['status'] == 'Occupied' ? 'red' : 'green') . ';">';
                echo '<h3>' . $device['device_name'] . ' (' . $device['device_type'] . ')</h3>';
                echo '<p>IP Address: ' . $device['ip_address'] . '</p>';
                echo '<p>Description: ' . $device['description'] . '</p>';

                // Show Reserve button for vacant devices
                if ($device['status'] == 'Vacant') {
                    echo '<button class="reserve-btn" onclick="openReserveForm(' . $device['id'] . ')">Reserve</button>';
                } elseif ($device['status'] == 'Occupied') {
                    // Show remaining time for occupied devices
                    echo '<div class="remaining-time" id="remaining-time-' . $device['id'] . '">Remaining Time: <span>' . $remaining_time . '</span></div>';
                    echo '<script>startCountdown(' . $device['id'] . ', ' . $remaining_seconds . ');</script>';
                }

                echo '</div>';
            }
        } else {
            echo "No devices available.";
        }
        ?>
    </div>

    <!-- Reservation Form -->
    <div class="overlay" id="overlay"></div>
    <div id="reserveForm">
        <form id="reserveFormDetails">
            <input type="hidden" id="deviceId" name="deviceId">
            <label>Name: </label>
            <input type="text" id="name" value="<?php echo htmlspecialchars($user['uname']); ?>" readonly><br>
            <label>Email: </label>
            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly><br>
            <label>Hours: </label>
            <select id="hours" onchange="calculateFees()">
                <option value="0">Select Hours</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select><br>
            <label>Fees: </label>
            <input type="text" id="fees" readonly><br>
            <button type="button" onclick="submitReservation()" name="submit">Submit</button>
        </form>
    </div>

    <script>
        // Show the reservation form
        function openReserveForm(deviceId) {
            document.getElementById('deviceId').value = deviceId;
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('reserveForm').style.display = 'block';
        }

        // Close form on overlay click
        document.getElementById('overlay').onclick = function() {
            document.getElementById('reserveForm').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Calculate fees based on selected hours
        function calculateFees() {
            const hours = document.getElementById('hours').value;
            const fee = hours * 100;
            document.getElementById('fees').value = fee;
        }

        // Submit reservation data to server
        function submitReservation() {
            const deviceId = document.getElementById('deviceId').value;
            const hours = document.getElementById('hours').value;
            const fees = document.getElementById('fees').value; // Get the calculated fees

            const formData = new FormData();
            formData.append('deviceId', deviceId);
            formData.append('hours', hours);

            fetch(window.location.href, { // Current URL handles request
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to payment.php with fees passed in URL
                        window.location.href = `payment.php?amount=${fees}`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Countdown for devices
        function startCountdown(deviceId, remainingSeconds) {
            const remainingTimeElement = document.getElementById('remaining-time-' + deviceId).getElementsByTagName('span')[0];

            const countdownInterval = setInterval(() => {
                if (remainingSeconds <= 0) {
                    clearInterval(countdownInterval);
                    remainingTimeElement.innerHTML = "Time Expired";

                    // Reset device status to vacant in the database
                    fetch(`reset_device.php?id=${deviceId}`, { // Make sure to create reset_device.php for resetting status
                        method: 'GET',
                    });
                } else {
                    remainingSeconds--;
                    remainingTimeElement.innerHTML = gmdate("H:i:s", remainingSeconds);
                }
            }, 1000);
        }

        // Helper function to format seconds to HH:MM:SS
        function gmdate(format, seconds) {
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds % 3600) / 60);
            let secs = seconds % 60;
            return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (secs < 10 ? "0" + secs : secs);
        }
    </script>
</body>

</html>