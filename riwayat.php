<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$user_id = $_SESSION['id'];

$query = mysqli_query($conn,
"SELECT * FROM pembelian
WHERE user_id='$user_id'
ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Riwayat Pembelian</title>

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
    background-attachment:fixed;

    min-height:100vh;

    color:white;

    padding:40px 20px;
}

.container{
    width:95%;
    max-width:1200px;

    margin:auto;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(12px);

    padding:30px;

    border-radius:25px;

    border:
    1px solid rgba(255,255,255,0.15);

    box-shadow:
    0 10px 25px rgba(0,0,0,0.25);
}

h1{
    margin-bottom:25px;
    font-size:36px;
}

h1 i{
    color:#ffd166;
}

.back{
    display:inline-block;

    margin-bottom:25px;

    text-decoration:none;

    background:
    linear-gradient(45deg,#6a994e,#386641);

    color:white;

    padding:12px 22px;

    border-radius:12px;

    font-weight:600;

    transition:0.3s;
}

.back:hover{
    transform:translateY(-3px);

    box-shadow:
    0 0 18px rgba(106,153,78,0.6);
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

    font-size:16px;
}

td{
    background:rgba(255,255,255,0.08);

    padding:16px;

    text-align:center;

    transition:0.3s;
}

tr:hover td{
    background:rgba(255,255,255,0.15);
}

.total{
    color:#ffd166;
    font-weight:bold;
}

.tanggal{
    color:#90e0ef;
    font-size:14px;
}

.kosong{
    text-align:center;
    padding:30px;
    color:#eee;
}

.rating-form{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.rating-form select,
.rating-form input{
    padding:8px;

    border:none;

    border-radius:8px;

    outline:none;
}

.rating-form button{
    padding:8px;

    border:none;

    border-radius:8px;

    background:
    linear-gradient(45deg,#6a994e,#386641);

    color:white;

    cursor:pointer;

    transition:0.3s;
}

.rating-form button:hover{
    transform:scale(1.03);
}

@media(max-width:768px){

    .container{
        overflow-x:auto;
    }

    table{
        min-width:1100px;
    }

    h1{
        font-size:28px;
    }

}

</style>

</head>
<body>

<div class="container">

    <h1>
        <i class="fa-solid fa-clock-rotate-left"></i>
        Riwayat Pembelian
    </h1>

    <a href="dashboard.php" class="back">
        ← Kembali ke Dashboard
    </a>

    <table>

        <tr>

            <th>No</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
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

                if($data['tanggal'] == "" || $data['tanggal'] == "0000-00-00 00:00:00"){

                    echo "-";

                }else{

                    date_default_timezone_set('Asia/Jakarta');

                    echo date(
                    'd M Y - H:i',
                    strtotime($data['tanggal'])
                    );

                }

                ?>

            </td>

            <?php if(empty($data['rating'])){ ?>

            <td colspan="2">

                <form method="POST"
                action="rating.php"
                class="rating-form">

                    <input type="hidden"
                    name="id"
                    value="<?php echo $data['id']; ?>">

                    <select name="rating" required>

                        <option value="">
                            Rating
                        </option>

                        <option value="1">⭐ 1</option>
                        <option value="2">⭐⭐ 2</option>
                        <option value="3">⭐⭐⭐ 3</option>
                        <option value="4">⭐⭐⭐⭐ 4</option>
                        <option value="5">⭐⭐⭐⭐⭐ 5</option>

                    </select>

                    <input type="text"
                    name="ulasan"
                    placeholder="Tulis ulasan..."
                    required>

                    <button type="submit">
                        Kirim
                    </button>

                </form>

            </td>

            <?php } else { ?>

            <td>
                <?php echo $data['rating']; ?> ⭐
            </td>

            <td>
                <?php echo $data['ulasan']; ?>
            </td>

            <?php } ?>

        </tr>

        <?php
            }

        } else {
        ?>

        <tr>

            <td colspan="8" class="kosong">
                Belum ada pembelian
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>