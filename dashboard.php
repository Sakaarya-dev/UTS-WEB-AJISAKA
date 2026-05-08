<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Toko Telur</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .navbar{
            background: #ff7e5f;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2{
            font-size: 22px;
        }

        .logout{
            background: white;
            color: #ff7e5f;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .container{
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
        }

        .welcome{
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .menu{
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
            gap: 20px;
        }

        .card{
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card h3{
            margin-bottom: 15px;
            color: #333;
        }

        .card a{
            display: inline-block;
            padding: 10px 20px;
            background: #ff7e5f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .card a:hover{
            background: #eb5e3b;
        }

    </style>

</head>
<body>

<div class="navbar">

    <h2>Toko Telur</h2>

    <a href="logout.php" class="logout">
        Logout
    </a>

</div>

<div class="container">

    <div class="welcome">
        <h1>
            Selamat Datang,
            <?php echo $_SESSION['user']; ?>
        </h1>

        <p>
            Sistem CRUD Toko Telur
        </p>
    </div>

    <div class="menu">

        <div class="card">
            <h3>Tambah Data</h3>

            <a href="tambah.php">
                Masuk
            </a>
        </div>

        <div class="card">
            <h3>Data Telur</h3>

            <a href="data.php">
                Lihat
            </a>
        </div>

        <div class="card">
            <h3>Kelola User</h3>

            <a href="#">
                Kelola
            </a>
        </div>

    </div>

</div>

</body>
</html>