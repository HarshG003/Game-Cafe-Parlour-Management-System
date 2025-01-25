<?php
session_start();
include 'config.php'; // Include your config.php for database connection

// Initialize variables
$uname = $email = $phone = $amount = '';

// Fetching user details from the users table
$userId = $_SESSION['id']; // Assuming user ID is stored in the session
$query = "SELECT uname, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($uname, $email, $phone);
$stmt->fetch();
$stmt->close();

$conn->close();

// Check if the 'amount' is set in the URL
if (isset($_GET['amount'])) {
    $amount = $_GET['amount'];
    // $refid = $_GET['refid'];
} else {
    // Handle error accordingly, e.g., redirect back or show an error message
    header("Location: payment.php"); // Redirect back to payment if amount is not set
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Bill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        h3.title {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .bill-container {
            position: absolute;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            min-width: 500px;
            margin: 0 auto;
            top: 30%;
            left: 35%;
        }

        .details {
            margin-bottom: 15px;
        }

        .details span {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .btn {
            display: flex;
        }

        .print-btn {
            /* display: block; */
            width: 45%;
            padding: 10px;
            background: darkcyan;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            margin-top: 20px;
        }

        
        .btn-print{
            
        }

        .back-btn {
            /* display: block; */
            width: 45%;
            padding: 10px;
            background: #007BFF;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            margin-top: 20px;
            margin-left: 20px;
        }

        .print-btn:hover {
            background: darkblue;
        }

        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <div class="bill-container" name="bill">
        <h3 class="title">Bill Summary</h3>

        <div class="details">
            <span>Full Name: <?php echo htmlspecialchars($uname); ?></span>
            <span>Email: <?php echo htmlspecialchars($email); ?></span>
            <span>Mobile No: <?php echo htmlspecialchars($phone); ?></span>
            <span>Amount Paid: ₹<?php echo htmlspecialchars($amount); ?></span>
        </div>

        <div class="btn <?php if(isset($_GET['ptbtn'])) echo 'btn-print' ?>">
            <button onclick="printbill()" class="print-btn" name="ptbtn">PRINT BILL</button>
            <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </div>
    </div>

    <script>
        function printbill() {
            window.print();
        }
    </script>

</body>

</html>