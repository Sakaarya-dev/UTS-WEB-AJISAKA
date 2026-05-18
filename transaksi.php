<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: dashboard.php");
    exit;
}

include 'koneksi.php';

/* =========================
   AMBIL DATA TRANSAKSI
========================= */

$query = mysqli_query($conn,
"SELECT * FROM pembelian
ORDER BY tanggal DESC");

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Data Transaksi</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
    rgba(0,0,0,0.6),
    rgba(0,0,0,0.6)),
    url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

    background-size:cover;
    background-position:center;
    background-attachment:fixed;

    min-height:100vh;

    color:white;

    padding:30px;
}

.container{
    width:95%;
    max-width:1300px;

    margin:auto;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(12px);

    border:
    1px solid rgba(255,255,255,0.15);

    border-radius:25px;

    padding:35px;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.3);
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:30px;

    flex-wrap:wrap;
    gap:15px;
}

h1{
    font-size:35px;
}

h1 i{
    color:#ffd166;
}

.back{
    text-decoration:none;

    background:
    linear-gradient(45deg,#6a994e,#386641);

    color:white;

    padding:12px 20px;

    border-radius:12px;

    font-weight:600;

    transition:0.3s;
}

.back:hover{
    transform:translateY(-3px);

    box-shadow:
    0 10px 20px rgba(0,0,0,0.25);
}

.table-box{
    overflow-x:auto;
}

table{
    width:100%;

    border-collapse:collapse;

    overflow:hidden;

    border-radius:18px;
}

th{
    background:rgba(0,0,0,0.45);

    padding:16px;

    font-size:15px;
}

td{
    background:rgba(255,255,255,0.08);

    padding:16px;

    text-align:center;

    transition:0.3s;
}

tr:hover td{
    background:rgba(255,255,255,0.16);
}

.tanggal{
    color:#90e0ef;
    font-size:14px;
}

.total{
    color:#ffd166;
    font-weight:bold;
}

.empty{
    text-align:center;

    padding:30px;

    color:#eee;
}

@media(max-width:768px){

    .header{
        flex-direction:column;
        align-items:flex-start;
    }

    h1{
        font-size:28px;
    }

    th, td{
        font-size:14px;
        padding:12px;
    }

    table{
        min-width:1000px;
    }

}

</style>

</head>
<body>

<div class="container">

    <div class="header">

        <h1>
            <i class="fa-solid fa-cart-shopping"></i>
            Data Transaksi
        </h1>

        <a href="dashboard.php" class="back">
            ← Kembali ke Dashboard
        </a>

    </div>

    <div class="table-box">

        <table>

            <tr>

                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
                <th>Rating</th>
                <th>Ulasan</th>

            </tr>

            <?php
            $no = 1;

            if(mysqli_num_rows($query) > 0){

                while($data = mysqli_fetch_assoc($query)){
            ?>

            <tr>

                <td>
                    <?php echo $no++; ?>
                </td>

                <td>
                    <?php echo $data['nama_produk']; ?>
                </td>

                <td>
                    Rp <?php echo number_format($data['harga']); ?>
                </td>

                <td>
                    <?php echo $data['jumlah']; ?>
                </td>

                <td class="total">
                    Rp <?php echo number_format($data['total_harga']); ?>
                </td>

                <td class="tanggal">

                    <?php

                    date_default_timezone_set('Asia/Jakarta');

                    if(
                        $data['tanggal'] != "" &&
                        $data['tanggal'] != "0000-00-00 00:00:00"
                    ){

                        echo date(
                            'd M Y - H:i',
                            strtotime($data['tanggal'])
                        );

                    }else{

                        echo "-";

                    }

                    ?>

                </td>

                <td>
                    <?php echo $data['rating']; ?> ⭐
                </td>

                <td>
                    <?php echo $data['ulasan']; ?>
                </td>

            </tr>

            <?php
                }

            } else {
            ?>

            <tr>

                <td colspan="8" class="empty">
                    Belum ada transaksi
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>