<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/* =========================
   CEK ROLE ADMIN
========================= */

if($_SESSION['role'] != 'admin'){
    header("Location: dashboard.php");
    exit;
}

/* =========================
   TAMBAH PRODUK
========================= */

if(isset($_POST['submit'])){

    $nama  = mysqli_real_escape_string($conn, $_POST['nama_telur']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_telur']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok  = mysqli_real_escape_string($conn, $_POST['stok']);

    /* =========================
       UPLOAD GAMBAR
    ========================= */

    $folder = "gambar_produk/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    $nama_gambar = "";

    if($gambar != ""){

        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

        $format_valid = ['jpg','jpeg','png','webp'];

        if(in_array($ext, $format_valid)){

            $nama_gambar = time() . "_" . $gambar;

            move_uploaded_file(
                $tmp,
                $folder . $nama_gambar
            );

        } else {

            echo "
            <script>
                alert('Format gambar harus JPG, JPEG, PNG, atau WEBP!');
                window.location='tambah.php';
            </script>
            ";
            exit;
        }
    }

    /* =========================
       SIMPAN DATABASE
    ========================= */

    $query = mysqli_query($conn,
    "INSERT INTO produk
    (nama_telur, jenis_telur, harga, stok, gambar)
    VALUES
    ('$nama','$jenis','$harga','$stok','$nama_gambar')");

    if($query){

        echo "
        <script>
            alert('Produk berhasil ditambahkan!');
            window.location='data.php';
        </script>
        ";

    } else {

        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: Arial, sans-serif;

            background:
            linear-gradient(
            rgba(0,0,0,0.5),
            rgba(0,0,0,0.5)),
            url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

            background-size:cover;
            background-position:center;

            min-height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;
        }

        .container{
            width:450px;

            background:rgba(255,255,255,0.12);

            backdrop-filter:blur(10px);

            padding:30px;

            border-radius:20px;

            color:white;
        }

        h2{
            text-align:center;
            margin-bottom:25px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        }

        input{
            width:100%;
            padding:12px;

            border:none;
            border-radius:10px;

            margin-bottom:20px;
        }

        button{
            width:100%;
            padding:14px;

            border:none;
            border-radius:10px;

            background:#4CAF50;
            color:white;

            font-size:16px;
            cursor:pointer;
        }

        button:hover{
            background:#43a047;
        }

        .kembali{
            display:block;
            margin-top:15px;

            text-align:center;
            color:white;

            text-decoration:none;
        }

    </style>

</head>
<body>

<div class="container">

    <h2>Tambah Produk</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Nama Telur</label>
        <input type="text" name="nama_telur" required>

        <label>Jenis Telur</label>
        <input type="text" name="jenis_telur" required>

        <label>Harga</label>
        <input type="number" name="harga" required>

        <label>Stok</label>
        <input type="number" name="stok" required>

        <label>Upload Gambar</label>
        <input type="file" name="gambar" accept="image/*" required>

        <button type="submit" name="submit">
            Simpan Produk
        </button>

    </form>

    <a href="dashboard.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

</div>

</body>
</html>