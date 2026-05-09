<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$data = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Produk Telur</title>

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
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        h2{
            color: #333;
        }

        .btn{
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .tambah{
            background: #28a745;
        }

        .beli{
            background: #17a2b8;
        }

        .edit{
            background: orange;
        }

        .hapus{
            background: red;
        }

        .kembali{
            background: #007bff;
        }

        .btn:hover{
            opacity: 0.9;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th{
            background: #ff7e5f;
            color: white;
        }

        table th,
        table td{
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table tr:nth-child(even){
            background: #f9f9f9;
        }

        table tr:hover{
            background: #f1f1f1;
        }

        .aksi{
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        @media(max-width: 768px){

            .header{
                flex-direction: column;
                align-items: flex-start;
            }

            table{
                font-size: 12px;
            }

            .btn{
                font-size: 12px;
                padding: 8px 10px;
            }

        }

    </style>

</head>
<body>

<div class="container">

    <div class="header">

        <h2>Data Produk Telur</h2>

        <div>

            <a href="dashboard.php" class="btn kembali">
                Dashboard
            </a>

            <a href="tambah.php" class="btn tambah">
                + Tambah Data
            </a>

        </div>

    </div>

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

            <td>
                Rp <?php echo number_format($row['harga/Kg']); ?>
            </td>

            <td><?php echo $row['stok']; ?></td>

            <td><?php echo $row['supplier']; ?></td>

            <td>

                <div class="aksi">

                    <a class="btn beli"
                    href="beli.php?id=<?php echo $row['id']; ?>">
                        Beli
                    </a>

                    <a class="btn edit"
                    href="edit.php?id=<?php echo $row['id']; ?>">
                        Edit
                    </a>

                    <a class="btn hapus"
                    href="hapus.php?id=<?php echo $row['id']; ?>"
                    onclick="return confirm('Yakin ingin menghapus data?')">
                        Hapus
                    </a>

                </div>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>