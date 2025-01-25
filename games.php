<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="Hstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Games</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #050e2d;
            overflow-x: hidden;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
        }

        .nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav li {
            margin-right: 20px;
        }

        .nav a {
            color: #fff;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .nav a:hover {
            color: #ccc;
        }

        .toggleMenu {
            cursor: pointer;
            font-size: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .col-md-4 {
            flex-basis: 30%;
            margin: 20px;
            transition: transform 0.2s ease;
        }

        .col-md-4:hover {
            transform: scale(1.05);
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .col-md-4 {
                flex-basis: 45%;
            }
        }

        @media (max-width: 480px) {
            .col-md-4 {
                flex-basis: 100%;
            }
        }
    </style>
</head>
<body>
<header>
        <a href="#" class="logo">Gammers</a>
        <ul class="nav">
            <li><a href="index.php">Home</a></li>
        </ul>
        <div class="action">
        </div>
        <div class="toggleMenu" onclick="toggleMenu();"></div>
    </header><br><br><br><br>
    <div class="container py-5">
        <div class="row mt-4">
        <?php 
            require_once 'config.php';
            $query = "SELECT * FROM games";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="games images" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $row['name']; ?></h2>
                        <h6 class="card-title"><?php echo $row['genres']; ?></h6>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>

    </div>
    
</body>
</html>