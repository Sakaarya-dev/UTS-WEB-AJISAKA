<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if(!isset($_GET['id'])){
    die("ID tidak ditemukan");
}

$id = $_GET['id'];

$data = mysqli_query($conn,
"SELECT * FROM produk WHERE id='$id'");

$row = mysqli_fetch_assoc($data);

if(!$row){
    die("Data tidak ditemukan");
}

if(isset($_POST['update'])){

    $nama = $_POST['nama_telur'];
    $jenis = $_POST['jenis_telur'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier'];

    mysqli_query($conn,
    "UPDATE produk SET
    nama_telur='$nama',
    jenis_telur='$jenis',
    harga='$harga',
    stok='$stok',
    supplier='$supplier'
    WHERE id='$id'");

    header("Location: data.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>

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
            border-color: orange;
        }

        .btn{
            width: 100%;
            padding: 12px;
            background: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover{
            background: #e69500;
        }

        .kembali{
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .kembali:hover{
            text-decoration: underline;
        }

    </style>

</head>
<body>

<div class="container">

    <h2>Edit Produk Telur</h2>

    <form method="POST">

        <label>Nama Telur</label>
        <input type="text"
        name="nama_telur"
        value="<?php echo $row['nama_telur']; ?>"
        required>

        <label>Jenis Telur</label>
        <input type="text"
        name="jenis_telur"
        value="<?php echo $row['jenis_telur']; ?>"
        required>

        <label>Harga</label>
        <input type="number"
        name="harga"
        value="<?php echo $row['harga']; ?>"
        required>

        <label>Stok</label>
        <input type="number"
        name="stok"
        value="<?php echo $row['stok']; ?>"
        required>

        <label>Supplier</label>
        <input type="text"
        name="supplier"
        value="<?php echo $row['supplier']; ?>"
        required>

        <button type="submit"
        name="update"
        class="btn">
            Update Data
        </button>

    </form>

    <a href="data.php" class="kembali">
        ← Kembali ke Data Produk
    </a>

</div>

</body>
</html>