<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$data = mysqli_query($conn,
"SELECT * FROM transaksi ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container{
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2{
            margin-bottom: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        th{
            background: #17a2b8;
            color: white;
        }

        th, td{
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        tr:nth-child(even){
            background: #f9f9f9;
        }

        .btn{
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

    </style>

</head>
<body>

<div class="container">

    <a href="dashboard.php" class="btn">
        Dashboard
    </a>

    <h2>Data Transaksi</h2>

    <table>

        <tr>
            <th>No</th>
            <th>Nama Pembeli</th>
            <th>Nama Telur</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
            <th>Tanggal</th>
        </tr>

        <?php
        $no = 1;

        while($row = mysqli_fetch_assoc($data)){
        ?>

        <tr>

            <td><?php echo $no++; ?></td>

            <td><?php echo $row['nama_pembeli']; ?></td>

            <td><?php echo $row['nama_telur']; ?></td>

            <td><?php echo $row['jumlah']; ?></td>

            <td>
                Rp <?php echo number_format($row['total_harga']); ?>
            </td>

            <td><?php echo $row['tanggal']; ?></td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>