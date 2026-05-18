<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/* =========================
   AMBIL DATA PRODUK
========================= */

$id = $_GET['id'];

$query = mysqli_query($conn,
"SELECT * FROM produk WHERE id='$id'");

$data = mysqli_fetch_assoc($query);

/* =========================
   UPDATE PRODUK
========================= */

if(isset($_POST['update'])){

    $nama     = mysqli_real_escape_string($conn, $_POST['nama_telur']);
    $jenis    = mysqli_real_escape_string($conn, $_POST['jenis_telur']);
    $harga    = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok     = mysqli_real_escape_string($conn, $_POST['stok']);
    $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);

    $gambar_lama = $data['gambar'];

    /* =========================
       UPLOAD GAMBAR BARU
    ========================= */

    $folder = "gambar_produk/";

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if($gambar != ""){

        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

        $format_valid = ['jpg','jpeg','png','webp'];

        if(in_array($ext, $format_valid)){

            $nama_gambar = time().'_'.$gambar;

            move_uploaded_file(
                $tmp,
                $folder.$nama_gambar
            );

            // hapus gambar lama
            if($gambar_lama != "" &&
               file_exists($folder.$gambar_lama)){

                unlink($folder.$gambar_lama);
            }

        }else{

            echo "
            <script>
                alert('Format gambar tidak valid!');
                window.location='edit.php?id=$id';
            </script>
            ";
            exit;
        }

    }else{

        // kalau tidak upload gambar baru
        $nama_gambar = $gambar_lama;
    }

    /* =========================
       UPDATE DATABASE
    ========================= */

    $update = mysqli_query($conn,
    "UPDATE produk SET

    nama_telur='$nama',
    jenis_telur='$jenis',
    harga='$harga',
    stok='$stok',
    supplier='$supplier',
    gambar='$nama_gambar'

    WHERE id='$id'
    ");

    if($update){

        echo "
        <script>
            alert('Produk berhasil diupdate!');
            window.location='data.php';
        </script>
        ";

    }else{

        echo "
        <script>
            alert('Gagal update produk!');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Edit Produk</title>

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
    rgba(0,0,0,0.55),
    rgba(0,0,0,0.55)),

    url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

    background-size:cover;
    background-position:center;

    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    padding:30px;
}

.card{

    width:100%;
    max-width:520px;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(12px);

    border-radius:25px;

    padding:35px;

    color:white;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.3);
}

h1{
    text-align:center;
    margin-bottom:30px;
}

label{
    display:block;
    margin-bottom:8px;
    margin-top:15px;
    font-weight:600;
}

input{

    width:100%;

    padding:14px;

    border:none;

    border-radius:12px;

    outline:none;

    font-size:15px;
}

.gambar-preview{

    width:120px;
    height:120px;

    object-fit:cover;

    border-radius:15px;

    margin-top:10px;

    border:3px solid rgba(255,255,255,0.3);
}

.btn{

    width:100%;

    margin-top:25px;

    padding:15px;

    border:none;

    border-radius:12px;

    background:
    linear-gradient(45deg,#fca311,#ff8800);

    color:white;

    font-size:16px;
    font-weight:bold;

    cursor:pointer;

    transition:0.3s;
}

.btn:hover{
    transform:translateY(-3px);
}

.kembali{

    display:inline-block;

    margin-top:20px;

    color:white;

    text-decoration:none;

    font-weight:600;
}

</style>

</head>
<body>

<div class="card">

    <h1>
        <i class="fa-solid fa-pen-to-square"></i>
        Edit Produk Telur
    </h1>

    <form method="POST"
    enctype="multipart/form-data">

        <label>Nama Telur</label>

        <input type="text"
        name="nama_telur"
        value="<?php echo $data['nama_telur']; ?>"
        required>

        <label>Jenis Telur</label>

        <input type="text"
        name="jenis_telur"
        value="<?php echo $data['jenis_telur']; ?>"
        required>

        <label>Harga</label>

        <input type="number"
        name="harga"
        value="<?php echo $data['harga']; ?>"
        required>

        <label>Stok</label>

        <input type="number"
        name="stok"
        value="<?php echo $data['stok']; ?>"
        required>

        <label>Supplier</label>

        <input type="text"
        name="supplier"
        value="<?php echo $data['supplier']; ?>"
        required>

        <label>Gambar Saat Ini</label>

        <?php if($data['gambar'] != ""){ ?>

            <img
            src="gambar_produk/<?php echo $data['gambar']; ?>"
            class="gambar-preview">

        <?php } ?>

        <label>Upload Gambar Baru</label>

        <input type="file"
        name="gambar">

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