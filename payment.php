<?php
session_start(); // Start session to access user data

// Database connection
$conn = new mysqli('localhost', 'root', '', 'GameCafe');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Check if form is submitted
if (isset($_POST['Proceed to Checkout'])) {
    $upiRefId = $_POST['upiRefId'];
    $amount = $_POST['amount']; // Get the amount from the form

    // Validate user input
    if (empty($upiRefId) || empty($amount)) {
        echo "Please fill in all fields.";
    } else {
        // Save payment details to the payment table
        $conn = new mysqli('localhost', 'root', '', 'GameCafe');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO payment (name, email, mobile, upiRefId, amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $uname, $email, $phone, $upiRefId, $amount);

        if ($stmt->execute()) {
            // Payment successful, redirect to the bill page
            header("Location: bill.php?amount=$amount,refid=$upiRefId");
            exit; // Ensure no further code is executed after the redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--- custom css file link -->
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

    <script>
        $(document).ready(function() {
            const params = new URLSearchParams(window.location.search);
            const amount = params.get('amount'); // Get the amount from the URL
            const r = Math.floor(Math.random() * 2) + 1;
            let upiID;
            if (r === 1) {
                upiID = '9373045003@pthdfc';
            } else if (r === 2) {
                upiID = 'harshaldesai218@okaxis';
            }
            const qrText = `upi://pay?pa=${upiID}&am=${amount}&cu=INR`;

            $('#qrcode').empty();
            new QRCode(document.getElementById("qrcode"), qrText);
        });
    </script>
    <link rel="stylesheet" href="payment.css">
</head>

<body>

    <div class="container">

        <form method="post">
            <div class="row">

                <div class="col">

                    <h3 class="title">Details</h3>

                    <div class="inputbox">
                        <span>Full Name:</span>
                        <input type="text" name="name" value="<?php echo $uname ?? ''; ?>" readonly>
                    </div>
                    <div class="inputbox">
                        <span>Mobile No:</span>
                        <input type="text" name="mobile" value="<?php echo $phone ?? ''; ?>" readonly>
                    </div>
                    <div class="inputbox">
                        <span>UPI Ref ID :</span>
                        <input type="text" name="upiRefId" placeholder="Enter UPI Ref ID " required>
                    </div>
                    <input type="hidden" name="amount" value="<?php echo $amount ?? ''; ?>">
                    <!-- Pass the amount as a hidden input -->

                </div>

                <div class="col">

                    <h3 class="title">Payment</h3>
                    <div class="qrcode" id="qrcode"></div>

                    <div id="payable-container">
                        <label for="payable">Payable Amount:</label>
                        <input type="text" id="payable" value="<?php echo $_GET['amount'] ?? ''; ?>" readonly>
                    </div>

                </div>

            </div>

            <input type="submit" value="Proceed to Checkout" name="Proceed to Checkout" class="submit-btn" id="submit-btn">

        </form>

    </div>

    <script>
        function submitbill() {
            const mno = document.getElementById('mobile').value;
            const amt = document.getElementById('amount').value;

            const formData = new FormData();
            formData.append('mobile', mno);
            formData.append('amount', amt);

            fetch(window.location.href, { // Current URL handles request
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to payment.php with fees passed in URL
                        window.location.href = `bill_print.php?amount=${amount}`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>