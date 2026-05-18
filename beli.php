<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($conn,
"SELECT * FROM produk WHERE id='$id'");

$data = mysqli_fetch_assoc($query);

if(!$data){
    die("Produk tidak ditemukan!");
}

if(isset($_POST['beli'])){

    $user_id = $_SESSION['id'];

    $produk_id = $data['id'];

    $nama_produk = $data['nama_telur'];

    $harga = $data['harga'];

    $jumlah = $_POST['jumlah'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');
    /* TOTAL */

    $total = $harga * $jumlah;

    /* CEK STOK */

    if($jumlah > $data['stok']){

        echo "
        <script>
            alert('Stok tidak cukup!');
            window.location='data.php';
        </script>
        ";

        exit;
    }

    /* SIMPAN PEMBELIAN */

    mysqli_query($conn,
    "INSERT INTO pembelian
    (user_id, produk_id, nama_produk, harga, jumlah, total_harga, tanggal)
    VALUES
    (
    '$user_id',
    '$produk_id',
    '$nama_produk',
    '$harga',
    '$jumlah',
    '$total',
    '$tanggal'
    )");

    /* UPDATE STOK */

    $stok_baru = $data['stok'] - $jumlah;

    mysqli_query($conn,
    "UPDATE produk
    SET stok='$stok_baru'
    WHERE id='$id'");

    echo "
    <script>
        alert('Pembelian berhasil!');
        window.location='riwayat.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Beli Produk</title>

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
    rgba(0,0,0,0.5),
    rgba(0,0,0,0.5)),

    url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7');

    background-size:cover;
    background-position:center;

    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    color:white;
}

.container{
    width:450px;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(10px);

    padding:35px;

    border-radius:20px;

    box-shadow:
    0 10px 25px rgba(0,0,0,0.25);
}

h2{
    text-align:center;
    margin-bottom:25px;
}

.info{
    line-height:2;
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
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

    background:#2a9d8f;

    color:white;

    font-size:16px;
    font-weight:600;

    cursor:pointer;

    transition:0.3s;
}

button:hover{
    transform:translateY(-3px);
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

    <h2>
        <i class="fa-solid fa-cart-shopping"></i>
        Beli Produk
    </h2>

    <div class="info">

        <p>
            <b>Produk:</b>
            <?php echo $data['nama_telur']; ?>
        </p>

        <p>
            <b>Harga:</b>
            Rp <?php echo number_format($data['harga']); ?>
        </p>

        <p>
            <b>Stok:</b>
            <?php echo $data['stok']; ?>
        </p>

    </div>

    <form method="POST">

        <label>Jumlah Beli</label>

        <input type="number"
        name="jumlah"
        min="1"
        required>

<label>Metode Pembayaran</label>

<select name="metode" required>

    <option value="">
        -- Pilih Pembayaran --
    </option>

    <option value="Transfer Bank">
        Transfer Bank
    </option>

    <option value="COD">
        COD
    </option>

    <option value="E-Wallet">
        E-Wallet
    </option>
    
    <option value="E-Wallet">
        Manual
    </option>
</select>

</button>
        <button type="submit" name="beli">
            Beli Sekarang
        </button>

    </form>

    <a href="data.php" class="kembali">
        ← Kembali ke Produk
    </a>

</div>

</body>
</html>