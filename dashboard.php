<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/* =========================
   TOTAL PRODUK
========================= */

$jumlah_produk = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM produk")
);

/* =========================
   TOTAL TRANSAKSI
========================= */

if($_SESSION['role'] == 'admin'){

    $jumlah_transaksi = mysqli_num_rows(
        mysqli_query($conn, "SELECT * FROM pembelian")
    );

}else{

    $user_id = $_SESSION['id'];

    $jumlah_transaksi = mysqli_num_rows(
        mysqli_query($conn,
        "SELECT * FROM pembelian
        WHERE user_id='$user_id'")
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Dashboard Peternakan Telur</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins',sans-serif;

            background:
            linear-gradient(
            rgba(0,0,0,0.55),
            rgba(0,0,0,0.55)),

            url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

            background-size:cover;
            background-position:center;
            background-attachment:fixed;

            min-height:100vh;
            color:white;

            transition:0.4s;
            animation:fadeIn 1s ease;
        }

        /* DARK MODE */

        body.dark{
            background:#111;
            background-image:none;
            color:white;
        }

        body.dark .navbar,
        body.dark .welcome,
        body.dark .card,
        body.dark .stat-card{
            background:rgba(20,20,20,0.9);
        }

        /* NAVBAR */

        .navbar{
            width:100%;
            padding:20px 40px;

            display:flex;
            justify-content:space-between;
            align-items:center;

            background:rgba(34,70,40,0.55);

            backdrop-filter:blur(12px);

            border-bottom:
            1px solid rgba(255,255,255,0.1);

            position:sticky;
            top:0;
            z-index:100;

            animation:slideDown 1s ease;
        }

        .logo{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .logo i{
            font-size:32px;
            color:#ffd166;

            animation:glow 2s infinite;
        }

        .logo h2{
            font-size:30px;
            font-weight:700;
        }

        .menu-nav{
            display:flex;
            gap:12px;
            align-items:center;
        }

        .logout,
        .dark-btn{
            text-decoration:none;

            color:white;

            padding:12px 22px;

            border-radius:12px;

            font-weight:600;

            transition:0.3s;

            border:none;
            cursor:pointer;
        }

        .logout{
            background:
            linear-gradient(45deg,#8b5e3c,#c08552);
        }

        .dark-btn{
            background:
            linear-gradient(45deg,#222,#444);
        }

        .profil-btn{
            width:48px;
            height:48px;

            display:flex;
            justify-content:center;
            align-items:center;

            border-radius:50%;

            text-decoration:none;
            color:white;

            background:
            linear-gradient(45deg,#6a994e,#386641);

            font-size:18px;

            transition:0.3s;

            box-shadow:
            0 0 10px rgba(106,153,78,0.5);
        }

        .profil-btn:hover{
            transform:translateY(-5px) scale(1.05);

            box-shadow:
            0 0 18px rgba(106,153,78,0.8);
        }

        .logout:hover,
        .dark-btn:hover{
            transform:translateY(-5px) scale(1.03);

            box-shadow:
            0 0 20px rgba(255,255,255,0.25);
        }

        /* CONTAINER */

        .container{
            width:90%;
            max-width:1300px;
            margin:40px auto;
        }

        .welcome,
        .card,
        .stat-card{

            background:
            rgba(255,255,255,0.12);

            backdrop-filter:blur(12px);

            border:
            1px solid rgba(255,255,255,0.15);

            box-shadow:
            0 10px 25px rgba(0,0,0,0.2);

            border-radius:25px;

            animation:fadeUp 1s ease;
        }

        /* WELCOME */

        .welcome{
            padding:35px;
            margin-bottom:35px;
        }

        .welcome h1{
            font-size:38px;
            margin-bottom:10px;
        }

        .welcome p{
            color:#eee;
            font-size:16px;
            line-height:1.7;
        }

        /* STATISTIK */

        .stats{
            display:grid;

            grid-template-columns:
            repeat(auto-fit,minmax(220px,1fr));

            gap:20px;

            margin-bottom:35px;
        }

        .stat-card{
            text-align:center;
            padding:30px;

            transition:0.4s;
        }

        .stat-card:hover{
            transform:translateY(-10px) scale(1.03);

            box-shadow:
            0 0 25px rgba(255,255,255,0.2);
        }

        .stat-card i{
            font-size:50px;
            color:#ffd166;
            margin-bottom:15px;
        }

        .stat-card h3{
            font-size:42px;
            margin-bottom:10px;
        }

        .stat-card p{
            color:#f1f1f1;
        }

        /* MENU */

        .menu{
            display:grid;

            grid-template-columns:
            repeat(auto-fit,minmax(280px,1fr));

            gap:25px;
        }

        .card{
            text-align:center;

            padding:35px 25px;

            transition:0.4s;
        }

        .card:hover{
            transform:translateY(-12px);

            box-shadow:
            0 20px 35px rgba(0,0,0,0.35);
        }

        .card i{
            font-size:65px;

            color:#ffd166;

            margin-bottom:20px;

            text-shadow:
            0 0 20px rgba(255,209,102,0.7);

            transition:0.4s;
        }

        .card:hover i{
            transform:scale(1.15) rotate(5deg);
        }

        .card h3{
            font-size:28px;
            margin-bottom:15px;
        }

        .card p{
            color:#f1f1f1;

            line-height:1.7;

            margin-bottom:25px;
        }

        .card a{
            display:inline-block;

            padding:13px 25px;

            border-radius:12px;

            text-decoration:none;

            color:white;

            font-weight:600;

            background:
            linear-gradient(45deg,#6a994e,#386641);

            transition:0.3s;

            box-shadow:
            0 0 10px rgba(106,153,78,0.5);
        }

        .card a:hover{
            transform:scale(1.08);

            box-shadow:
            0 0 20px rgba(106,153,78,0.9);
        }

        /* ANIMASI */

        @keyframes fadeIn{
            from{
                opacity:0;
            }

            to{
                opacity:1;
            }
        }

        @keyframes fadeUp{
            from{
                opacity:0;
                transform:translateY(30px);
            }

            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @keyframes slideDown{
            from{
                opacity:0;
                transform:translateY(-50px);
            }

            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @keyframes glow{
            0%{
                text-shadow:0 0 5px #ffd166;
            }

            50%{
                text-shadow:0 0 20px #ffd166;
            }

            100%{
                text-shadow:0 0 5px #ffd166;
            }
        }

        /* RESPONSIVE */

        @media(max-width:768px){

            .navbar{
                flex-direction:column;
                gap:15px;
            }

            .welcome h1{
                font-size:28px;
            }

            .logo h2{
                font-size:24px;
            }

            .menu-nav{
                flex-wrap:wrap;
                justify-content:center;
            }

        }

    </style>

</head>
<body>

<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">

        <i class="fa-solid fa-egg"></i>

        <h2>Toko Telur</h2>

    </div>

    <div class="menu-nav">

        <a href="profil.php" class="profil-btn">
            <i class="fa-solid fa-user"></i>
        </a>

        <button class="dark-btn" onclick="darkMode()">
            🌙 Dark Mode
        </button>

        <a href="logout.php" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>

<!-- CONTAINER -->

<div class="container">

    <!-- WELCOME -->

    <div class="welcome">

        <h1>
            Selamat Datang,
            <?php echo $_SESSION['user']; ?>
        </h1>

        <p>
            Sistem Manajemen Produk,
            Stok, dan Transaksi
            Toko Telur Modern
        </p>

    </div>

    <!-- STATISTIK -->

    <div class="stats">

        <div class="stat-card">

            <i class="fa-solid fa-box"></i>

            <h3>
                <?php echo $jumlah_produk; ?>
            </h3>

            <p>Total Produk</p>

        </div>

        <div class="stat-card">

            <i class="fa-solid fa-cart-shopping"></i>

            <h3>
                <?php echo $jumlah_transaksi; ?>
            </h3>

            <p>Total Transaksi</p>

        </div>

    </div>

    <!-- MENU -->

    <div class="menu">

        <?php if($_SESSION['role'] == 'admin'){ ?>

            <div class="card">

                <i class="fa-solid fa-plus"></i>

                <h3>Tambah Produk</h3>

                <p>
                    Tambah produk baru ke toko.
                </p>

                <a href="tambah.php">
                    Tambah Produk
                </a>

            </div>

            <div class="card">

                <i class="fa-solid fa-table"></i>

                <h3>Data Produk</h3>

                <p>
                    Kelola seluruh produk toko.
                </p>

                <a href="data.php">
                    Lihat Produk
                </a>

            </div>

            <div class="card">

                <i class="fa-solid fa-cart-shopping"></i>

                <h3>Semua Transaksi</h3>

                <p>
                    Melihat seluruh transaksi user.
                </p>

                <a href="transaksi.php">
                    Lihat Transaksi
                </a>

            </div>

        <?php } else { ?>

            <div class="card">

                <i class="fa-solid fa-store"></i>

                <h3>Beli Produk</h3>

                <p>
                    Lihat dan beli produk telur.
                </p>

                <a href="data.php">
                    Beli Produk
                </a>

            </div>

            <div class="card">

                <i class="fa-solid fa-clock-rotate-left"></i>

                <h3>Riwayat Pembelian</h3>

                <p>
                    Lihat riwayat pembelian kamu.
                </p>

                <a href="riwayat.php">
                    Lihat Riwayat
                </a>

            </div>

        <?php } ?>

    </div>

</div>

<script>

function darkMode(){
    document.body.classList.toggle('dark');
}

</script>

</body>
</html>