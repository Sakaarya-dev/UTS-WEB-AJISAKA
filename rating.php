<?php
include 'koneksi.php';

$id = $_POST['id'];
$rating = $_POST['rating'];
$ulasan = $_POST['ulasan'];

mysqli_query($conn,
"UPDATE pembelian
SET
rating='$rating',
ulasan='$ulasan'
WHERE id='$id'");

echo "
<script>
alert('Rating berhasil dikirim!');
window.location='riwayat.php';
</script>
";
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Rating Produk</title>

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

    display:flex;
    justify-content:center;
    align-items:center;

    color:white;
}

.container{
    width:420px;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(12px);

    padding:35px;

    border-radius:25px;

    border:
    1px solid rgba(255,255,255,0.15);

    box-shadow:
    0 10px 30px rgba(0,0,0,0.3);

    animation:fadeUp 0.8s ease;
}

h2{
    text-align:center;

    margin-bottom:25px;

    font-size:30px;
}

h2 i{
    color:#ffd166;
}

label{
    display:block;

    margin-bottom:8px;

    font-weight:600;
}

select,
textarea{
    width:100%;

    padding:14px;

    border:none;

    border-radius:12px;

    margin-bottom:20px;

    font-size:15px;

    outline:none;
}

textarea{
    resize:none;
    height:120px;
}

button{
    width:100%;

    padding:14px;

    border:none;

    border-radius:12px;

    background:
    linear-gradient(45deg,#6a994e,#386641);

    color:white;

    font-size:16px;
    font-weight:600;

    cursor:pointer;

    transition:0.3s;

    box-shadow:
    0 0 10px rgba(106,153,78,0.5);
}

button:hover{
    transform:translateY(-3px);

    box-shadow:
    0 0 20px rgba(106,153,78,0.9);
}

.back{
    display:block;

    margin-top:18px;

    text-align:center;

    text-decoration:none;

    color:white;

    transition:0.3s;
}

.back:hover{
    color:#ffd166;
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

</style>

</head>
<body>

<div class="container">

    <h2>
        <i class="fa-solid fa-star"></i>
        Rating Produk
    </h2>

    <form method="POST">

        <input type="hidden"
        name="id"
        
        <label>Rating</label>

        <select name="rating" required>

            <option value="">-- Pilih Rating --</option>

            <option value="1">⭐ 1</option>
            <option value="2">⭐⭐ 2</option>
            <option value="3">⭐⭐⭐ 3</option>
            <option value="4">⭐⭐⭐⭐ 4</option>
            <option value="5">⭐⭐⭐⭐⭐ 5</option>

        </select>

        <label>Ulasan</label>

        <textarea
        name="ulasan"
        placeholder="Tulis ulasan produk..."
        required></textarea>

        <button type="submit" name="submit">

            <i class="fa-solid fa-paper-plane"></i>
            Kirim Rating

        </button>

    </form>

    <a href="riwayat.php" class="back">
        ← Kembali ke Riwayat
    </a>

</div>

</body>
</html>