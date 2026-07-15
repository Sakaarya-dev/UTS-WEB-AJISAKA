<?php
session_start();

// 1. Pengaman: Pastikan user sudah login
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// 2. Ambil ID transaksi dari URL (Contoh: cetak_nota.php?id=5)
$id_pembelian = $_GET['id'] ?? '';

if(empty($id_pembelian)){
    echo "<script>alert('ID Transaksi tidak ditemukan!'); window.close();</script>";
    exit;
}

// 3. Ambil ID user yang sedang login untuk memastikan pembeli hanya bisa mencetak notanya sendiri
$user_id = $_SESSION['id'] ?? '';
$role = $_SESSION['role'] ?? '';

// 4. Query ambil data transaksi (Silakan sesuaikan nama kolom & tabelnya jika berbeda)
// Query ini menghubungkan tabel pembelian dengan tabel produk dan users
if($role == 'admin') {
    // Jika admin yang akses, bisa cetak nota mana saja
    $query = mysqli_query($conn, "SELECT pembelian.*, users.username, produk.nama_telur, produk.harga 
                                  FROM pembelian 
                                  JOIN users ON pembelian.user_id = users.id 
                                  JOIN produk ON pembelian.produk_id = produk.id 
                                  WHERE pembelian.id = '$id_pembelian'");
} else {
    // Jika pembeli, harus dicocokkan dengan user_id-nya agar tidak bisa mengintip nota orang lain
    $query = mysqli_query($conn, "SELECT pembelian.*, users.username, produk.nama_telur, produk.harga 
                                  FROM pembelian 
                                  JOIN users ON pembelian.user_id = users.id 
                                  JOIN produk ON pembelian.produk_id = produk.id 
                                  WHERE pembelian.id = '$id_pembelian' AND pembelian.user_id = '$user_id'");
}

$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan atau pembeli mencoba mengakses nota orang lain
if(!$data){
    echo "<script>alert('Data transaksi tidak ditemukan atau Anda tidak memiliki akses!'); window.close();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran #<?php echo $data['id']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font khas struk/nota */
            margin: 20px;
            color: #000;
            background-color: #fff;
        }
        .nota-container {
            width: 380px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .garis-pembatas {
            border-top: 1px dashed #000;
            margin: 12px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 4px 0;
            font-size: 14px;
        }
        .judul {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .sub-judul {
            font-size: 12px;
            margin-bottom: 10px;
        }
        /* Menyembunyikan tombol navigasi saat dicetak ke kertas */
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
            .nota-container {
                border: none; /* Menghilangkan border kotak saat dicetak asli */
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨️ Cetak Nota
        </button>
        <a href="riwayat.php" style="display: inline-block; padding: 8px 16px; background: #0288d1; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            ⬅️ Kembali ke Riwayat
        </a>
    </div>

    <div class="nota-container">
        <div class="text-center">
            <div class="judul">SAKA POULTRY</div>
            <div class="sub-judul">Jl. Raya Soloraya No. 12, Surakarta <br> Telp: 08123456789</div>
        </div>

        <div class="garis-pembatas"></div>

        <table>
            <tr>
                <td><strong>No. Nota:</strong> #TRX-<?php echo $data['id']; ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal:</strong> <?php echo $data['tanggal'] ?? date('Y-m-d H:i'); ?></td>
            </tr>
            <tr>
                <td><strong>Pelanggan:</strong> <?php echo $data['username']; ?></td>
            </tr>
        </table>

        <div class="garis-pembatas"></div>

        <table>
            <tr>
                <td>
                    <strong><?php echo $data['nama_telur']; ?></strong> <br>
                    <?php echo $data['jumlah']; ?> x Rp <?php echo number_format($data['harga']); ?>
                </td>
                <td class="text-right" style="vertical-align: bottom;">
                    Rp <?php echo number_format($data['total_harga'] ?? ($data['harga'] * $data['jumlah'])); ?>
                </td>
            </tr>
        </table>

        <div class="garis-pembatas"></div>

        <table>
            <tr>
                <td><strong>TOTAL BAYAR</strong></td>
                <td class="text-right"><strong>Rp <?php echo number_format($data['total_harga']); ?></strong></td>
            </tr>
        </table>

        <div class="garis-pembatas"></div>

        <div class="text-center" style="font-size: 12px; font-style: italic; margin-top: 15px;">
            Terima kasih telah berbelanja!<br>
            Telur segar, gizi mantap.
        </div>
    </div>

    <script>
        // Otomatis memicu printer saat halaman selesai dimuat oleh browser
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
