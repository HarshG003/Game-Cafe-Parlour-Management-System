
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GameCafe";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully";
}

$query = "SELECT * FROM msg";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $num = $result->num_rows;
} else {
    $num = 0;
}
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <style type="text/css">
        *{
        padding:0;
        margin: 0;
        box-sizing: border-box;
    }
    body{   
        width: 100%;
        min-height: 100vh;
        background-color: #050e2d;
        background-image: url("img/re.jpg");
            background-repeat: no-repeat;
            
            height: 500px; /* You must set a specified height */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover;
            padding: 20px 100px; 
    }
    .container{
        max-width: 900px;
        margin: 100px auto;
        width: 1000%;
    }
    table{
        
        border-collapse: collapse;
        width: 100%;
    }
    table th{
        background-color:#5d5d7d;
        color: #fff;
        padding: 10px;
    }
    table td{
        padding: 12px;
        color: #fff;
        font-size: 1em;
        text-align: center;
    }
    table tr:nth-child(odd){
        background-color: #797676;
    }

    </style>
</head>
<body>
    <div class="container">
        <table border="1">
            <tr>
                
                <th>name</th>
                
                <th>email</th>
                <th>message</th>
            </tr>
            <?php 
            if($num>0){
                while($data = mysqli_fetch_assoc($connect)){
                    echo "
                    <tr>
                    
                    <td>".$data['name']."</td>
                    
                    <td>".$data['email']."</td>
                    <td>".$data['msg']."</td>
                    </tr>
                    ";
                }

            }
            ?>
        </table>

    </div>
</body>
</html>