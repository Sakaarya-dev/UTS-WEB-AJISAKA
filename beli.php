<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if(!isset($_GET['id'])){
    die("ID produk tidak ditemukan");
}

$id = $_GET['id'];

$data = mysqli_query($conn,
"SELECT * FROM produk WHERE id='$id'");

$row = mysqli_fetch_assoc($data);

if(!$row){
    die("Produk tidak ditemukan");
}

if(isset($_POST['beli'])){

    $nama_pembeli = $_POST['nama_pembeli'];
    $jumlah = $_POST['jumlah'];

    if($jumlah > $row['stok']){

        $error = "Stok tidak mencukupi";

    } else {

        $total = $jumlah * $row['harga'];

        $stok_baru = $row['stok'] - $jumlah;

        mysqli_query($conn,
        "INSERT INTO transaksi
        (nama_pembeli, nama_telur, jumlah, total_harga)
        VALUES
        ('$nama_pembeli',
        '".$row['nama_telur']."',
        '$jumlah',
        '$total')");

        mysqli_query($conn,
        "UPDATE produk SET
        stok='$stok_baru'
        WHERE id='$id'");

        header("Location: data.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Beli Produk</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container{
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2{
            margin-bottom: 20px;
            text-align: center;
        }

        p{
            margin-bottom: 10px;
            color: #555;
        }

        label{
            display: block;
            margin-top: 15px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input{
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn{
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #17a2b8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover{
            background: #138496;
        }

        .error{
            margin-top: 15px;
            color: red;
            text-align: center;
        }

        .kembali{
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

    </style>

</head>
<body>

<div class="container">

    <h2>Beli Produk</h2>

    <p>
        <b>Nama Produk:</b>
        <?php echo $row['nama_telur']; ?>
    </p>

    <p>
        <b>Jenis:</b>
        <?php echo $row['jenis_telur']; ?>
    </p>

    <p>
        <b>Harga:</b>
        Rp <?php echo number_format($row['harga']); ?>
    </p>

    <p>
        <b>Stok:</b>
        <?php echo $row['stok']; ?>
    </p>

    <form method="POST">

        <label>Nama Pembeli</label>

        <input type="text"
        name="nama_pembeli"
        placeholder="Masukkan nama pembeli"
        required>

        <label>Jumlah Beli</label>

        <input type="number"
        name="jumlah"
        placeholder="Masukkan jumlah beli"
        required>

        <button type="submit"
        name="beli"
        class="btn">
            Beli Sekarang
        </button>

    </form>

    <?php if(isset($error)){ ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <a href="data.php" class="kembali">
        ← Kembali ke Data Produk
    </a>

</div>

</body>
</html>