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
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .navbar{
            background: #ff7e5f;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2{
            font-size: 24px;
        }

        .logout{
            background: white;
            color: #ff7e5f;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout:hover{
            background: #f1f1f1;
        }

        .container{
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
        }

        .welcome{
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .welcome h1{
            margin-bottom: 10px;
            color: #333;
        }

        .welcome p{
            color: #666;
        }

        .menu{
            display: grid;
            grid-template-columns:
            repeat(auto-fit,minmax(220px,1fr));

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

        .card p{
            margin-bottom: 20px;
            color: #666;
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

        @media(max-width: 768px){

            .navbar{
                flex-direction: column;
                gap: 10px;
            }

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
            Sistem CRUD Data Produk Toko Telur
        </p>

    </div>

    <div class="menu">

        <div class="card">

            <h3>Tambah Produk</h3>

            <p>
                Menambahkan produk telur baru
            </p>

            <a href="tambah.php">
                Tambah
            </a>

        </div>

        <div class="card">

            <h3>Data Produk</h3>

            <p>
                Melihat seluruh data produk
            </p>

            <a href="data.php">
                Lihat
            </a>

        </div>
        <div class="card">

            <h3>Data Transaksi</h3>

             <p>
                Melihat riwayat pembelian
            </p>

            <a href="transaksi.php">
                 Lihat
            </a>
      </div>

</div>

</body>
</html>