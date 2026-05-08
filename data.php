<?php
include 'koneksi.php';

$data = mysqli_query($conn,
"SELECT * FROM produk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Produk</title>

    <style>

        body{
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }

        .container{
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        h2{
            margin-bottom: 20px;
        }

        a{
            text-decoration: none;
            background: #ff7e5f;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid #ccc;
        }

        th{
            background: #ff7e5f;
            color: white;
        }

        th, td{
            padding: 10px;
            text-align: center;
        }

    </style>

</head>
<body>

<div class="container">

<h2>Data Produk Telur</h2>

<a href="tambah.php">
    Tambah Data
</a>

<table>

<tr>
    <th>No</th>
    <th>Nama Telur</th>
    <th>Jenis</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Supplier</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;

while($row = mysqli_fetch_assoc($data)){
?>

<tr>

<td><?php echo $no++; ?></td>

<td><?php echo $row['nama_telur']; ?></td>

<td><?php echo $row['jenis_telur']; ?></td>

<td>Rp <?php echo number_format($row['harga']); ?></td>

<td><?php echo $row['stok']; ?></td>

<td><?php echo $row['supplier']; ?></td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>">
    Edit
</a>

<a href="hapus.php?id=<?php echo $row['id']; ?>">
    Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>