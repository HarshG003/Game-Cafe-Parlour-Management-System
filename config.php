<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GameCafe";

$conn= mysqli_connect($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("failed");
}else{
    //echo 'ok';
}

?>
