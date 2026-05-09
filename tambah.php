<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if(isset($_POST['simpan'])){

    $nama = $_POST['nama_telur'];
    $jenis = $_POST['jenis_telur'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier'];

    mysqli_query($conn,
    "INSERT INTO produk
    (nama_telur, jenis_telur, harga, stok, supplier)
    VALUES
    ('$nama', '$jenis', '$harga', '$stok', '$supplier')");

    header("Location: data.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>

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
            margin-bottom: 25px;
            text-align: center;
            color: #333;
        }

        label{
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input{
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        input:focus{
            border-color: #ff7e5f;
        }

        .btn{
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover{
            background: #218838;
        }

        .kembali{
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #ff7e5f;
            font-weight: bold;
        }

        .kembali:hover{
            text-decoration: underline;
        }

    </style>

</head>
<body>

<div class="container">

    <h2>Tambah Produk Telur</h2>

    <form method="POST">

        <label>Nama Telur</label>
        <input type="text"
        name="nama_telur"
        placeholder="Masukkan nama telur"
        required>

        <label>Jenis Telur</label>
        <input type="text"
        name="jenis_telur"
        placeholder="Masukkan jenis telur"
        required>

        <label>Harga</label>
        <input type="number"
        name="harga"
        placeholder="Masukkan harga"
        required>

        <label>Stok</label>
        <input type="number"
        name="stok"
        placeholder="Masukkan stok"
        required>

        <label>Supplier</label>
        <input type="text"
        name="supplier"
        placeholder="Masukkan supplier"
        required>

        <button type="submit"
        name="simpan"
        class="btn">
            Simpan Produk
        </button>

    </form>

    <a href="data.php" class="kembali">
        ← Kembali ke Data Produk
    </a>

</div>

</body>
</html>