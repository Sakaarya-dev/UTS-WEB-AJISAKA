<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #4CAF50; color: white; border: none; border-radius: 5px;">Cetak Sekarang</button>
        <a href="data.php" style="padding: 10px 20px; background: #e63946; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">Kembali</a>
    </div>

    <h2>Laporan Data Produk Telur</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jenis</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Supplier</th>
        </tr>
        <?php
        $no = 1;
        while($data = mysqli_fetch_assoc($query)){
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['nama_telur']; ?></td>
            <td><?php echo $data['jenis_telur']; ?></td>
            <td>Rp <?php echo number_format($data['harga']); ?></td>
            <td><?php echo $data['stok']; ?></td>
            <td><?php echo isset($data['supplier']) ? $data['supplier'] : '-'; ?></td>
        </tr>
        <?php } ?>
    </table>

    <script>
        // Otomatis membuka dialog print saat halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
